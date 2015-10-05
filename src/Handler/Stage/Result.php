<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector\Handler\Stage;

class Result
{
    /**
     * @var string
     */
    private $data;

    /**
     * @var string
     */
    private $status;

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Result constructor.
     * @param string $data
     * @param string $status
     */
    public function __construct($data, $status)
    {
        $this->data = $data;
        $this->status = $status;
    }
}
