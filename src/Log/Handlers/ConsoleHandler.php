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
            \App::make('console-connector-logger')->line('test');
        }
    }
}
