<?php /* created by Rob van Bentem, 16/10/2015 */

namespace Unifact\Connector\Http\Controllers;

class LoginController extends BaseController
{
    public function login()
    {
        return \View::make('connector::login');
    }
}
