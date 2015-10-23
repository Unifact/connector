<?php

namespace Unifact\Connector\Http\Controllers;


use Unifact\Connector\Repository\LogRepository;

class LogController extends BaseController
{
    private $logRepository;

    /**
     * LogController constructor.
     * @param $logRepository
     */
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }


    public function index()
    {
        $logs = $this->logRepository->paginate(25);

        $title = 'Logs';
        return \View::make('connector::log.index')
            ->with('title', $title)
            ->with('logs', $logs);
    }
}
