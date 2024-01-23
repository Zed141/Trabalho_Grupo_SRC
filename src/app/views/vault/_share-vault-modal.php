<?php

use app\components\GridView;
use app\helpers\SvgIconIndex;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
?>

<div class="modal modal-blur fade" id="modal-share-vault" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'Share Vault') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="<?= Yii::t('app', 'Close') ?>"></button>
            </div>

            <div class="modal-body">
                <div class="divide-y" id="vault-users-list">
                    <!-- PLACEHOLDER -->
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary"
                        data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>

                <button type="button" class="btn btn-info ms-auto" data-bs-dismiss="modal" id="share-vault-btn"
                        data-url="<?= Url::to(['/vault/share']) ?>" data-id="">

                    <?= SvgIconIndex::icon(SvgIconIndex::SHARE) ?>
                    <?= Yii::t('app', 'Share') ?>
                </button>
            </div>
        </div>
    </div>
</div>