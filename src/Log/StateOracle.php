<?php /* created by Rob van Bentem, 12/10/2015 */

namespace Unifact\Connector\Log;

class StateOracle
{
    /**
     * @var int
     */
    protected $jobId;

    /**
     * @var int
     */
    protected $stageNumber;

    /**
     * @return int
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @param int $jobId
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return int
     */
    public function getStageNumber()
    {
        return $this->stageNumber;
    }

    /**
     * @param int $stageNumber
     */
    public function setStageNumber($stageNumber)
    {
        $this->stageNumber = $stageNumber;
    }

    /**
     * @param null $jobId
     * @param null $stageNumber
     */
    public function reset($jobId = null, $stageNumber = null)
    {
        $this->setJobId($jobId);
        $this->setStageNumber($stageNumber);
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return [
            'job_id' => $this->getJobId(),
            'stage_number' => $this->getStageNumber()
        ];
    }
}
