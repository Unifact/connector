<?php /* created by Rob van Bentem, 12/10/2015 */

namespace Unifact\Connector\Handler\Traits;

use Unifact\Connector\Exceptions\ConnectorException;

trait JsonDataTrait
{
    /**
     * @return array
     * @throws ConnectorException
     */
    public function getParsedData()
    {
        if (($data = json_decode($this->data, true)) !== false) {
            return $data;
        }

        throw new ConnectorException("Could not decode " . get_class($this), " data column to json (id: {$this->primaryKey}.");
    }
}
