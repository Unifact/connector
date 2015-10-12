<?php /* created by Rob van Bentem, 09/10/2015 */

namespace Unifact\Connector\Repository;

use Unifact\Connector\Exceptions\ConnectorException;
use Unifact\Connector\Models\Stage;

class StageRepository implements StageContract
{

    /**
     * @param $data
     * @return Stage
     * @throws ConnectorException
     */
    public function create($data)
    {
        try {
            $stage = $this->createStub($data);

            if ($stage->save() === false) {
                throw new \Exception("Cannot save Stage.");
            }

            return $stage;
        } catch (\Exception $e) {
            throw new ConnectorException($e->getMessage());
        }
    }

    /**
     * @param $data
     * @return Stage
     * @throws ConnectorException
     */
    public function createStub($data)
    {
        try {
            $data['data'] = json_encode(array_get($data, 'data', []));

            return new Stage($data);
        } catch (\Exception $e) {
            throw new ConnectorException($e->getMessage());
        }
    }

    /**
     * @param int $jobId
     * @param int $stage
     * @return null|Stage
     */
    public function findByJobIdAndStage($jobId, $stage)
    {
        return Stage::where('job_id', '=', $jobId)
            ->where('stage', '=', $stage)
            ->first();
    }


}
