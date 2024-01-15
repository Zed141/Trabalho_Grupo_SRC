<?php

error_reporting(E_ALL);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', true);
ini_set('log_errors', true);

$config['bootstrap'][] = 'log';
$config['components']['log']['traceLevel'] = 3;

$config['components']['cache']['class'] = 'yii\caching\DummyCache';
unset($config['components']['cache']['useApcu']);

$config['components']['db']['dsn'] = '';
$config['components']['db']['username'] = '';
$config['components']['db']['password'] = '';
$config['components']['db']['enableSchemaCache'] = false;

$config['components']['mailer']['useFileTransport'] = true;