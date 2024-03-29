<?php

$environment = 'prod';
$env = realpath(__DIR__ . '/../_ENV');
if (is_file($env)) {
    $environment = trim(file_get_contents($env));
}

if ($environment === 'development') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', false);
    defined('YII_ENV') or define('YII_ENV', 'prod');
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../common/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../common/config/main.php',
    require __DIR__ . '/../app/config/web.php'
);

(new yii\web\Application($config))->run();
