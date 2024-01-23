<?php

use app\assets\AppAsset;
use app\helpers\SvgIconIndex;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var \yii\web\View $this */
/** @var string $content */

AppAsset::register($this);

$baseUrl = Yii::$app->urlManager->baseUrl;

$subtitle = $this->params['subtitle'] ?? null;
$buttons = $this->params['buttons'] ?? [];

$this->registerJsFile('/static/js/common.js', ['position' => View::POS_HEAD]);

/** @var \app\orm\User $user */
$user = !Yii::$app->user->isGuest ? Yii::$app->user->identity->getUser() : null;
$this->beginPage();
?>
    <!doctype html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
        <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">


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
                    <a href="<?= $baseUrl ?>/">
                        <img src="/static/images/logo2.png" width="110" height="32" alt="Ciphered Lock"
                             class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">

                    <?= $this->render('blocks/_updates-list') ?>

                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                           aria-label="Open user menu">
                            <span class="avatar avatar-sm"
                                  style="background-image: url(/static/images/avatar-1577909_640.png)"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div><?= $user?->name ?></div>
                                <div class="mt-1 small text-muted"><?= $user?->email ?></div>
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
                    <div class="row g-2 align-items-center"> <!-- -->
                        <div class="col">
                            <?php if (!empty($subtitle)) { ?>
                                <div class="page-pretitle"></div>
                            <?php } ?>
                            <h2 class="page-title"><?= Html::encode($this->title) ?></h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <?php if (!empty($buttons)) {
                                    foreach ($buttons as $button) {
                                        switch ($button->type) {
                                            case 'single':
                                                $tag = '<a href="' . Url::to($button->url) . '">' . $button->label . '</a>';
                                                if ($button->actionBtn) {
                                                    $tag = '<button type="button" class="btn" id="' . $button->id . '"' . (!empty($button->url) ? 'data-action="' . $button->url . '"' : '') . '>' . $button->label . '</button>';
                                                }
                                                echo '<span class="d-none d-sm-inline">', $tag, '</span>';
                                                break;
                                            case 'group':

                                                $data = '';
                                                if (!empty($button->data)) {
                                                    $data = trim(implode(' ', $button->data));
                                                }

                                                if ($button->actionBtn) {
                                                    echo '<button type="button" id="', $button->id, '" class="btn btn-primary d-none d-sm-inline-block" ', $data . '>',
                                                    SvgIconIndex::icon(SvgIconIndex::PLUS), $button->label, '</button>',
                                                    '<button type="button" id="', $button->smId, '" class="btn btn-primary d-sm-none btn-icon" ', $data,
                                                    ' aria-label="', $button->labelSm, '"></button>';
                                                    break;
                                                }

                                                echo '<a href="', Url::to([$button->url]), '" class="btn btn-primary d-none d-sm-inline-block" ',
                                                $data, '">', SvgIconIndex::icon(SvgIconIndex::PLUS), $button->label, '</a>',
                                                '<a href="', Url::to([$button->url]), '" class="btn btn-primary d-sm-none btn-icon" ',
                                                $data, ' aria-label="', $button->labelSm, '">', SvgIconIndex::icon(SvgIconIndex::PLUS), '</a>';
                                                break;
                                        }
                                    }
                                }
                                ?>
                            </div>
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
                                       class="link-secondary" rel="noopener"><?= Yii::t('app', 'Help/FAQ') ?></a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; <?= date('Y') ?>
                                    <a href="<?= Url::to(['/app/copyright']) ?>" class="link-secondary">
                                        José Irio & Sérgio Lopes
                                    </a>. <?= Yii::t('app', 'All rights reserved.') ?>
                                </li>
                                <li class="list-inline-item">
                                    <a href="<?= Url::to([Url::to('/app/changelog')]) ?>" class="link-secondary"
                                       rel="noopener">v<?= Yii::$app->version ?></a>
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