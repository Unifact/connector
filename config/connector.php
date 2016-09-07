<?php /* created by Rob van Bentem, 13/10/2015 */

use Monolog\Logger;

return [
    'logging' => [
        'context' => 'connector',
        'logviewer' => [
            'min_level' => env('CONNECTOR_LOGVIEW_LEVEL', Logger::DEBUG)
        ],
        'handlers' => [
            'file' => [
                'enabled' => env('CONNECTOR_LOG_FILE_ENABLED', true),
                'level' => env('CONNECTOR_LOG_FILE_LEVEL', Logger::DEBUG),
                'keep_days' => env('CONNECTOR_LOG_FILE_KEEP_DAYS', 30)
            ],
            'db' => [
                'enabled' => env('CONNECTOR_LOG_DB_ENABLED', true),
                'level' => env('CONNECTOR_LOG_DB_LEVEL', Logger::NOTICE),
            ],
            'email' => [
                'enabled' => env('CONNECTOR_LOG_EMAIL_ENABLED', false),
                'level' => env('CONNECTOR_LOG_EMAIL_LEVEL', Logger::ERROR),
                'from' => env('CONNECTOR_LOG_EMAIL_FROM', null), // defaults to error@HOSTNAME
                'subject' => 'Error in %s application',
                'to' => env('CONNECTOR_LOG_EMAIL_TO') ? explode('|', env('CONNECTOR_LOG_EMAIL_TO')) : [],
            ],
            'hipchat' => [
                'enabled' => env('CONNECTOR_LOG_HIPCHAT_ENABLED', false),
                'level' => env('CONNECTOR_LOG_HIPCHAT_LEVEL', Logger::ERROR),
                'token' => env('CONNECTOR_LOG_HIPCHAT_TOKEN', ''),
                'room' => env('CONNECTOR_LOG_HIPCHAT_ROOM', ''),
                'name' => env('CONNECTOR_LOG_HIPCHAT_NAME', ''),
                'notify' => true // blink to draw attention to the user
            ],
        ]
    ],
    'icons' => [
        'job' => 'glyphicon glyphicon-record',
        'stage' => 'glyphicon glyphicon-unchecked',
        'log' => 'glyphicon glyphicon-console'
    ]
];
