<?php

use app\helpers\SvgIconIndex;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $baseUrl string */
?>
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $baseUrl ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><?= SvgIconIndex::icon(SvgIconIndex::HOME) ?></span>
                            <span class="nav-link-title"> <?= Yii::t('app', 'Home') ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['/vault/index']) ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><?= SvgIconIndex::icon(SvgIconIndex::SHIELD) ?></span>
                            <span class="nav-link-title"> <?= Yii::t('app', 'Vaults') ?></span>
                        </a>
                    </li>
                </ul>

                <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                    <form action="<?= Url::to(['/app/search']) ?>" method="get" autocomplete="off" novalidate>
                        <div class="input-icon">
                            <span class="input-icon-addon"><?= SvgIconIndex::icon(SvgIconIndex::SEARCH) ?> </span>
                            <input type="text" value="" class="form-control"
                                   placeholder="<?= Yii::t('app', 'Search ...') ?>"
                                   aria-label="<?= Yii::t('app', 'Search vaults') ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>