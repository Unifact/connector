<?php /* created by Rob van Bentem, 20/10/2015 */

namespace Unifact\Connector\Http\Controllers;

use Unifact\Connector\Models\Log;
use Unifact\Connector\Presenters\StagePresenter;
use Unifact\Connector\Repository\JobContract;
use Unifact\Connector\Repository\LogContract;
use Unifact\Connector\Repository\StageContract;

/**
 * Class StageController
 * @package Unifact\Connector\Http\Controllers
 */
class StageController extends BaseController
{
    /**
     * @var StageContract
     */
    private $stageRepo;

    /**
     * @var JobContract
     */
    private $jobRepo;

    /**
     * @var LogContract
     */
    private $logRepo;

    /**
     * StageController constructor.
     * @param StageContract $stageRepo
     * @param JobContract $jobRepo
     * @param LogContract $logRepo
     */
    public function __construct(StageContract $stageRepo, JobContract $jobRepo, LogContract $logRepo)
    {
        $this->stageRepo = $stageRepo;
        $this->jobRepo = $jobRepo;
        $this->logRepo = $logRepo;
    }

    /**
     * @param $jobId
     * @param $stageId
     * @return \Illuminate\Contracts\View\View
     */
    public function show($jobId, $stageId)
    {
        $stage = $this->stageRepo->findById($stageId);

        $previous = $this->stageRepo->getPrecedingStage($stage);
        if ($previous === null) {
            $previous = $this->jobRepo->findById($stage->job_id);
        }

        $logs = $this->logRepo->filter([
            ['job_id', $stage->job_id],
            ['stage', $stage->stage]
        ],'id');

        return \View::make('connector::stage.show', [
            'stage' => new StagePresenter($stage),
            'previous' => $previous, // this can be a Stage or Job model.
            'logs' => $logs
        ]);
    }

}
