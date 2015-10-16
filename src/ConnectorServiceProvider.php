<?php

namespace Unifact\Connector;

use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Unifact\Connector\Console\RunCommand;
use Unifact\Connector\Http\Middleware\Auth;
use Unifact\Connector\Log\ConnectorLogger;
use Unifact\Connector\Log\Interfaces\IConnectorLogger;
use Unifact\Connector\Log\StateOracle;
use Unifact\Connector\Repository\JobContract;
use Unifact\Connector\Repository\JobRepository;
use Unifact\Connector\Repository\StageContract;
use Unifact\Connector\Repository\StageRepository;

class ConnectorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        // Configs
        $this->publishes([
            __DIR__ . '/../config/' => base_path('config')
        ], 'config');

        // Assets
        $this->publishes([
            __DIR__ . '/../assets/' => public_path('vendor/unifact')
        ], 'public');



        $this->loadViewsFrom(__DIR__ . '/../views', 'connector');

        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/../routes/routes.php';
        }

        $this->app['router']->middleware('connector.auth', Auth::class);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(JobContract::class, JobRepository::class);
        $this->app->singleton(StageContract::class, StageRepository::class);

        // Global logger
        $this->app->singleton(LoggerInterface::class, function ($app) {
            return ConnectorLogger::make();
        });

        // Keeps track of current connector state
        $this->app->singleton(StateOracle::class, StateOracle::class);


        // Register connector:run command
        $this->app['command.connector.run'] = $this->app->share(function ($app) {
            return app(RunCommand::class);
        });

        $this->commands('command.connector.run');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['command.connector.run'];
    }


}
