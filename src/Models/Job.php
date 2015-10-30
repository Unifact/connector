<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector\Models;

use Unifact\Connector\Handler\Traits\JsonDataTrait;
use Unifact\Connector\Models\Collections\JobCollection;

class Job extends \Eloquent
{
    use JsonDataTrait;


    const STATUS_NEW = 'new';

    const STATUS_HANDLED = 'handled';

    const STATUS_ERROR = 'error';

    const STATUS_RETRY = 'retry';

    const STATUS_RESTART = 'restart';

    const STATUS_SKIP = 'skip';

    const STATUS_QUEUED = 'queued';

    const STATUS_RESUME = 'resume';


    public $table = 'connector_jobs';

    public $fillable = [
        'type',
        'priority',
        'reference',
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
        return $this->hasMany(Stage::class, 'job_id', 'id')->orderBy('id', 'asc');
    }

    /**
     * @param $number
     * @return Stage|null
     */
    public function getStageByNumber($number)
    {
        return $this->stages()->where('stage', '=', $number)->first();
    }

    /**
     * Returns the last associated stage sorted by `stage` descending.
     *
     * @return Stage|null
     */
    public function getLastStage()
    {
        return $this->stages()->orderBy('id', 'desc')->first();
    }

    /**
     * @param array $models
     * @return JobCollection
     */
    public function newCollection(array $models = [])
    {
        return new JobCollection($models);
    }

}
