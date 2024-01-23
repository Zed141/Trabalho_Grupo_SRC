<?php

use app\helpers\SvgIconIndex;
use yii\helpers\Url;

/** @var yii\web\View $this */
?>
<div class="modal modal-blur fade" id="modal-create-vault" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'New Vault') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="<?= Yii::t('app', 'Close') ?>"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="c-vault-description"
                                   class="form-label"><?= Yii::t('app', 'Description') ?></label>
                            <input id="c-vault-description" type="text" class="form-control"
                                   placeholder="<?= Yii::t('app', 'Name you new vault') ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="c-vault-data" class="form-label"><?= Yii::t('app', 'Password') ?></label>
                            <input id="c-vault-data" type="text" class="form-control"
                                   placeholder="<?= Yii::t('app', 'Password you wish to save') ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="c-vault-username" class="form-label"><?= Yii::t('app', 'Username') ?></label>
                            <input id="c-vault-username" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="c-vault-url" class="form-label">URL</label>
                            <input id="c-vault-url" type="text" class="form-control" name="Vault[url]">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div>
                            <label for="c-vault-notes"
                                   class="form-label"><?= Yii::t('app', 'Additional information') ?></label>
                            <textarea id="c-vault-notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary"
                        data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>

                <button type="button" class="btn btn-success ms-auto" data-bs-dismiss="modal" id="create-vault-btn"
                        data-url="<?= Url::to(['/vault/create']) ?>" >

                    <?= SvgIconIndex::icon(SvgIconIndex::CHECK) ?>
                    <?= Yii::t('app', 'Create') ?>
                </button>
            </div>
        </div>
    </div>
</div>
