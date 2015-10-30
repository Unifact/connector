## Unifact Connector

### Intro

Unifact Connector package for Laravel 5.1. 

#### Installation

1.  Run `php artisan vendor:publish --provider="Unifact\Connector\ConnectorServiceProvider"` to copy the assets, config and migrations.
3.  Run `php artisan migrate`, there are three tables the system uses.
2.  Configure the fo;lowing .env settings:
    - `CONNECTOR_DOMAIN`: The full domain the connector routes are available on (default: 'connector.local.dev')
    - `CONNECTOR_PREFIX`: Prefix so we don't get route collisions with app routes (default: '/cnr')
    - `CONNECTOR_USER`: Username to access the connector
    - `CONNECTOR_PASS`: Password to access the connector (must be hashed with sha1)
    - `CONNECTOR_QUEUE_HIGH`: Name of the high priority queue
    - `CONNECTOR_QUEUE_LOW`: Name of the low priority queue
    - `CONNECTOR_QUEUE_HIGH_TRESHOLD`: The treshold needed for a job to become high priority
    
    The connector needs (need is a big word, but it's smart to it like this) both a subdomain and a routing prefix in order to work without conflict with your application.
4.  Look at the `/config/connector.php` file and optionally turn on Hipchat logging.
5.  Turn your browser to the configured url and try to log in.

#### How to use

- Resolve `JobProviderContract` through the service container to insert connector jobs.
- Listen for the `ConnectorRunCronEvent` to perform tasks before handling connector jobs.
- Listen for the `ConnectorRegisterHandlerEvent` to register JobHandlers for your connector job types.

Run `php artisan connector:run` to perform the cron event and handle all connector jobs.
