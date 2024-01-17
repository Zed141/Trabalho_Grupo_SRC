<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \yii\web\View $this */
/** @var string $content */

AppAsset::register($this);

$baseUrl = Yii::$app->urlManager->baseUrl;
$subtitle = $this->params['subtitle'] ?? null;
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

    <body>
    <?php $this->beginBody() ?>
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                        aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="<?= $baseUrl ?>">
                        <!-- //TODO: APP SRC LOGO! -->
                        <img src="./static/logo.svg" width="110" height="32" alt="SRC" class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">

                    <?= $this->render('blocks/_updates-list') ?>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                           aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                  style="background-image: url(./static/avatars/000m.jpg)"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>&lt;user name&gt;</div>
                                <div class="mt-1 small text-muted">smaller example text</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="<?= Url::to(['/app/profile']) ?>" class="dropdown-item">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= Url::to(['/app/settings']) ?>" class="dropdown-item">Settings</a>
                            <a href="<?= Url::to(['/app/logout']) ?>" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?= $this->render('blocks/_header', ['baseUrl' => $baseUrl]) ?>

        <div class="page-wrapper">
            <!-- Page header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <?php if (!empty($subtitle)) { ?>
                                <div class="page-pretitle"></div>
                            <?php } ?>
                            <h2 class="page-title"><?= Html::encode($this->title) ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page body -->
            <div class="page-body">
                <div class="container-xl">
                    <?= $content ?>
                </div>
            </div>

            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    <a href="<?= Url::to(['/app/documentation']) ?>"
                                       class="link-secondary" rel="noopener">Documentation</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; <?= date('Y') ?>
                                    <a href="<?= Url::to(['/app/copyright']) ?>" class="link-secondary">
                                        José Irio & Sérgio Lopes
                                    </a>. All rights reserved.
                                </li>
                                <li class="list-inline-item">
                                    <a href="<?= Url::to([Url::to('/app/changelog')]) ?>" class="link-secondary"
                                       rel="noopener">v1.0.0</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();