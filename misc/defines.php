<?php /* created by Rob van Bentem, 20/10/2015 */

if(defined('CONNECTOR_DEFINES') === false){
    define('CONNECTOR_DEFINES', true);
    define("JOB_ICON", \Config::get('connector.icons.job'));
    define("STAGE_ICON", \Config::get('connector.icons.stage'));
    define("LOG_ICON", \Config::get('connector.icons.log'));
    define("CONNECTOR_DATE_FORMAT", \Config::get('connector.common.date_format', 'D M. d H:i'));
}
