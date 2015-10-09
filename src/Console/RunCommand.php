<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Console;

use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Unifact\Connector\Events\ConnectorRegisterHandlerEvent;
use Unifact\Connector\Events\ConnectorRunCronEvent;
use Unifact\Connector\Events\ConnectorRunJobEvent;
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
     * RunCommand constructor.
     * @param JobContract $jobRepo
     */
    public function __construct(JobContract $jobRepo)
    {
        parent::__construct();

        $this->jobRepo = $jobRepo;
        $this->manager = new Manager();
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


        $jobs = $this->jobRepo->filter([
            'status' => 'new'
        ]);

        foreach($jobs as $job){
            \Event::fire(new ConnectorRunJobEvent($job));
            $this->manager->handleJob($job);
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
            $this->output->write('--> '.$text, $newLine, $style);
        }
    }
}
