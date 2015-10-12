<?php /* created by Rob van Bentem, 12/10/2015 */

namespace Unifact\Connector\Models;

class Log extends \Eloquent
{
    public $table = 'connector_logs';

    protected $fillable = [
        'message',
        'level',
        'data',
        'job_id',
        'stage_number',
    ];

}
