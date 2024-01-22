<?php

use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = Yii::t('app', 'Login');
$baseUrl = Yii::$app->urlManager->baseUrl;
$this->registerJsFile('/static/js/login.js', ['depends' => '\app\assets\AppAsset']);
?>
<div class="text-center mb-4">
    <a href="<?= $baseUrl ?>/" class="navbar-brand navbar-brand-autodark">
        <img src="/static/images/logo2.png" height="36" alt="Ciphered Lock"></a>
</div>

<h2 class="h3 text-center mb-3"><?= Yii::t('app', 'Login to your account') ?></h2>

<form action="#" method="post" autocomplete="off" novalidate id="login-form">
    <div class="mb-3">
        <label for="login-email" class="form-label"><?= Yii::t('app', 'Email address') ?></label>
        <input id="login-email" type="email" class="form-control" autocomplete="off"
               placeholder="<?= Yii::t('app', 'your@email.com') ?>">
    </div>

    <div class="form-footer">
        <button type="button" id="login-btn" class="btn btn-primary w-100"
                data-stage1url="<?= Url::to(['/app/start-login']) ?>"
                data-stage2url="<?= Url::to(['/app/confirm-login']) ?>"
        ><?= Yii::t('app', 'Sign in') ?></button>
    </div>
</form>

<div class="text-center text-muted mt-3">
    <?= Yii::t('app', "Don't have account yet?") ?>
    <a href="<?= Url::to(['/register/index']) ?>" tabindex="-1">
        <?= Yii::t('app', 'Sign up') ?>
    </a>
</div>