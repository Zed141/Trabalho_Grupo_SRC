<?php

use app\helpers\SvgIconIndex;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = Yii::t('app', 'Login');
$baseUrl = Yii::$app->urlManager->baseUrl;
$this->registerJsFile('/static/js/login.js');
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

    <!-- <div class="mb-2">
        <label class="form-label" for="key-info">
            <?= Yii::t('app', 'RSA Key') ?>
            <span class="form-label-description"><a href="<?= Url::to(['/register/index']) ?>">No key found?</a></span>
        </label>

        <div class="input-group input-group-flat">
            <input id="key-info" readonly type="text" class="form-control" placeholder="" autocomplete="off">
            <span class="input-group-text">
                  <a id="search-key-btn" href="#" class="link-secondary" data-bs-toggle="tooltip"
                     aria-label="<?= Yii::t('app', 'Search key again') ?>"
                     data-bs-original-title="<?= Yii::t('app', 'Search key again') ?>">
                      <?= SvgIconIndex::icon(SvgIconIndex::KEY) ?>
                  </a>
                </span>
        </div>
    </div> -->

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