<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler;

use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Unifact\Connector\Events\ConnectorRunJobEvent;
use Unifact\Connector\Exceptions\HandlerException;
use Unifact\Connector\Handler\Interfaces\IJobHandler;
use Unifact\Connector\Log\StateOracle;
use Unifact\Connector\Models\Job;
use Unifact\Connector\Repository\JobContract;

class Manager
{
    /**
     * @var Collection
     */
    protected $handlers;

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

    protected $handleOrder = [
        'retry',
        'restart',
        'new'
    ];

    /**
     * Manager constructor.
     * @param JobContract $jobRepo
     * @param StateOracle $oracle
     * @param LoggerInterface $logger
     */
    public function __construct(JobContract $jobRepo, StateOracle $oracle, LoggerInterface $logger)
    {
        $this->handlers = new Collection();
        $this->oracle = $oracle;
        $this->logger = $logger;
        $this->jobRepo = $jobRepo;
    }

    /**
     * @param IJobHandler $handler
     */
    public function pushHandler(IJobHandler $handler)
    {
        $this->handlers->offsetSet($handler->getType(), $handler);
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
     * Main enty point to handling Jobs, is called from the RunCommand class
     */
    public function start()
    {
        foreach ($this->handleOrder as $status) {
            $jobs = $this->jobRepo->filter([['status', $status]]);

            $this->logger->info("Starting Jobs with status '{$status}'");
            $this->handleJobs($jobs);
        }
    }

    /**
     * @param Collection $jobs
     */
    protected function handleJobs(Collection $jobs)
    {
        foreach ($jobs as $job) {
            try {
                $this->oracle->setJobId($job->id);

                $this->logger->info("Starting new '{$job->type}' Job");

                $this->logger->debug('Firing ConnectorRunJobEvent before handling Job');
                \Event::fire(new ConnectorRunJobEvent($job));

                $this->logger->debug('Starting Job handling procedure');
                if (!$this->handleJob($job)) {
                    $this->logger->notice('Controlled failure of handleJob (FALSE returned)');
                }
            } catch (\Exception $e) {
                $this->logger->critical('Unexpected exception while handling Job (requires in-depth investigation)', [
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
    protected function handleJob(Job $job)
    {
        if ($this->hasHandlerForType($job->type)) {
            $handler = $this->getHandlerForType($job->type);

            if (!$handler->prepare()) {
                $this->logger->error('Handler returned FALSE in prepare() method');

                return false;
            }
            if (!$handler->handle($job)) {
                $this->logger->error('Handler returned FALSE in handle() method');

                return false;
            }

            $handler->complete();
            $this->logger->info('Finished Job successfully');

            return true;
        }

        $this->logger->error("No handler registered for type '{$job->type}'");

        return false;
    }

}
