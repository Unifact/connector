<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector\Handler;

use Unifact\Connector\Handler\Handlers\JobHandler;
use Unifact\Connector\Handler\Interfaces\IStageProcessor;
use Unifact\Connector\Log\ConnectorLogger;

abstract class Stage implements IStageProcessor
{
    /**
     * @var null
     */
    protected $name = null;

    /**
     * @var JobHandler
     */
    protected $jobHandler;

    /**
     * @var ConnectorLogger
     */
    protected $logger;

    /**
     * @param JobHandler $jobHandler
     */
    public function setJobHandler($jobHandler)
    {
        $this->jobHandler = $jobHandler;
    }

    /**
     * @param ConnectorLogger $logger
     */
    public function setLogger(ConnectorLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * This name is used in the handler as the stage handle
     *
     * @return string
     */
    public function getName()
    {
        if ($this->name !== null) {
            return $this->name;
        }

        return class_basename($this);
    }

    /**
     * @param array $data
     * @return array|object
     */
    abstract function process(array $data);

}
