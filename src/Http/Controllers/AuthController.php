<?php /* created by Rob van Bentem, 16/10/2015 */

namespace Unifact\Connector\Http\Controllers;

class AuthController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getLogin()
    {
        if (\Session::get('connector.auth') === true) {
            return \Redirect::route('connector.dashboard.index');
        }

        return \View::make('connector::auth.login');
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postLogin()
    {
        if ($this->checkCredentials()) {
            \Session::put('connector.auth', true); // flag as logged in
            return \Redirect::route('connector.dashboard.index');
        }

        return \Redirect::back()->withErrors([
            'login' => 'Invalid credentials'
        ], 'login');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        \Session::forget('connector.auth');

        return \Redirect::route('connector.auth.login.get');
    }

    /**
     * @return bool
     */
    protected function checkCredentials()
    {
        if (env('CONNECTOR_USER') === null || env('CONNECTOR_PASS') === null) {
            return false;
        }

        if (env('CONNECTOR_USER') !== \Input::get('username')) {
            return false;
        }

        if (env('CONNECTOR_PASS') !== sha1(\Input::get('password'))) {
            return false;
        }

        return true;
    }
}
