<?php


namespace Unifact\Connector\Log;

use Psr\Log\LoggerInterface;

interface ConnectorLoggerInterface extends LoggerInterface
{
    /**
     * @return StateOracle
     */
    public function getOracle();
}
