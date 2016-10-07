<?php

namespace Unifact\Connector\Events;

use Illuminate\Support\Facades\Event;
use Unifact\Connector\Handler\Manager;

class ConnectorRegisterEvent extends Event
{
    /**
     * @var Manager
     */
    public $manager;

    /**
     * ConnectorRegisterEvent constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }
}
