<?php /* created by Rob van Bentem, 19/10/2015 */

namespace Unifact\Connector\Presenters;

use Illuminate\Database\Eloquent\Model;

class BasePresenter
{
    /**
     * @var Model
     */
    private $item;

    /**
     * @param Model $item
     */
    public function __construct(Model $item)
    {
        $this->item = $item;
    }

    /**
     * @param $var
     * @return mixed
     */
    public function __get($var)
    {
        return $this->item->$var;
    }

    /**
     * @param $func
     * @param array $args
     * @return mixed
     */
    public function __call($func, $args = [])
    {
        return call_user_func_array([$this->item, $func], $args);
    }

    /**
     * @param $route
     * @param $display
     * @param null $params
     * @return string
     */
    public function url($route, $display, $params = null)
    {
        if ($params === null) {
            $params = [$this->id];
        }

        return '<a href="' . \URL::route($route, $params) . '">' . $this->$display . '</a>';
    }
}
