<?php /* created by Rob van Bentem, 19/10/2015 */

namespace Unifact\Connector\Http\Controllers;

use Unifact\Connector\Models\Log;
use Unifact\Connector\Repository\JobContract;
use Unifact\Connector\Repository\LogContract;

class DashboardController extends BaseController
{
    /**
     * @var JobContract
     */
    private $jobRepo;

    /**
     * @var LogContract
     */
    private $logRepo;

    /**
     * @param JobContract $jobRepo
     * @param LogContract $logRepo
     */
    public function __construct(JobContract $jobRepo, LogContract $logRepo)
    {
        $this->jobRepo = $jobRepo;
        $this->logRepo = $logRepo;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $jobs = $this->jobRepo->latest(10);
        $issues = $this->jobRepo->filter([['status', '!=', 'handled'], ['status', '!=', 'new']]);

        $logs = Log::orderBy('id', 'desc')
            ->take(25)
            ->get();

        $logs = $this->logRepo->latest(25, [], 'id', 'desc');

        return \View::make('connector::dashboard.index', [
            'jobs' => $jobs,
            'issues' => $issues,
            'logs' => $logs
        ]);
    }
}
