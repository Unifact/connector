<?php

namespace Unifact\Connector\Log\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class ConsoleHandler extends AbstractProcessingHandler
{
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record)
    {
        if(\App::bound('console-connector-logger')){
            app('console-connector-logger')->writeln(sprintf("[%s] %d: %s", $record['datetime']->format('Y-m-d H:i:s'), $record['level'], $record['message']));
        }
    }
}
