<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Console;

use Illuminate\Console\Command;
use Unifact\Connector\Events\ConnectorRegisterHandlerEvent;
use Unifact\Connector\Events\ConnectorRunCronEvent;
use Unifact\Connector\Events\ConnectorRunJobEvent;
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
        \Event::fire(new ConnectorRegisterHandlerEvent($manager));

        // Run cronjobs
        \Event::fire(new ConnectorRunCronEvent());

        // Handle jobs
        //\Event::fire(new ConnectorRunJobEvent());
    }
}
