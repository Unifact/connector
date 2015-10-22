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
    protected $stage;

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
     * @return string
     */
    public function getStage()
    {
        return $this->stage;
    }

    /**
     * @param string $stage
     */
    public function setStage($stage)
    {
        $this->stage = $stage;
    }

    /**
     * @param null $jobId
     * @param null $stage
     */
    public function reset($jobId = null, $stage = null)
    {
        $this->setJobId($jobId);
        $this->setStage($stage);
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return [
            'job_id' => $this->getJobId(),
            'stage' => $this->getStage()
        ];
    }
}
