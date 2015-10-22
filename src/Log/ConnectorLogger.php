<?php /* created by Rob van Bentem, 12/10/2015 */

namespace Unifact\Connector\Log;

use Monolog\Handler\HipChatHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Unifact\Connector\Log\Handlers\DatabaseHandler;

class ConnectorLogger extends Logger implements ConnectorLoggerInterface
{

    /**
     * @return Logger
     */
    public static function make()
    {
        $config = \Config::get('connector.logging');
        $context = array_get($config, 'context');

        $log = new ConnectorLogger($context);

        $handlers = $config['handlers'];

        if (array_get($handlers, 'file.enabled')) {
            $log->pushHandler(new RotatingFileHandler(storage_path('logs/' . $context), 30,
                array_get($handlers, 'file.level', Logger::DEBUG)));
        }

        if (array_get($handlers, 'db.enabled')) {
            $log->pushHandler(new DatabaseHandler(array_get($handlers, 'db.level', Logger::DEBUG)));
        }

        if (array_get($handlers, 'hipchat.enabled')) {
            $log->pushHandler(new HipChatHandler(
                array_get($handlers, 'hipchat.token'),
                array_get($handlers, 'hipchat.room'),
                array_get($handlers, 'hipchat.name', 'Connector'),
                array_get($handlers, 'hipchat.notify', false),
                array_get($handlers, 'hipchat.level', Logger::ERROR)
            ));
        }

        return $log;
    }
}
