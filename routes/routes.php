<?php /* created by Rob van Bentem, 16/10/2015 */

$domain = env('CONNECTOR_DOMAIN', sprintf('%s.%s', 'connector', env('DOMAIN', 'localhost')));
$namespace = '\Unifact\Connector\Http\Controllers';
$prefix = env('CONNECTOR_PREFIX', 'cnr');

// No auth needed
Route::group([
    'domain' => $domain,
    'prefix' => $prefix,
    'namespace' => $namespace,
    'middleware' => ['connector.auth']
], function () {
    Route::get('test', function () {
        return 'test';
    });
});

// Lock it up boyz
Route::group(['domain' => $domain, 'namespace' => $namespace, 'prefix' => $prefix], function () {
    Route::get('login', ['uses' => 'LoginController@login', 'as' => 'connector.login']);
});
