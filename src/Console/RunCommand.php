<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Console;

use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Psr\Log\LoggerInterface;
use Unifact\Connector\Events\ConnectorRegisterHandlerEvent;
use Unifact\Connector\Events\ConnectorRunCronEvent;
use Unifact\Connector\Handler\Manager;
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
     * RunCommand constructor.
     * @param JobContract $jobRepo
     * @param LoggerInterface $logger
     */
    public function __construct(JobContract $jobRepo, LoggerInterface $logger)
    {
        parent::__construct();

        \DB::connection()->statement('SET foreign_key_checks = 0;');
        \DB::connection()->table('connector_job_stages')->truncate();
        \DB::connection()->table('connector_jobs')->truncate();
        \DB::connection()->table('connector_logs')->truncate();
        \DB::connection()->statement('SET foreign_key_checks = 1;');

        $this->jobRepo = $jobRepo;
        $this->manager = app(Manager::class);
        $this->logger = $logger;
    }


    public function fire()
    {
        // Allow handlers to register at the Manager
        $this->registerHandler();

        // Run cronjobs by firing ConnectorRunCronEvent
        $this->runCron();

        // Handle unfinished job in the connector_jobs table
        $this->handleJobs();
    }


    /**
     *
     */
    private function registerHandler()
    {
        $this->out('Registering handlers.');
        \Event::fire(new ConnectorRegisterHandlerEvent($this->manager));
    }

    /**
     *
     */
    private function runCron()
    {
        $this->out('Running cronjobs.');
        \Event::fire(new ConnectorRunCronEvent());
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
                    'trace' => $e->getTraceAsString()
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
