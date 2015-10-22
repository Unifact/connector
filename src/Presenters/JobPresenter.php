<?php /* created by Rob van Bentem, 19/10/2015 */

namespace Unifact\Connector\Presenters;

class JobPresenter extends BasePresenter
{
    /**
     * @return string
     */
    public function getTableRowClass()
    {
        switch ($this->status) {
            case "handled":
                return "success";
            case "error":
                return "danger";
            case "restart":
                return "warning";
            case "retry":
                return "warning";
            default:
                return "active";
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function getSearchResultRow()
    {
        return \View::make('connector::job.partial.searchresult', ['item' => $this]);
    }
}
