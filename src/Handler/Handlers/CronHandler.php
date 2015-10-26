<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler\Handlers;


use Unifact\Connector\Events\ConnectorRunCronEvent;
use Unifact\Connector\Handler\Handler;
use Unifact\Connector\Handler\Interfaces\ICronHandler;
use Unifact\Connector\Log\ConnectorLoggerInterface;
use Unifact\Connector\Repository\JobProviderContract;

abstract class CronHandler extends Handler implements ICronHandler
{
    /**
     * @var JobProviderContract
     */
    protected $jobProvider;

    /**
     * @var ConnectorLoggerInterface
     */
    protected $logger;

    /**
     * @param ConnectorRunCronEvent $event
     * @return bool
     */
    public function handle(ConnectorRunCronEvent $event)
    {
        $this->jobProvider = $event->jobProvider;
        $this->logger = $event->logger;

        try {
            if ($this->prepare() === false) {

            } else {
                $this->run();
                $this->complete();
            }
        } catch (\Exception $e) {
            $this->logger->getOracle()->exception($e);
            $this->logger->error("Unexpected exception in CronHandler class");
        }

        return true;
    }

    /**
     * @return bool
     */
    public function prepare()
    {
        $this->logger->getOracle()->reset();
        $this->logger->getOracle()->setVar('CronHandler', get_class($this));

        return true;
    }

    /**
     * @return void
     */
    public function complete()
    {
        // Do nothing
    }

}
