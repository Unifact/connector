<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector\Models;

use Unifact\Connector\Handler\Traits\JsonDataTrait;

class Stage extends \Eloquent
{
    use JsonDataTrait;


    const STATUS_PROCESSED = 'processed';

    const STATUS_ERROR = 'error';


    public $table = 'connector_job_stages';

    protected $fillable = [
        'job_id',
        'stage',
        'data',
        'status',
    ];

}
