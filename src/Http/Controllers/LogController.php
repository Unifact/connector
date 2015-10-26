<?php

namespace Unifact\Connector\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Unifact\Connector\Repository\LogContract;

class LogController extends BaseController
{
    /**
     * @var LogContract
     */
    private $logRepo;

    /**
     * @param LogContract $logRepo
     */
    public function __construct(LogContract $logRepo)
    {
        $this->logRepo = $logRepo;
    }


    /**
     * @return \View
     */
    public function index()
    {
        $logs = $this->logRepo->paginate(25);

        $title = 'Logs';

        return \View::make('connector::log.index')
            ->with('title', $title)
            ->with('logs', $logs);
    }

    /**
     * @param $id
     * @return \View
     */
    public function show($id)
    {
        try {
            $log = $this->logRepo->findById($id);
            $last = null;
        } catch (ModelNotFoundException $e) {
            $log = null;
            $last = ((int)$id) - 1;
        }

        return \View::make('connector::log.show', [
            'log' => $log,
            'last' => $last
        ]);
    }
}
