<?php /* created by Rob van Bentem, 05/10/2015 */

namespace Unifact\Connector\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Unifact\Connector\Exceptions\ConnectorException;
use Unifact\Connector\Models\Job;
use Unifact\Connector\Models\Stage as StageModel;

class JobRepository implements JobContract, JobProviderContract
{
    /**
     * @param $id
     * @return Job
     */
    public function findById($id)
    {
        $job = Job::find($id);

        if (is_null($job)) {
            throw new ModelNotFoundException("Cannot find this model.");
        }

        return $job;
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $orderDir
     * @return Job[]|Collection
     */
    public function filter(array $filters = [], $orderBy = 'created_at', $orderDir = 'asc')
    {
        $model = $this->getFilterQry($filters, $orderBy, $orderDir);

        return $model->get();
    }

    /**
     * @param $values
     * @return Job
     * @throws ConnectorException
     */
    public function create($values)
    {
        $job = new Job();

        $values['status'] = array_get($values, 'status', 'new');

        $job->fill($values);

        if ($job->save()) {
            return $job;
        }

        throw new ConnectorException("Cannot create Job.");
    }

    /**
     * @param $id
     * @param $data
     * @return Job
     * @throws ConnectorException
     */
    public function update($id, $data)
    {
        $job = $this->findById($id);

        $job->fill($data);

        if ($job->save()) {
            return $job;
        }

        throw new ConnectorException("Cannot update Job with id {$id}.");
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function delete($id)
    {
        return $this->findById($id)->delete();
    }

    /**
     * @param $id
     * @param StageModel $stage
     * @return bool
     * @throws ConnectorException
     */
    public function attachStage($id, StageModel $stage)
    {
        try {
            $job = $this->findById($id);

            $v = $job->stages()->save($stage);

            return !is_null($v);
        } catch (\Exception $e) {
            throw new ConnectorException($e->getMessage());
        }
    }

    /**
     * @param int $amount
     * @param array $filters
     * @param string $orderBy
     * @param string $orderDir
     * @return array|Collection|static[]
     */
    public function latest($amount = 10, $filters = [], $orderBy = 'created_at', $orderDir = 'desc')
    {
        return $this->getFilterQry($filters, $orderBy, $orderDir)
            ->take($amount)
            ->get();
    }

    /**
     * @param array $filters
     * @param $orderBy
     * @param $orderDir
     * @return $this|Job
     */
    private function getFilterQry(array $filters, $orderBy, $orderDir)
    {
        $model = new Job();

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

    /**
     * @param $values
     * @return bool
     * @throws ConnectorException
     */
    public function insert($values)
    {
        if ($this->create($values)) {
            return true;
        }

        return false;
    }


}
