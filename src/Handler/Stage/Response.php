<?php /* created by Rob van Bentem, 28/10/2015 */

namespace Unifact\Connector\Handler\Stage;

use Unifact\Connector\Models\Stage;

class Response
{
    /**
     * @var Stage|null
     */
    public $model;

    /**
     * @var bool
     */
    public $success;

    /**
     * @var bool
     */
    public $continue;

    /**
     * @param Stage $model
     * @param bool $success
     * @param bool $continue
     */
    public function __construct($model, $success = true, $continue = true)
    {
        $this->model = $model;
        $this->success = $success;
        $this->continue = $continue;
    }
}
