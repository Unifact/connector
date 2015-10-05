<?php

namespace Unifact\Connector;

use Illuminate\Support\ServiceProvider;
use Unifact\Connector\Console\RunCommand;

class ConnectorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {

        // Reguister connector:run command
        $this->app['command.connector.run'] = $this->app->share(function ($app) {
            return new RunCommand();
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
