<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Repository;

use Illuminate\Database\Eloquent\Collection;
use Unifact\Connector\Exceptions\ConnectorException;
use Unifact\Connector\Models\Job;
use Unifact\Connector\Models\Stage as StageModel;

interface JobContract
{
    /**
     * @param $id
     * @return Job
     */
    public function findById($id);

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $orderDir
     * @return Job[]|Collection
     */
    public function filter(array $filters = [], $orderBy = 'created_at', $orderDir = 'asc');

    /**
     * @param $id
     * @param $data
     * @return Job
     */
    public function update($id, $data);

    /**
     * @param $values
     * @return Job
     */
    public function create($values);

    /**
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * @param $id
     * @param StageModel $stage
     * @return bool
     * @throws ConnectorException
     */
    public function attachStage($id, StageModel $stage);

    /**
     * @param int $amount
     * @return \Unifact\Connector\Models\Job[]
     */
    public function latest($amount = 10, $filters = [], $orderBy = 'created_at', $orderDir = 'desc');

    /**
     * Clean jobs older than $days days
     * @param $days
     * @return mixed
     */
    public function clean($days);
}
