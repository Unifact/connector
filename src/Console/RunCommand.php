<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Console;

use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Psr\Log\LoggerInterface;
use Unifact\Connector\Events\ConnectorRegisterCronEvent;
use Unifact\Connector\Events\ConnectorRegisterEvent;
use Unifact\Connector\Events\ConnectorRegisterHandlerEvent;
use Unifact\Connector\Handler\Manager;
use Unifact\Connector\Log\ConnectorLoggerInterface;
use Unifact\Connector\Repository\JobContract;

class RunCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'connector:run';

    /**
     * @var string
     */
    protected $description = 'Run the Unifact Connector';

    /**
     * @var JobContract
     */
    private $jobRepo;

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Last minute the cron has run
     * @var null
     */
    private $lastCronMin = null;

    /**
     * RunCommand constructor.
     * @param JobContract $jobRepo
     * @param ConnectorLoggerInterface $logger
     */
    public function __construct(JobContract $jobRepo, ConnectorLoggerInterface $logger)
    {
        parent::__construct();

        $this->jobRepo = $jobRepo;
        $this->manager = app(Manager::class);
        $this->logger = $logger;
    }


    public function fire()
    {
        if ($this->option('verbose')) {
            \App::instance('console-connector-logger', $this->output);
        }

        try {
            // Allow crons and handlers to register at the Manager
            $this->fireManagerRegisterEvent();

            for (; ;) {
                // Run cronjobs by firing ConnectorRunCronEvent
                $this->runCron();

                // Handle unfinished job in the connector_jobs table
                $this->handleJobs();

                sleep(5);
            }
        } catch (\Exception $e) {
            $this->logger->getOracle()->exception($e);
            $this->logger->emergency('Exception on the highest level possible, immediate action required');
        }
    }


    /**
     *
     */
    private function fireManagerRegisterEvent()
    {
        $this->out('Registering crons and handlers.');
        \Event::fire(new ConnectorRegisterEvent($this->manager));
    }

    /**
     *
     */
    private function runCron()
    {
        $this->out('Running cronjobs.');

        $min = date('H:i');
        $date = date('c');

        if ($min === $this->lastCronMin) {
            return;
        }

        $this->lastCronMin = $min;

        try {
            $this->manager->crons($date);
        } catch (\Exception $e) {
            $this->logger->emergency("Highly unexpected exception while running Manager->cron(), investigation needed.",
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
        }
    }

    /**
     *
     */
    private function handleJobs()
    {
        // Handle jobs
        $this->out('Handling Jobs.');
        try {
            $this->manager->start();
        } catch (\Exception $e) {
            $this->logger->emergency("Highly unexpected exception while running Manager->start(), investigation needed.",
                [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
        }
    }


    /**
     * @param $text
     * @param bool|true $newLine
     * @param int $style
     */
    protected function out($text, $newLine = true, $style = OutputStyle::OUTPUT_NORMAL)
    {
        if ($this->option('verbose')) {
            $this->output->write('--> ' . $text, $newLine, $style);
        }
    }
}
