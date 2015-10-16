<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector\Handler;

use Unifact\Connector\Handler\Handlers\JobHandler;
use Unifact\Connector\Handler\Interfaces\IStageProcessor;

abstract class Stage implements IStageProcessor
{
    /**
     * @var JobHandler
     */
    protected $jobHandler;

    /**
     * @param JobHandler $jobHandler
     */
    public function setJobHandler($jobHandler)
    {
        $this->jobHandler = $jobHandler;
    }

    /**
     * @param array $data
     * @return array|object
     */
    abstract function process(array $data);

}
