<?php

use app\helpers\SvgIconIndex;

/* @var $this \yii\web\View */

?>
<div class="d-none d-md-flex">
    <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
           aria-label="Show notifications">
            <?= SvgIconIndex::icon(SvgIconIndex::BELL) ?>
            <!-- //TODO: <span class="badge bg-red"></span> -->
        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= Yii::t('app', 'Last updates') ?></h3>
                </div>
                <div class="list-group list-group-flush list-group-hoverable">
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <!-- //TODO: Revoke access <span class="status-dot status-dot-animated bg-red d-block"></span> -->
                                <span class="status-dot status-dot-animated bg-green d-block"></span>
                            </div>
                            <div class="col text-truncate">
                                <a href="#" class="text-body d-block">Example 4</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    Regenerate package-lock.json (#29730)
                                </div>
                            </div>
                            <div class="col-auto"><!-- PLACEHOLDER/SPACER --></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>