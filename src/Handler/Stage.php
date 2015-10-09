<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector\Handler;

use Unifact\Connector\Handler\Interfaces\IStageProcessor;
use Unifact\Connector\Models\Job;
use Unifact\Connector\Models\Stage as StageModel;

abstract class Stage implements IStageProcessor
{
    /**
     * @param Job $job
     * @return array|object
     */
    abstract function process(Job $job);

}
