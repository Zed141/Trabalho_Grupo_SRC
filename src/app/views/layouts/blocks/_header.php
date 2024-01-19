<?php

use app\helpers\SvgIconIndex;
use yii\helpers\Url;

/* @var $this \yii\web\View */

//TODO: Remove example code
?>
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $baseUrl ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><?= SvgIconIndex::icon(SvgIconIndex::HOME) ?></span>
                            <span class="nav-link-title"> Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Url::to(['/vault/index']) ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><?= SvgIconIndex::icon(SvgIconIndex::SHIELD) ?></span>
                            <span class="nav-link-title"> Vaults</span>
                        </a>
                    </li>
                </ul>
                
                <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                    <form action="<?= Url::to(['/app/search']) ?>" method="get" autocomplete="off" novalidate>
                        <div class="input-icon">
                    <span class="input-icon-addon">
                      <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                           stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                           stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path
                                  d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"/><path d="M21 21l-6 -6"/></svg>
                    </span>
                            <input type="text" value="" class="form-control" placeholder="Search ..."
                                   aria-label="Search in website">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>