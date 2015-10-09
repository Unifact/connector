<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler\Interfaces;

use Unifact\Connector\Events\ConnectorRunCronEvent;
use Unifact\Connector\Exceptions\CronException;

interface ICronHandler
{
    /**
     * @param ConnectorRunCronEvent $event
     * @return bool
     */
    public function handle(ConnectorRunCronEvent $event);

    /**
     * @return bool
     * @throws CronException
     */
    public function prepare();

    /**
     * @return bool
     * @throws CronException
     */
    public function run();

    /**
     * @return void
     * @throws CronException
     */
    public function complete();
}
