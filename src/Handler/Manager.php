<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler;

use Illuminate\Support\Collection;
use Unifact\Connector\Exceptions\HandlerException;
use Unifact\Connector\Handler\Interfaces\IJobHandler;
use Unifact\Connector\Models\Job;

class Manager
{
    /**
     * @var Collection
     */
    protected $handlers;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
        $this->handlers = new Collection();
    }

    /**
     * @param IJobHandler $handler
     */
    public function pushHandler(IJobHandler $handler)
    {
        $this->handlers->offsetSet($handler->getType(), $handler);
    }

    /**
     * @param $type
     * @return IJobHandler
     * @throws HandlerException
     */
    public function getHandlerForType($type)
    {
        if ($this->hasHandlerForType($type)) {
            return $this->handlers->offsetGet($type);
        }

        throw new HandlerException("No handler of type {$type} exists.");
    }

    /**
     * @param $type
     * @return bool
     */
    public function hasHandlerForType($type)
    {
        return $this->handlers->offsetExists($type);
    }

    /**
     * @param Job $job
     * @return bool
     * @throws HandlerException
     */
    public function handleJob(Job $job)
    {
        if ($this->hasHandlerForType($job->type)) {
            $handler = $this->getHandlerForType($job->type);

            if (!$handler->prepare()) {
                return false;
            }
            if (!$handler->handle($job)) {
                return false;
            }

            $handler->complete();

            return true;
        }

        return false;
    }

}
