<?php /* created by Rob van Bentem, 16/10/2015 */

namespace Unifact\Connector\Http\Middleware;

use Closure;

class Auth
{
    /**
     * Very basic authentication by checking a session variable
     *
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (\Request::session()->get('connector.auth', false) !== true) {
            return \Redirect::route('connector.auth.login.get');
        }

        return $next($request);
    }
}
