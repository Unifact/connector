<?php /* created by Rob van Bentem, 20/10/2015 */

namespace Unifact\Connector\Models\Collections;

use Illuminate\Database\Eloquent\Collection;
use Unifact\Connector\Presenters\JobPresenter;

class JobCollection extends Collection
{
    /**
     * @return \Illuminate\Support\Collection|JobPresenter[]
     */
    public function presenters()
    {
        $jobPresenters = [];
        foreach ($this->items as $item) {
            $jobPresenters[] = new JobPresenter($item);
        }

        return collect($jobPresenters);
    }
}
