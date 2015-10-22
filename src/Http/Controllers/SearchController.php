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


    public function search()
    {
        $results = new Collection();

        $reference = \Input::get('reference');
        $data = \Input::get('data');

        $jobs = [];
        $stages = [];

        $query = "";

        if (!empty($reference)) {
            $jobs = $this->jobRepo->filter([['reference', $reference]]);

            $query = $reference;
        } elseif (!empty($data)) {
            $jobs = $this->jobRepo->filter([['data', 'LIKE', '%' . $data . '%']]);
            $stages = $this->stageRepo->filter([['data', 'LIKE', '%' . $data . '%']]);

            $query = $data;
        } else {
            return \Redirect::route('connector.dashboard.index');
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
            'query' => $query
        ]);
    }
}
