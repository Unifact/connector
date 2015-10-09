<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Handler\Handlers;


use Unifact\Connector\Events\ConnectorRunCronEvent;
use Unifact\Connector\Handler\Handler;
use Unifact\Connector\Handler\Interfaces\ICronHandler;
use Unifact\Connector\Repository\JobContract;

abstract class CronHandler extends Handler implements ICronHandler
{
    /**
     * @var JobContract
     */
    protected $jobRepo;

    /**
     * CronHandler constructor.
     * @param JobContract $jobRepo
     */
    public function __construct(JobContract $jobRepo)
    {
        $this->jobRepo = $jobRepo;
    }

    /**
     * @param ConnectorRunCronEvent $event
     * @return bool
     */
    public function handle(ConnectorRunCronEvent $event)
    {
        if ($this->prepare()) {
            if ($this->run()) {
                $this->complete();
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function prepare()
    {
        return true;
    }

    /**
     * @return void
     */
    public function complete()
    {
        // Do nothing
    }

}
