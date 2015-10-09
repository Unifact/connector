<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Repository;

use Illuminate\Database\Eloquent\Collection;
use Unifact\Connector\Models\Job;

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
}
