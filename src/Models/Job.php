<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector\Models;

class Job extends \Eloquent
{
    public $table = 'connector_jobs';

    public $fillable = [
        'type',
        'data',
        'status',
    ];

    public $with = [
        'stages',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stages()
    {
        return $this->hasMany(Stage::class, 'job_id', 'id');
    }

    /**
     * @param $number
     * @return Stage|null
     */
    public function getStageByNumber($number)
    {
        return $this->stages()->where('stage', '=', $number)->first();
    }
}
