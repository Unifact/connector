<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler\Interfaces;

use Unifact\Connector\Exceptions\HandlerException;
use Unifact\Connector\Models\Job;

interface IJobHandler
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return bool
     * @throws HandlerException
     */
    public function prepare();

    /**
     * @param Job $job
     * @return bool
     * @throws HandlerException
     */
    public function handle(Job $job);

    /**
     * @return void
     * @throws HandlerException
     */
    public function complete();
}
