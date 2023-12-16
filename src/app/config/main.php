<?php

use yii\caching\FileCache;
use yii\log\FileTarget;

return [
    'id' => 'projsrc-app',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'request' => [
            'csrfParam' => '_csrf-app',
        ],
        'user' => [
            'identityClass' => 'app\models\Account',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-projsrc', 'httpOnly' => true
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the app
            'name' => 'projsrc-app',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'rules' => [
            //],
        ],
    ],
    'params' => require __DIR__ . '/params.php',
];
