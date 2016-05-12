<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler\Handlers;


use Illuminate\Queue\InteractsWithQueue;
use Unifact\Connector\Handler\Handler;
use Unifact\Connector\Handler\Interfaces\ICronHandler;
use Unifact\Connector\Log\ConnectorLoggerInterface;
use Unifact\Connector\Repository\JobProviderContract;

abstract class CronHandler extends Handler implements ICronHandler
{
    use InteractsWithQueue;

    /**
     * @var JobProviderContract
     */
    protected $jobProvider;

    /**
     * @var ConnectorLoggerInterface
     */
    protected $logger;

    /**
     * @return string
     * @throws \Exception
     */
    public static function getCronSchedule()
    {
        throw new \Exception("Not implemented.");
    }

    /**
     * @return bool
     */
    public function handle()
    {
        try {
            if ($this->prepare()) {
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
        $this->logger = app(ConnectorLoggerInterface::class);
        $this->jobProvider = app(JobProviderContract::class);

        $this->logger->getOracle()->reset();
        $this->logger->getOracle()->setVar('CronHandler', get_class($this));

        return true;
    }

    /**
     * @return void
     */
    public function complete()
    {
        $this->delete();
    }


}
