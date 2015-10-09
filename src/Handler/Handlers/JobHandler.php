<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler\Handlers;

use Illuminate\Support\Collection;
use Unifact\Connector\Exceptions\ConnectorException;
use Unifact\Connector\Exceptions\HandlerException;
use Unifact\Connector\Handler;
use Unifact\Connector\Handler\Interfaces\IStageProcessor;
use Unifact\Connector\Handler\Stage;
use Unifact\Connector\Models\Job;

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
     * @var
     */
    protected $job;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * IJobHandler constructor.
     * @param string $type
     * @param array $stages
     */
    public function __construct($type, $stages = [])
    {
        parent::__construct();

        $this->type = $type;
        $this->stages = new Collection($stages);
    }

    /**
     * @return bool
     */
    public function prepare()
    {
        return true;
    }

    public function handle(Job $job)
    {
        foreach ($this->stages as $n => $stage) {
            $this->handleStage($job, $stage, $n + 1);
        }
    }

    /**
     * @return void
     */
    public function complete()
    {
        // Do nothing
    }


    protected function handleStage(Job $job, IStageProcessor $stage, $number)
    {
        try {
            $data = $stage->process($job);

            if (is_null($data)) {
                throw new ConnectorException("Data was NULL after processing stage.");
            }

            $job->stages()->save($stageModel);
        } catch (ConnectorException $e) {
            throw new HandlerException($e->getMessage());
        } catch (\Exception $e) {
            throw new HandlerException("Unexpected exception while processing stage (job_id: {$job->id}).");
        }
    }

}
