<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Console;

use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Unifact\Connector\Events\ConnectorRegisterHandlerEvent;
use Unifact\Connector\Events\ConnectorRunCronEvent;
use Unifact\Connector\Handler\Manager;

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

    public function fire()
    {
        $manager = new Manager();

        // Allow handlers to register at the Manager
        $this->out('Registering handlers.');
        \Event::fire(new ConnectorRegisterHandlerEvent($manager));

        // Run cronjobs
        $this->out('Running cronjobs.');
        \Event::fire(new ConnectorRunCronEvent());

        // Handle jobs
        $this->out('Handling Jobs.');
        //\Event::fire(new ConnectorRunJobEvent());
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
