<?php

use yii\log\FileTarget;
use yii\symfonymailer\Mailer;

$config = [
    'name' => 'Projeto SRC',
    'aliases' => [
        '@bower' => '@container/vendor/bower-asset',
        '@npm' => '@container/vendor/npm-asset',
    ],
    'vendorPath' => '@container/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\ApcCache',
            'useApcu' => true
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages'
                ]
            ]
        ],
        'mailer' => [
            'class' => Mailer::class,
            'useFileTransport' => true,
            'transport' => [
                'scheme' => '',
                'host' => '',
                'port' => 25,
                'options' => ['ssl' => false]
            ],
        ],
        'log' => [
            'traceLevel' => 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning']
                ]
            ]
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => '<dsn>',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => (24 * 60 * 60),
            'schemaCache' => 'cache'
        ],
    ],
    'params' => []
];

$prod = realpath(__DIR__ . '/main.prod.php');
if (is_file($prod)) {

    include $prod;
}

$test = realpath(__DIR__ . '/main.test.php');
if (defined('YII_DEBUG') && defined('YII_ENV') && YII_DEBUG && YII_ENV == 'test' &&
    is_file($test)) {

    include $test;
}

$dev = realpath(__DIR__ . '/main.dev.php');
if (defined('YII_DEBUG') && defined('YII_ENV') && YII_DEBUG && YII_ENV == 'dev' &&
    is_file($dev)) {

    include $dev;
}

return $config;