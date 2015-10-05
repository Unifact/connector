<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Events;

use App\Events\Event;
use Unifact\Connector\Models\Job;

class ConnectorRunJobEvent extends Event
{
    /**
     * @var Job
     */
    public $job;

    /**
     * ConnectorRunJobEvent constructor.
     * @param Job $job
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }
}
