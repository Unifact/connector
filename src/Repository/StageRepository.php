<?php /* created by Rob van Bentem, 09/10/2015 */

namespace Unifact\Connector\Repository;

use Unifact\Connector\Exceptions\ConnectorException;
use Unifact\Connector\Models\Stage;

class StageRepository implements StageContract
{

    /**
     * @param $data
     * @return Stage
     * @throws ConnectorException
     */
    public function create($data)
    {
        try {
            $stage = new Stage();

            $stage->fill($data);

            if ($stage->save() === false) {
                throw new \Exception("Cannot save Stage.");
            }

            return $stage;
        } catch (\Exception $e) {
            throw new ConnectorException($e->getMessage());;
        }
    }
}
