# Changelog

## [1.0.2] 2015-10-26
-   Added: `ConnectorLoggerInterface` is now available in `Stage` and `CronHandler` class as $this->logger
-   Added: `StateOracle` can now be accessed through the `ConnectorLoggerInterface` getOracle method
-   Added: Some `StateOracle` improvements (see setVar(), exception()), asArray now used in addRecord (ConnectorLogger)
-   Added: Improved logging in `Stage` and `CronHandler`

## [1.0.1] 2015-10-26
-   Added: some basic documentation
-   Added: `JobProviderContact` as an interface for inserting jobs, is available through service container
-   Changed: resolve `JobProviderContract` in `ConnectorRunCronEvent` ctor and made it publicly available

## [1.0.0] 2015-10-23
-   First public release
