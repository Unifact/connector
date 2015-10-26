<?php /* created by Rob van Bentem, 22/10/2015 */

namespace Unifact\Connector\Repository;

use Illuminate\Database\Eloquent\Collection;
use Monolog\Logger;
use Unifact\Connector\Models\Log;

/**
 * Class LogRepository
 * @package Unifact\Connector\Repository
 */
class LogRepository implements LogContract
{
    /**
     * @param $id
     * @return Log
     */
    public function findById($id)
    {
        return Log::findOrFail($id);
    }

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $orderDir
     * @return Log[]|Collection
     */
    public function filter(array $filters = [], $orderBy = 'created_at', $orderDir = 'asc')
    {
        $model = $this->getFilterQry($filters, $orderBy, $orderDir);

        return $model->get();
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
     * @return Log
     */
    private function getFilterQry(array $filters, $orderBy, $orderDir)
    {
        $model = new Log();

        $model = $model->where('level', '>=', \Config::get('connector.logging.logviewer.min_level', Logger::DEBUG));

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
     * @param $perPage
     * @param array $filters
     * @param $orderBy
     * @param $orderDir
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $filters = [], $orderBy = 'created_at', $orderDir = 'desc')
    {
        return $this->getFilterQry($filters, $orderBy, $orderDir)->paginate($perPage);
    }
}
