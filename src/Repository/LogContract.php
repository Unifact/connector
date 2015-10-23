<?php


namespace Unifact\Connector\Repository;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Unifact\Connector\Models\Log;

/**
 * Interface LogContract
 * @package Unifact\Connector\Repository
 */
interface LogContract
{

    /**
     * @param int $amount
     * @return \Unifact\Connector\Models\Log[]
     */
    public function latest($amount = 10, $filters = [], $orderBy = 'created_at', $orderDir = 'desc');

    /**
     * @param array $filters
     * @param string $orderBy
     * @param string $orderDir
     * @return Log[]|Collection
     */
    public function filter(array $filters = [], $orderBy = 'created_at', $orderDir = 'asc');

    /**
     * @param $perPage
     * @param array $filters
     * @param $orderBy
     * @param $orderDir
     * @return mixed
     */
    public function paginate($perPage, $filters = [], $orderBy = 'created_at', $orderDir = 'desc');
}
