<?php /* created by Rob van Bentem, 22/10/2015 */

namespace Unifact\Connector\Http\Controllers;

use Illuminate\Support\Collection;
use Unifact\Connector\Presenters\JobPresenter;
use Unifact\Connector\Presenters\StagePresenter;
use Unifact\Connector\Repository\JobContract;
use Unifact\Connector\Repository\StageContract;

class SearchController extends BaseController
{
    /**
     * @var JobContract
     */
    private $jobRepo;

    /**
     * @var StageContract
     */
    private $stageRepo;

    /**
     * @param JobContract $jobRepo
     * @param StageContract $stageRepo
     */
    function __construct(JobContract $jobRepo, StageContract $stageRepo)
    {
        $this->jobRepo = $jobRepo;
        $this->stageRepo = $stageRepo;
    }


    /**
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function search()
    {
        $results = new Collection();

        $reference = \Request::get('reference');
        $data = \Request::get('data');

        $dateFrom = \Request::get('from');
        $dateTo = \Request::get('to');

        $filters = [];

        $time = ' 23:59:59';
        if (!empty($dateFrom)) {
            $filters[] = ['created_at', '>=', $dateFrom . $time];
        }

        if (!empty($dateTo)) {
            $filters[] = ['created_at', '<=', $dateTo . $time];
        }

        $jobs = [];
        $stages = [];

        $query = "";

        $search = false;

        $jobFilters = $filters;
        $stageFilters = $filters;

        if (!empty($reference)) {
            $jobFilters[] = ['reference', $reference];
            $search = true;
        }

        if (!empty($data)) {
            $jobFilters[] = ['data', 'LIKE', '%' . $data . '%'];
            $stageFilters[] = ['data', 'LIKE', '%' . $data . '%'];
            $search = true;
        }

        if (sizeof($filters) > 0) {
            $search = true;
        }

        if ($search === false) {
            return \Redirect::route('connector.dashboard.index');
        }


        $jobs = $this->jobRepo->filter($jobFilters);
        if(!empty($data)) {
            $stages = $this->stageRepo->filter($stageFilters);
        } else {
            $stages = [];
        }

        foreach ($jobs as $job) {
            $results->push(new JobPresenter($job));
        }

        foreach ($stages as $stage) {
            $results->push(new StagePresenter($stage));
        }

        $results = $results->sort(function ($a, $b) {
            return strtotime($a->created_at) > strtotime($b->created_at) ? 1 : 0;
        });

        return \View::make('connector::search.results', [
            'results' => $results,
        ]);
    }
}
