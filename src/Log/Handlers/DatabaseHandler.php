<?php /* created by Rob van Bentem, 12/10/2015 */

namespace Unifact\Connector\Log\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Unifact\Connector\Log\StateOracle;
use Unifact\Connector\Models\Log;

class DatabaseHandler extends AbstractProcessingHandler
{

    /**
     * @var StateOracle
     */
    protected $oracle;

    /**
     * @param int $level
     * @param bool|true $bubble
     */
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->oracle = app(StateOracle::class);
    }


    /**
     * @param array $record
     */
    protected function write(array $record)
    {
        $attributes = [
            'message' => $record['message'],
            'level' => $record['level'],
            'data' => $record['formatted'],
            'job_id' => $this->oracle->getJobId(),
            'stage_number' => $this->oracle->getStageNumber(),
        ];

        Log::create($attributes);
    }

}
