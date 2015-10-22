<?php /* created by Rob van Bentem, 20/10/2015 */

namespace Unifact\Connector\Models\Collections;

use Illuminate\Database\Eloquent\Collection;
use Unifact\Connector\Presenters\StagePresenter;

class StageCollection extends Collection
{
    /**
     * @return \Illuminate\Support\Collection|StagePresenter[]
     */
    public function presenters()
    {
        $stagePresenters = [];
        foreach ($this->items as $item) {
            $stagePresenters[] = new StagePresenter($item);
        }

        return collect($stagePresenters);
    }
}
