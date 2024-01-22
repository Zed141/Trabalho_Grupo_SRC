<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;

/** @var \yii\web\View $this */
/** @var string $content */

AppAsset::register($this);

$baseUrl = Yii::$app->urlManager->baseUrl;
$subtitle = $this->params['subtitle'] ?? null;
$buttons = $this->params['buttons'] ?? [];

$this->registerJsFile('/static/js/common.js', ['position' => View::POS_HEAD]);

$this->beginPage();
?>
    <!doctype html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->registerCsrfMetaTags() ?>
        <?php $this->head() ?>
    </head>

    <body class="d-flex flex-column">
    <?php $this->beginBody() ?>
    <div class="page page-center">
        <div class="container container-tight py-4"><?= $content ?></div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();