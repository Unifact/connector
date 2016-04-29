<?php /* created by Rob van Bentem, 16/10/2015 */

$domain = env('CONNECTOR_DOMAIN', sprintf('%s.%s', 'connector', env('DOMAIN', 'local.dev')));
$namespace = '\Unifact\Connector\Http\Controllers';
$prefix = env('CONNECTOR_PREFIX', 'cnr');

// Restricted routes (auth needed)
Route::group([
    'prefix' => $prefix,
    'namespace' => $namespace,
    'middleware' => ['web', 'connector.auth'],
], function () {

    // Dashboard
    Route::get('/', ['as' => 'connector.dashboard.index', 'uses' => 'DashboardController@index']);

    // Logs
    Route::get('logs', ['as' => 'connector.logs.index', 'uses' => 'LogController@index']);
    Route::get('logs/{id}', ['as' => 'connector.logs.show', 'uses' => 'LogController@show']);

    // Job
    Route::get('jobs/{jobId}', ['as' => 'connector.jobs.show', 'uses' => 'JobController@show']);
    Route::put('jobs/{jobId}', ['as' => 'connector.jobs.update', 'uses' => 'JobController@update']);

    // Stage
    Route::get('jobs/{jobId}/stage/{stageId}', ['as' => 'connector.stages.show', 'uses' => 'StageController@show']);

    // Search
    Route::get('search', ['as' => 'connector.search', 'uses' => 'SearchController@search']);
});

// Unrestricted routes
Route::group(['domain' => $domain, 'namespace' => $namespace, 'prefix' => $prefix, 'middleware' => ['web']],
    function () {
        Route::get('auth/login', ['uses' => 'AuthController@getLogin', 'as' => 'connector.auth.login.get']);
        Route::post('auth/login', ['uses' => 'AuthController@postLogin', 'as' => 'connector.auth.login.post']);
        Route::get('auth/logout', ['uses' => 'AuthController@logout', 'as' => 'connector.auth.logout']);
    });
