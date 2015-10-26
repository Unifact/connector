<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Events;

use App\Events\Event;
use Unifact\Connector\Log\ConnectorLoggerInterface;
use Unifact\Connector\Repository\JobProviderContract;

class ConnectorRunCronEvent extends Event
{
    /**
     * @var JobProviderContract
     */
    public $jobProvider;

    /**
     * @var ConnectorLoggerInterface
     */
    public $logger;

    /**
     * @param JobProviderContract $jobProvider
     * @param ConnectorLoggerInterface $logger
     */
    public function __construct(JobProviderContract $jobProvider, ConnectorLoggerInterface $logger)
    {
        $this->jobProvider = $jobProvider;
        $this->logger = $logger;
    }
}
