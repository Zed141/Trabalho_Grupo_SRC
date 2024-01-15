<?php

$config['components']['request']['cookieValidationKey'] = '__dummy__';
$config['components']['request']['csrfCookie'] = [
    'expire' => time() + (3600 * 4),
    //'secure' => true
];

unset($config['components']['urlManager']['cache']);

$config['bootstrap'][] = 'debug';
$config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    'allowedIPs' => ['*', '127.0.0.1', '::1'],
    'panels' => [
        'user' => [
            'class' => 'yii\debug\panels\UserPanel',
            'ruleUserSwitch' => [
                'allow' => true
            ]
        ]
    ]
];

//$config['bootstrap'][] = 'gii';
//$config['modules']['gii'] = [
//    'class' => 'yii\gii\Module',
//    'allowedIPs' => ['*', '127.0.0.1', '::1']
//];
//
//$config['components']['assetManager']['forceCopy'] = true;
