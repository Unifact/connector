<?php /* created by Rob van Bentem, 13/10/2015 */

use Monolog\Logger;

return [
    'logging' => [
        'context' => 'connector',
        'handlers' => [
            'file' => [
                'enabled' => true,
                'level' => Logger::DEBUG,
            ],
            'db' => [
                'enabled' => true,
                'level' => Logger::INFO,
            ],
            'hipchat' => [
                'enabled' => true,
                'level' => Logger::ERROR,
                'token' => '',
                'room' => '',
                'name' => '',
                'notify' => true // blink to draw attention to the user
            ],
        ]
    ]
];
