<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'bootstrap'  => [
        'queue',
        'log'
    ],
    'language'   => 'ru-RU',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log'                => [
            'traceLevel' =>  0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ]
            ],
        ],
        'queue'              => [
            'class'    => yii\queue\amqp\Queue::class,
            'host'     => env('RABBITMQ_HOST'),
            'user'     => env('RABBITMQ_USER'),
            'password' => env('RABBITMQ_PASS'),
        ],
    ],
];
