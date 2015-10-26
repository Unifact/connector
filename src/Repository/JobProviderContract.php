<?php /* created by Rob van Bentem, 26/10/2015 */

namespace Unifact\Connector\Repository;

interface JobProviderContract
{
    /**
     * @param $values
     * @return bool
     */
    public function insert($values);
}
