<?php

use app\helpers\SvgIconIndex;
use yii\helpers\Url;

/** @var yii\web\View $this */
?>
<div class="modal modal-blur fade" id="modal-edit-vault" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'Vault Details') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="<?= Yii::t('app', 'Close') ?>"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="e-vault-description"
                                   class="form-label"><?= Yii::t('app', 'Description') ?></label>
                            <input id="e-vault-description" type="text" class="form-control"
                                   placeholder="<?= Yii::t('app', 'Vault name') ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="e-vault-data" class="form-label"><?= Yii::t('app', 'Password') ?></label>
                            <input id="e-vault-data" type="text" class="form-control"
                                   placeholder="<?= Yii::t('app', 'Password you wish to save') ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="e-vault-username" class="form-label"><?= Yii::t('app', 'Username') ?></label>
                            <input id="e-vault-username" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="e-vault-url" class="form-label">URL</label>
                            <input id="e-vault-url" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div>
                            <label for="e-vault-notes"
                                   class="form-label"><?= Yii::t('app', 'Additional information') ?></label>
                            <textarea id="e-vault-notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="hidden" id="v-nonce">
                <input type="hidden" id="v-tag">
                <input type="hidden" id="v-sec">

                <button type="button" class="btn btn-link link-secondary"
                        data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>

                <button type="button" class="btn btn-success ms-auto" data-bs-dismiss="modal" id="edit-vault-btn"
                        data-url="<?= Url::to(['/vault/update']) ?>" data-id="">
                    <?= SvgIconIndex::icon(SvgIconIndex::CHECK) ?>
                    <?= Yii::t('app', 'Save') ?>
                </button>
            </div>
        </div>
    </div>
</div>
