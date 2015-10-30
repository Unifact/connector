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
     * @var string
     */
    protected $trace;

    /**
     * @var array
     */
    protected $vars = [];

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
     * @return string
     */
    public function getTrace()
    {
        return $this->trace;
    }

    /**
     * @param string $trace
     */
    public function setTrace($trace)
    {
        $this->trace = $trace;
    }

    /**
     * @param $var
     * @param $value
     */
    public function setVar($var, $value)
    {
        $this->vars[$var] = $value;
    }

    /**
     * @param null $jobId
     * @param null $stage
     */
    public function reset($jobId = null, $stage = null)
    {
        $this->setJobId($jobId);
        $this->setStage($stage);

        $this->setTrace(null);
        $this->vars = [];
    }

    /**
     * @param \Exception $ex
     */
    public function exception(\Exception $ex)
    {
        $this->setTrace($ex->getTraceAsString());
        $this->setVar('ex_msg', $ex->getMessage());
    }

    /**
     *
     */
    public function resetException()
    {
        $this->setTrace(null);
        unset($this->vars['ex_msg']);
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return [
            'job_id' => $this->getJobId(),
            'stage' => $this->getStage(),
            'vars' => $this->vars,
            'trace' => $this->trace,
        ];
    }
}
