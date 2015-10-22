<?php /* created by Rob van Bentem, 20/10/2015 */

namespace Unifact\Connector\Presenters;

use Monolog\Logger;

class LogPresenter extends BasePresenter
{

    protected $levels = [
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    ];

    /**
     * @return string
     */
    public function getTableRowClass()
    {
        switch ($this->level) {
            case Logger::DEBUG:
                return "active";
            case Logger::INFO:
                return "info";
            case Logger::NOTICE;
                return "warning";
            case Logger::WARNING:
                return "warning";
            default:
                return "danger";
        }

    }

    /**
     * @return string
     */
    public function humanLevel()
    {
        return array_get($this->levels, $this->level, 'UNKNOWN');
    }
}
