<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Events;

use App\Events\Event;
use Unifact\Connector\Handler\Manager;

class ConnectorRegisterHandlerEvent extends Event
{
    /**
     * @var Manager
     */
    public $manager;

    /**
     * ConnectorRegisterHandlerEvent constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }
}
