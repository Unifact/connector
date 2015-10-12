<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector\Handler;

use Unifact\Connector\Handler\Interfaces\IStageProcessor;

abstract class Stage implements IStageProcessor
{
    /**
     * @param array $data
     * @return array|object
     */
    abstract function process(array $data);

}
