<?php

//TODO: Setup debug detection process
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../app/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../../app/config/main.php',
//TODO: Fix for dev vs. prod > require __DIR__ . '/../app/config/main-local.php',
);

(new yii\web\Application($config))->run();
