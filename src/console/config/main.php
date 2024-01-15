<?php

use yii\console\controllers\FixtureController;
use yii\log\FileTarget;

$params = array_merge(
    require __DIR__ . '/../../app/config/params.php',
//TODO: require __DIR__ . '/params-local.php'
);

return [
    'id' => 'projsrc-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'fixture' => [
            'class' => FixtureController::class,
            'namespace' => 'common\fixtures',
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => [],
];
