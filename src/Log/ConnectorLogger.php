<?php /* created by Rob van Bentem, 12/10/2015 */

namespace Unifact\Connector\Log;

use Monolog\Handler\HipChatHandler;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\SlackHandler;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Logger;
use Unifact\Connector\Log\Handlers\ConsoleHandler;
use Unifact\Connector\Log\Handlers\DatabaseHandler;

class ConnectorLogger extends Logger implements ConnectorLoggerInterface
{
    /**
     * @var StateOracle
     */
    private $oracle;

    /**
     * @return StateOracle
     */
    public function getOracle()
    {
        return $this->oracle;
    }

    /**
     * @param string $name
     * @param StateOracle $oracle
     * @param array $handlers
     * @param array $processors
     */
    public function __construct($name, StateOracle $oracle, array $handlers = [], array $processors = [])
    {
        $this->oracle = $oracle;

        parent::__construct($name, $handlers, $processors);
    }

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function addRecord($level, $message, array $context = [])
    {
        $context = array_merge($this->getOracle()->asArray(), $context);

        $this->oracle->resetException();

        return parent::addRecord($level, $message, $context);
    }

    /**
     * @return Logger
     */
    public static function make()
    {
        $config = \Config::get('connector.logging');
        $context = array_get($config, 'context');

        $log = app(ConnectorLogger::class, [$context]);

        $handlers = $config['handlers'];

        $log->pushHandler(new ConsoleHandler(array_get($handlers, 'db.level', Logger::DEBUG)));

        if (array_get($handlers, 'file.enabled')) {
            $log->pushHandler(new RotatingFileHandler(storage_path('logs/' . $context),
                array_get($handlers, 'file.keep_days', 30),
                array_get($handlers, 'file.level', Logger::DEBUG)
            ));
        }

        if (array_get($handlers, 'db.enabled')) {
            $log->pushHandler(new DatabaseHandler(array_get($handlers, 'db.level', Logger::DEBUG)));
        }

        if (array_get($handlers, 'email.enabled')) {
            foreach (array_get($handlers, 'email.to') as $email) {
                $subject = sprintf('Error in %s application', $context);
                $from = array_get($handlers, 'email.from') ?: sprintf('error@%s', gethostname());

                $log->pushHandler(new NativeMailerHandler(
                    $email,
                    $subject,
                    $from,
                    array_get($handlers, 'email.level')
                ));
            }
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

        if (array_get($handlers, 'slack.enabled')) {
            $log->pushHandler(new SlackWebhookHandler(
                array_get($handlers, 'slack.webhook'),
                array_get($handlers, 'slack.channel'),
                array_get($handlers, 'slack.username'),
                array_get($handlers, 'slack.useAttachment'),
                array_get($handlers, 'slack.iconEmoji'),
                array_get($handlers, 'slack.useShortAttachment'),
                array_get($handlers, 'slack.includeContextAndExtra'),
                array_get($handlers, 'slack.level'),
                array_get($handlers, 'slack.bubble')
            ));
        }

        return $log;
    }
}
