### Unifact connector

#### Intro

Connector package for Laravel 5.1


#### ENV settings

- `CONNECTOR_DOMAIN` = The full domain the connector routes are available on (default: 'connector.local.dev')
- `CONNECTOR_PREFIX` = Prefix so we don't get route collisions with app routes (default: '/cnr')
- `CONNECTOR_USER` = Username to access the connector
- `CONNECTOR_PASS` = Password to access the connector (hashed with sha1)
