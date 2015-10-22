<?php /* created by Rob van Bentem, 20/10/2015 */

namespace Unifact\Connector\Presenters;

class StagePresenter extends BasePresenter
{
    /**
     * @return string
     */
    public function getTableRowClass()
    {
        switch ($this->status) {
            case "processed":
                return "success";
            case "error":
                return "danger";
            default:
                return "active";
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function getSearchResultRow()
    {
        return \View::make('connector::stage.partial.searchresult', ['item' => $this]);
    }
}
