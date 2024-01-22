<?php

use yii\helpers\Url;

/** @var \yii\web\View $this */

$baseUrl = Yii::$app->urlManager->baseUrl;
$this->registerJsFile('/static/js/register.js', ['depends' => '\app\assets\AppAsset']);
?>
<div class="text-center mb-4">
    <a href="<?= $baseUrl ?>/" class="navbar-brand navbar-brand-autodark">
        <img src="/static/images/logo2-transp.png" height="36" alt="Ciphered Lock">
    </a>
</div>
<form class="card card-md" action="#" method="post" autocomplete="off" novalidate id="registration-form">
    <div class="card-body">
        <h2 class="card-title text-center mb-4"><?= Yii::t('app', 'Create new account') ?></h2>
        <div class="mb-3">
            <label for="register-name" class="form-label"><?= Yii::t('app', 'Name') ?></label>
            <input id="register-name" type="text" class="form-control" placeholder="<?= Yii::t('app', 'Enter name') ?>">
        </div>

        <div class="mb-3">
            <label for="register-email" class="form-label"><?= Yii::t('app', 'Email address') ?></label>
            <input id="register-email" type="email" class="form-control"
                   placeholder="<?= Yii::t('app', 'Enter email') ?>">
        </div>

        <div class="mb-3">
            <label class="form-check">
                <input type="checkbox" class="form-check-input"/>
                <span class="form-check-label"><?= Yii::t('app', 'Agree the {link}.', [
                        'link' => '<a href="' . Url::to(['/app/terms-and-conditions']) . '" tabindex="-1">' . Yii::t('app', 'terms and policy') . '</a>'
                    ]) ?></span>
            </label>
        </div>

        <div class="form-footer">
            <button type="button" id="register-btn" class="btn btn-primary w-100"
                    data-url="<?= Url::to(['/register/store']) ?>">
                <?= Yii::t('app', 'Create new account') ?>
            </button>
        </div>
    </div>
</form>
<div class="text-center text-muted mt-3">
    <?= Yii::t('app', 'Already have account?') ?>
    <a href="<?= Url::to(['/app/login']) ?>" tabindex="-1"><?= Yii::t('app', 'Sign in') ?></a>
</div>
