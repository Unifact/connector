<?php /* created by Rob van Bentem, 02/10/2015 */

namespace Unifact\Connector;

use Unifact\Connector\Handler\Stage;

abstract class Handler
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array|Stage[]
     */
    private $stages;

    /**
     * Handler constructor.
     * @param string $type
     * @param Stage[] $stages
     */
    public function __construct($type, array $stages)
    {
        $this->type = $type;
        $this->stages = $stages;
    }

}
