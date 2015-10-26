<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Events;

use App\Events\Event;
use Unifact\Connector\Repository\JobProviderContract;

class ConnectorRunCronEvent extends Event
{
    /**
     * @var JobProviderContract
     */
    public $jobProvider;

    /**
     * @param JobProviderContract $jobProvider
     */
    public function __construct(JobProviderContract $jobProvider)
    {
        $this->jobProvider = $jobProvider;
    }
}
