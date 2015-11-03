## Changelog

#### [1.1.1] 2015-11-03
-   Added: added `handler` column in connector_jobs
-   Added: queued jobs will resolve handler through service container based on connector_jobs `handler` column

#### [1.1.0] 2015-10-30
-   Added: `NativeEmailHandler` now available for logging
-   Added: Details view for log entries
-   Added: Priority for jobs, can be run on a high or low priority queue handler
-   Added: Cron expressions to cron handlers
-   Changed: Switched the logs table to MyISAM engine
-   Changed: Jobs are running on the laravel queue

#### [1.0.2] 2015-10-26
-   Added: `ConnectorLoggerInterface` is now available in `Stage` and `CronHandler` class as $this->logger
-   Added: `StateOracle` can now be accessed through the `ConnectorLoggerInterface` getOracle method
-   Added: Some `StateOracle` improvements (see setVar(), exception()), asArray now used in addRecord (ConnectorLogger)
-   Added: Improved logging in `Stage` and `CronHandler`

#### [1.0.1] 2015-10-26
-   Added: some basic documentation
-   Added: `JobProviderContact` as an interface for inserting jobs, is available through service container
-   Changed: resolve `JobProviderContract` in `ConnectorRunCronEvent` ctor and made it publicly available

#### [1.0.0] 2015-10-23
-   First public release
