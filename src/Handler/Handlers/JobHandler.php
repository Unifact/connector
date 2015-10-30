<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler\Handlers;

use Illuminate\Support\Collection;
use Unifact\Connector\Exceptions\ConnectorException;
use Unifact\Connector\Exceptions\HandlerException;
use Unifact\Connector\Handler;
use Unifact\Connector\Handler\Interfaces\IStageProcessor;
use Unifact\Connector\Handler\Stage;
use Unifact\Connector\Log\ConnectorLogger;
use Unifact\Connector\Log\ConnectorLoggerInterface;
use Unifact\Connector\Log\StateOracle;
use Unifact\Connector\Models\Job as JobModel;
use Unifact\Connector\Models\Stage as StageModel;
use Unifact\Connector\Repository\JobContract;
use Unifact\Connector\Repository\StageContract;

abstract class JobHandler extends Handler\Handler implements Handler\Interfaces\IJobHandler
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var Collection|Stage[]
     */
    private $stages;

    /**
     * @var JobModel
     */
    protected $job;

    /**
     * @var JobContract
     */
    private $jobRepo;

    /**
     * @var
     */
    private $stageRepo;

    /**
     * @var ConnectorLogger
     */
    private $logger;

    /**
     * @var StateOracle
     */
    private $oracle;

    /**
     * @var array
     */
    private $stageMapping = [];

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return JobModel
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * IJobHandler constructor.
     * @param string $type
     * @param array $stages
     * @param JobContract $jobRepo
     * @param StageContract $stageRepo
     * @param ConnectorLoggerInterface $logger
     * @param StateOracle $oracle
     */
    public function __construct(
        $type,
        array $stages = [],
        JobContract $jobRepo,
        StageContract $stageRepo,
        ConnectorLoggerInterface $logger,
        StateOracle $oracle
    ) {
        parent::__construct();

        $this->type = $type;
        $this->stages = new Collection($stages);

        $this->jobRepo = $jobRepo;
        $this->stageRepo = $stageRepo;
        $this->logger = $logger;
        $this->oracle = $oracle;
    }

    /**
     * @return bool
     */
    public function prepare()
    {
        if ($this->stages->count() === 0) {
            $this->logger->warning("Job has no stages, cannot continue");

            return false;
        }

        foreach ($this->stages as $n => $stage) {
            $this->stageMapping[$stage->getName()] = $n + 1;
            $stage->setJobHandler($this);
            $stage->setLogger($this->logger);
        }

        $this->logger->debug("Prepared Job with {$this->stages->count()} stages");

        return true;
    }

    /**
     * @param JobModel $job
     * @return bool
     * @throws HandlerException
     */
    public function handle(JobModel $job)
    {
        $success = true;

        $this->job = $job;

        list($fromStageNumber, $preloadedData) = $this->prepareStageData($job);

        foreach ($this->stages as $stage) {
            try {

                $stageNumber = $this->getStageNumber($stage);
                if ($stageNumber < $fromStageNumber) {
                    $this->logger->info("Skipping Stage because of retry");
                    continue;
                }

                $this->oracle->reset($job->id, $stage->getName());
                $this->handleStage($job, $stage, $preloadedData);
                $this->logger->info("Stage successfully processed");
                $this->oracle->reset($job->id);
            } catch (\Exception $e) {
                $this->logger->warning("Exception was thrown in the JobHandler handle() method, cannot continue (status: error)",
                    [
                        'exception_message' => $e->getMessage(),
                        'exception_trace' => $e->getTraceAsString()
                    ]);

                $this->jobRepo->update($job->id, [
                    'status' => JobModel::STATUS_ERROR
                ]);

                $success = false;
                break;
            }
        }

        return $success;
    }

    /**
     * @return void
     */
    public function complete()
    {
        $this->logger->debug('Job completed');
    }


    /**
     * @param JobModel $job
     * @param IStageProcessor $stage
     * @param null $preloadedData is used when status is STATUS_RETRY
     * @throws HandlerException
     */
    protected function handleStage(JobModel $job, IStageProcessor $stage, $preloadedData = null)
    {
        $number = $this->getStageNumber($stage);

        try {
            if ($number === 1) {
                // pass the parsed job data when this is the first stage.
                $data = $stage->process($preloadedData ?: $job->getParsedData());
            } else {
                if ($preloadedData) {
                    $processData = $preloadedData;
                } else {
                    $lastStageNumber = $number - 1;
                    $lastStage = $this->stageRepo->findByJobIdAndStage($job->id, $this->getStageName($lastStageNumber));
                    if (is_null($lastStage)) {
                        throw new ConnectorException("Last Stage output is NULL, array expected.");
                    }
                    $processData = $lastStage->getParsedData();
                }

                // pass the last processed stage's parsed data
                $data = $stage->process($processData);
            }

            if (is_null($data) || $data === false) {
                // $data must be array|object (it needs to be json decodeable to an array).
                throw new ConnectorException("Data was NULL after processing stage.");
            }

            $stageModel = $this->stageRepo->createStub([
                'stage' => $stage->getName(),
                'data' => $data,
                'status' => StageModel::STATUS_PROCESSED
            ]);

            if ($this->jobRepo->attachStage($job->id, $stageModel) === false) {
                throw new HandlerException("Could not attach StageModel to JobModel (database/foreign key issue?).");
            }

            $this->jobRepo->update($job->id, [
                'status' => JobModel::STATUS_HANDLED
            ]);

        } catch (ConnectorException $e) {
            throw new HandlerException($e->getMessage());
        } catch (\Exception $e) {
            throw new HandlerException("Unexpected exception while processing stage (job_id: {$job->id}).");
        }

    }

    /**
     * @param JobModel $job
     * @return array
     * @throws ConnectorException
     */
    protected function prepareStageData(JobModel $job)
    {
        $fromStageNumber = 1;
        $preloadedData = null;

        if ($job->status === JobModel::STATUS_NEW || $job->status === JobModel::STATUS_QUEUED) {
            // Defaults are OK
        } elseif ($job->status === JobModel::STATUS_RETRY) {
            $lastStage = $this->stageRepo->findLastByJobIdAndStatus($job->id, 'processed');

            if ($lastStage !== null) {
                $fromStageNumber = array_get($this->stageMapping, $lastStage->stage);
                $preloadedData = $lastStage->getParsedData();
                $this->deleteFailedStages($lastStage);
            }
        } elseif ($job->status === JobModel::STATUS_RESTART) {
            $this->stageRepo->deleteByJobId($job->id);
            // Defaults are OK
        } else {
            throw new HandlerException("Unexpected Job status (status: {$job->status}), cannot prepare Stages.");
        }

        return [$fromStageNumber, $preloadedData];
    }

    /**
     * Updates the `reference` column in the `connector_jobs` table
     *
     * @param $reference
     */
    public function setJobReference($reference)
    {
        $this->jobRepo->update($this->job->id, [
            'reference' => $reference
        ]);
    }

    /**
     * @param IStageProcessor $stage
     * @return int
     */
    private function getStageNumber(IStageProcessor $stage)
    {
        return array_get($this->stageMapping, $stage->getName());
    }

    /**
     * @param $number
     * @return int|null
     */
    private function getStageName($number)
    {
        foreach ($this->stageMapping as $name => $num) {
            if ($num === $number) {
                return $name;
            }
        }

        return null;
    }

    /**
     * @param $stage
     */
    protected function deleteFailedStages($stage)
    {
        $deletes = $this->jobRepo->filter([['job_id', $stage->job_id], ['id', '>', $stage->id]]);

        foreach ($deletes as $delete) {
            $this->jobRepo->delete($delete->id);
        }
    }
}
