<?php /* created by Rob van Bentem, 09/10/2015 */

namespace Unifact\Connector\Repository;

use Illuminate\Database\Eloquent\Collection;
use Unifact\Connector\Exceptions\ConnectorException;
use Unifact\Connector\Models\Stage;

class StageRepository implements StageContract
{
    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function findById($id)
    {
        return Stage::where('id', '=', $id)->firstOrFail();
    }

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
     * @param string $stage
     * @return null|Stage
     */
    public function findByJobIdAndStage($jobId, $stage)
    {
        return Stage::where('job_id', '=', $jobId)
            ->where('stage', '=', $stage)
            ->first();
    }

    /**
     * @param $jobId
     * @param $status
     * @return Stage|null
     */
    public function findLastByJobIdAndStatus($jobId, $status)
    {
        return Stage::orderBy('id', 'desc')
            ->where('job_id', '=', $jobId)
            ->where('status', '=', $status)
            ->first();
    }

    /**
     * @param $jobId
     * @return bool
     */
    public function deleteByJobId($jobId)
    {
        return Stage::where('job_id', '=', $jobId)->delete();
    }

    /**
     * @param Stage $stage
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getPrecedingStage(Stage $stage)
    {
        return Stage::where('job_id', '=', $stage->job_id)
            ->where('id', '!=', $stage->id)
            ->where('id', '<', $stage->id)
            ->orderBy('id', 'desc')
            ->first();
    }


    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $orderDir
     * @return Stage[]|Collection
     */
    public function filter(array $filters = [], $orderBy = 'created_at', $orderDir = 'asc')
    {
        $model = $this->getFilterQry($filters, $orderBy, $orderDir);

        return $model->get();
    }


    /**
     * @param array $filters
     * @param $orderBy
     * @param $orderDir
     * @return $this|Stage
     */
    private function getFilterQry(array $filters, $orderBy, $orderDir)
    {
        $model = new Stage();

        foreach ($filters as $filter) {
            if (count($filter) == 2) {
                if ($filter[1] === 'NOT NULL') {
                    $model = $model->whereNotNull($filter[0]);
                } else {
                    $model = $model->where($filter[0], $filter[1]);
                }
            } elseif (count($filter) == 3) {
                $model = $model->where($filter[0], $filter[1], $filter[2]);
            }
        }

        $model = $model->orderBy($orderBy, $orderDir);

        return $model;
    }

}
