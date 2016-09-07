<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Console;

use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Unifact\Connector\Events\ConnectorRegisterCronEvent;
use Unifact\Connector\Events\ConnectorRegisterHandlerEvent;
use Unifact\Connector\Repository\JobContract;
use Unifact\Connector\Repository\LogContract;

class CleanCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'connector:clean {days}';

    /**
     * @var string
     */
    protected $description = 'Clean Jobs and Logs from the connector database tables';

    /**
     * @var JobContract
     */
    private $jobRepo;

    /**
     * @var LogContract
     */
    private $logRepo;

    /**
     * RunCommand constructor.
     * @param JobContract $jobRepo
     * @param LogContract $logRepo
     */
    public function __construct(JobContract $jobRepo, LogContract $logRepo)
    {
        parent::__construct();

        $this->jobRepo = $jobRepo;
        $this->logRepo = $logRepo;
    }


    public function fire()
    {
        $days = $this->argument('days');

        try {
            if (!is_numeric($days)) {
                throw new \Exception("days must be numeric");
            }

            $this->jobRepo->clean($days);
            $this->logRepo->clean($days);
        } catch (\Exception $e) {
            $this->out('Error cleaning database: ' . $e->getMessage());
        }
    }

}
