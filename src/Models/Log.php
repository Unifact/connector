<?php /* created by Rob van Bentem, 12/10/2015 */

namespace Unifact\Connector\Models;

use Unifact\Connector\Models\Collections\LogCollection;

class Log extends \Eloquent
{
    public $table = 'connector_logs';

    protected $fillable = [
        'message',
        'level',
        'data',
        'job_id',
        'stage',
    ];

    /**
     * @param array $models
     * @return LogCollection
     */
    public function newCollection(array $models = [])
    {
        return new LogCollection($models);
    }
}
