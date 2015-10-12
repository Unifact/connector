<?php /* created by Rob van Bentem, 12/10/2015 */

namespace Unifact\Connector\Log;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Unifact\Connector\Log\Handlers\DatabaseHandler;

class ConnectorLogger extends Logger
{

    /**
     * @return Logger
     */
    public static function make()
    {
        // @todo get from config etc..
        $log = new ConnectorLogger('test');

        $log->pushHandler(new StreamHandler('c:/tmp/connector_log.txt', Logger::DEBUG));
        $log->pushHandler(new DatabaseHandler(Logger::INFO));

        return $log;
    }
}
