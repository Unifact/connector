<?php

namespace Unifact\Connector\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Unifact\Connector\Events\ConnectorRegisterHandlerEvent;
use Unifact\Connector\Handler\Manager;
use Unifact\Connector\Log\ConnectorLoggerInterface;
use Unifact\Connector\Log\StateOracle;
use Unifact\Connector\Repository\JobContract;

/**
 * Class JobQueueHandler
 * @package Unifact\Connector\Jobs
 */
class JobQueueHandler implements SelfHandling, ShouldQueue
{
    /**
     * @var JobContract
     */
    private $jobRepo;

    /**
     * @var StateOracle
     */
    private $oracle;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param JobContract $jobRepo
     * @param StateOracle $oracle
     * @param ConnectorLoggerInterface $logger
     */
    public function __construct(JobContract $jobRepo, StateOracle $oracle, ConnectorLoggerInterface $logger)
    {
        $this->jobRepo = app(JobContract::class);
        $this->oracle = app(StateOracle::class);
        $this->logger = app(ConnectorLoggerInterface::class);
    }

    /**
     * @param $syncJob
     * @param $arguments
     * @return bool
     * @throws \Unifact\Connector\Exceptions\HandlerException
     */
    public function fire($syncJob, $arguments)
    {
        $job = $this->jobRepo->findById($arguments['job_id']);

        $event = new ConnectorRegisterHandlerEvent(app(Manager::class));

        \Event::fire($event);

        /**
         * @var $manager Manager
         */
        $manager = $event->manager;

        $handler = $manager->getHandlerForType($job->type);

        $this->logger->debug("Preparing Job..");
        if (!$handler->prepare()) {
            $this->logger->error('Handler returned FALSE in prepare() method, see log for details');

            return false;
        }

        $this->logger->debug("Handling Job..");
        if ($handler->handle($job) === false) {
            $this->logger->error('Handler returned FALSE in handle() method, see log for details');

            return false;
        }

        $this->logger->debug("Completing Job..");
        $handler->complete();
        $this->logger->info('Finished Job successfully');

        $syncJob->delete();

        return true;
    }
}
