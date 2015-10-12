<?php /* created by Rob van Bentem, 09/10/2015 */

namespace Unifact\Connector\Repository;

use Unifact\Connector\Models\Stage;

interface StageContract
{
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
     * @param int $stage
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
}
