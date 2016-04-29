<?php /* created by Rob van Bentem, 19/10/2015 */

namespace Unifact\Connector\Http\Controllers;

use Unifact\Connector\Presenters\JobPresenter;
use Unifact\Connector\Repository\JobContract;
use Unifact\Connector\Repository\LogContract;

class JobController extends BaseController
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
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $job = $this->jobRepo->findById($id);

        $referenced = $this->jobRepo->latest(10, [
            ['reference', '=', $job->reference],
            ['id', '!=', $job->id],
            ['reference', 'NOT NULL'],
            ['reference', '!=', '']
        ]);

        $logs = $this->logRepo->filter([['job_id', $job->id]], 'id');

        return \View::make('connector::job.show', [
            'job' => $job,
            'presenter' => new JobPresenter($job),
            'referenced' => $referenced,
            'logs' => $logs
        ]);
    }

    public function update($id)
    {
        $job = $this->jobRepo->findById($id);

        if (!empty(\Request::get('save_restart'))) {

            $this->jobRepo->update($id, [
                'data' => \Request::get('data'),
                'status' => 'restart'
            ]);

        } elseif (!empty(\Request::get('restart'))) {
            $this->jobRepo->update($id, [
                'status' => 'restart'
            ]);
        } elseif (!empty(\Request::get('retry'))) {
            $this->jobRepo->update($id, [
                'status' => 'retry'
            ]);
        }

        return \Redirect::route('connector.jobs.show', [$id]);
    }

}
