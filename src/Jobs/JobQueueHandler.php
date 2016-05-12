<?php

namespace Unifact\Connector\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Jobs\Job;
use Psr\Log\LoggerInterface;
use Unifact\Connector\Log\ConnectorLoggerInterface;
use Unifact\Connector\Log\StateOracle;
use Unifact\Connector\Repository\JobContract;

/**
 * Class JobQueueHandler
 * @package Unifact\Connector\Jobs
 */
class JobQueueHandler implements ShouldQueue
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
     * @param Job $syncJob
     * @param $arguments
     * @return bool
     * @throws \Unifact\Connector\Exceptions\HandlerException
     */
    public function fire(Job $syncJob, $arguments)
    {
        try {
            $job = $this->jobRepo->findById($arguments['job_id']);

            $handler = forward_static_call([$job->handler, 'make']);

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
        } catch (\Exception $e) {
            $this->oracle->exception($e);
            $this->logger->error('Exception was thrown in JobQueueHandler::fire method.');

            $this->jobRepo->update($arguments['job_id'], [
                'status' => 'error'
            ]);

            return false;
        }

        $syncJob->delete();

        return true;
    }
}
