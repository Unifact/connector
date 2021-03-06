<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler;

use Cron\CronExpression;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Unifact\Connector\Events\ConnectorRunJobEvent;
use Unifact\Connector\Exceptions\HandlerException;
use Unifact\Connector\Handler\Interfaces\IJobHandler;
use Unifact\Connector\Jobs\JobQueueHandler;
use Unifact\Connector\Log\ConnectorLoggerInterface;
use Unifact\Connector\Log\StateOracle;
use Unifact\Connector\Models\Job;
use Unifact\Connector\Repository\JobContract;
use Unifact\Connector\Repository\StageContract;

class Manager
{
    use DispatchesJobs;

    /**
     * @var Collection
     */
    protected $handlers;

    /**
     * @var Collection
     */
    protected $crons;

    /**
     * @var StateOracle
     */
    private $oracle;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var JobContract
     */
    private $jobRepo;

    /**
     * @var array
     */
    protected $handleOrder = [
        'retry',
        'restart',
        'new',
    ];

    /**
     * @var StageContract
     */
    private $stageRepo;

    /**
     * Manager constructor.
     * @param JobContract $jobRepo
     * @param StateOracle $oracle
     * @param ConnectorLoggerInterface $logger
     * @param StageContract $stageRepo
     */
    public function __construct(JobContract $jobRepo, StateOracle $oracle, ConnectorLoggerInterface $logger, StageContract $stageRepo)
    {
        $this->handlers = new Collection();
        $this->crons = new Collection();
        $this->oracle = $oracle;
        $this->logger = $logger;
        $this->jobRepo = $jobRepo;
        $this->stageRepo = $stageRepo;
    }

    /**
     * @param IJobHandler $handler
     */
    public function pushHandler(IJobHandler $handler)
    {
        $this->handlers->offsetSet($handler->getType(), $handler);
    }

    /**
     * @param $classNamespace
     */
    public function pushCron($classNamespace)
    {
        $this->crons->push($classNamespace);
    }

    /**
     * @param $type
     * @return IJobHandler
     * @throws HandlerException
     */
    public function getHandlerForType($type)
    {
        if ($this->hasHandlerForType($type)) {
            return $this->handlers->offsetGet($type);
        }

        throw new HandlerException("No handler of type {$type} exists.");
    }

    /**
     * @param $type
     * @return bool
     */
    public function hasHandlerForType($type)
    {
        return $this->handlers->offsetExists($type);
    }

    /**
     * Run registered cronjobs
     * @param $date
     */
    public function crons($date)
    {
        foreach ($this->crons as $class) {
            $cron = CronExpression::factory($class::getCronSchedule());

            if ($cron->isDue($date)) {
                $this->logger->info("Running cronjob '{$class}''");
                try {
                    $this->dispatch(app($class));
                } catch (\Exception $e) {
                    $this->logger->error("Error while running cronjob '{$class}''", [
                        'oracle' => $this->oracle->asArray(),
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }
        }
    }

    /**
     * Main enty point to handling Jobs, is called from the RunCommand class
     */
    public function start()
    {
        foreach ($this->handleOrder as $status) {
            $this->logger->info("Starting Jobs with status '{$status}'");
            $jobs = $this->getJobsWithStatus($status);

            while (sizeof($jobs) > 0) {
                $this->handleJobs($jobs);
                $jobs = $this->getJobsWithStatus($status);
            }
        }
    }

    /**
     * @param Collection $jobs
     */
    protected function handleJobs(Collection $jobs)
    {
        foreach ($jobs as $job) {
            try {
                $this->oracle->reset($job->id);

                $this->logger->info("Queueing new '{$job->type}' Job");

                $this->logger->debug('Firing ConnectorRunJobEvent before queueing Job');

                \Event::fire(new ConnectorRunJobEvent($job));

                $this->queueJob($job);

                $this->oracle->reset();
            } catch (\Exception $e) {
                $this->logger->critical('Unexpected exception while queueing Job (requires in-depth investigation)', [
                    'oracle' => $this->oracle->asArray(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    /**
     * @param Job $job
     * @return bool
     * @throws HandlerException
     */
    protected function queueJob(Job $job)
    {
        if ($this->hasHandlerForType($job->type)) {
            $threshold = env('CONNECTOR_QUEUE_HIGH_THRESHOLD');

            $queue = ($job->priority >= $threshold) ? env('CONNECTOR_QUEUE_HIGH') : env('CONNECTOR_QUEUE_LOW');

            if ($threshold == null || $queue == null) {
                throw new \InvalidArgumentException("Threshold or queue is not set");
            }

            $this->jobRepo->update($job->id, [
                'status' => 'queued',
                'handler' => get_class($this->getHandlerForType($job->type)),
            ]);

            \Queue::push(JobQueueHandler::class, ['job_id' => $job->id, 'previous_status' => $job->status], $queue);

            return true;
        }

        $this->jobRepo->update($job->id, [
            'status' => 'error',
        ]);

        $this->logger->error("No handler registered for type '{$job->type}'");

        return false;
    }

    /**
     * @param $status
     * @return \Unifact\Connector\Models\Job[]
     */
    protected function getJobsWithStatus($status)
    {
        return $this->jobRepo->latest(100, [['status', $status]], 'id', 'asc');
    }

}
