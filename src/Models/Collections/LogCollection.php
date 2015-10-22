<?php /* created by Rob van Bentem, 20/10/2015 */

namespace Unifact\Connector\Models\Collections;

use Illuminate\Database\Eloquent\Collection;
use Unifact\Connector\Presenters\LogPresenter;

class LogCollection extends Collection
{
    /**
     * @return \Illuminate\Support\Collection|LogPresenter[]
     */
    public function presenters()
    {
        $logPresenters = [];
        foreach ($this->items as $item) {
            $logPresenters[] = new LogPresenter($item);
        }

        return collect($logPresenters);
    }
}
