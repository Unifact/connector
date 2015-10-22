<?php /* created by Rob van Bentem, 09/10/2015 */

namespace Unifact\Connector\Repository;

use Illuminate\Database\Eloquent\Collection;
use Unifact\Connector\Models\Stage;

interface StageContract
{

    /**
     * @param $id
     * @return Stage
     */
    public function findById($id);

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $orderDir
     * @return Stage[]|Collection
     */
    public function filter(array $filters = [], $orderBy = 'created_at', $orderDir = 'asc');

    /**
     * @param $data
     * @return Stage
     */
    public function create($data);

    /**
     * @param $data
     * @return Stage
     */
    public function createStub($data);

    /**
     * @param int $jobId
     * @param string $stage
     * @return Stage|null
     */
    public function findByJobIdAndStage($jobId, $stage);

    /**
     * @param $jobId
     * @param $status
     * @return Stage|null
     */
    public function findLastByJobIdAndStatus($jobId, $status);

    /**
     * @param $jobId
     * @return bool
     */
    public function deleteByJobId($jobId);

    /**
     * Returns the stage begore the given Stage
     * @param Stage $stage
     * @return Stage|null
     */
    public function getPrecedingStage(Stage $stage);
}
