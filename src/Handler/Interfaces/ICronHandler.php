<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler\Interfaces;

use Unifact\Connector\Exceptions\CronException;

interface ICronHandler
{
    /**
     * @return bool
     */
    public function handle();

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
