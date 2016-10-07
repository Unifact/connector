<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Events;

use Illuminate\Support\Facades\Event;

class ConnectorRunCronEvent extends Event
{
    /**
     * @var
     */
    public $newMin;

    /**
     * @var
     */
    public $date;

    /**
     * @param $date
     * @param $newMin
     */
    public function __construct($date, $newMin)
    {
        $this->date = $date;
        $this->newMin = $newMin;
    }
}
