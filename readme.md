## Unifact Connector

### Intro

Unifact Connector package for Laravel 5.2 

#### Installation

    composer require unifact/connector

1.  Add `Unifact\Connector\ConnectorServiceProvider` to app providers config
2.  Run `php artisan vendor:publish --provider="Unifact\Connector\ConnectorServiceProvider"` to copy the assets, config and migrations.
3.  Run `php artisan migrate`, there are three tables the system uses.
4.  Configure the following .env settings:
    - `CONNECTOR_DOMAIN`: The full domain the connector routes are available on (default: 'connector.local.dev')
    - `CONNECTOR_PREFIX`: Prefix so we don't get route collisions with app routes (default: '/cnr')
    - `CONNECTOR_USER`: Username to access the connector
    - `CONNECTOR_PASS`: Password to access the connector (must be hashed with sha1)
    - `CONNECTOR_QUEUE_HIGH`: Name of the high priority queue
    - `CONNECTOR_QUEUE_LOW`: Name of the low priority queue
    - `CONNECTOR_QUEUE_HIGH_THRESHOLD`: The threshold needed for a job to become high priority
    
    The connector needs (need is a big word, but it's smart to do it like this) both a subdomain and a routing prefix in order to work without conflict with your application.
5.  Look at the `/config/connector.php` file and optionally turn on Hipchat logging (various other logging methods are available and configurable).
6.  Turn your browser to the configured url and try to log in.

#### How to use

- Resolve `JobProviderContract` through the service container to insert connector jobs.
- Listen for the `ConnectorRegisterEvent` to register JobHandlers and/or CronHandlers.

Run `php artisan connector:run` to perform the cron event and handle all connector jobs.
