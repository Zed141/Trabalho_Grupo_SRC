<?php

$config = [
    'id' => 'projsrc-app',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'app\controllers',
    'defaultRoute' => 'app/index',
    'components' => [
        'request' => [
            'csrfParam' => '_csrfweb',
            'cookieValidationKey' => 'YlV8Kpr#$TT000hZDZC00peVGGVIFlfC',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'user' => [
            'identityClass' => 'app\models\Account',
            'enableSession' => true,
            'enableAutoLogin' => true,
            'loginUrl' => ['app/login'],
            'identityCookie' => [
                'name' => '_identity-projsrc', 'httpOnly' => true
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'app/error'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'cache' => 'cache'
            //'rules' => [
            //],
        ],
//        'assetManager' => [
//            'bundles' => [
//                'yii\bootstrap\BootstrapAsset' => ['css' => [], 'js' => []]
//            ]
//        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => ''
        ],
//        'session' => [
//            // this is the name of the session cookie used for login on the app
//            'name' => 'projsrc-app',
//        ],
        //TODO: Configure and uncomment!
        //'assetManager' => [
        //    'bundles' => [
        //        'yii\bootstrap\BootstrapAsset' => ['css' => [], 'js' => []]
        //    ]
        //],
    ],
    'params' => [],
];

$prod = realpath(__DIR__ . '/web.prod.php');
if (is_file($prod)) {

    include $prod;
}

$test = realpath(__DIR__ . '/web.test.php');
if (defined('YII_DEBUG') && defined('YII_ENV') && YII_DEBUG && YII_ENV == 'test' &&
    is_file($test)) {

    include $test;
}

$dev = realpath(__DIR__ . '/web.dev.php');
if (defined('YII_DEBUG') && defined('YII_ENV') && YII_DEBUG && YII_ENV == 'dev' &&
    is_file($dev)) {

    include $dev;
}

return $config;