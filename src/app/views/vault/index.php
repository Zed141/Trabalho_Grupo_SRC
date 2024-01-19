<?php

use app\components\GridView;
use app\helpers\SvgIconIndex;

/** @var yii\web\View $this */
/** @var app\filters\Vaults $provider */

$this->title = 'Vault List';
$this->params = [
    'buttons' => [
        (object)[
            'type' => 'group', 'url' => '#', 'labelSm' => 'Create Vault', 'label' => 'Create Vault', 'actionBtn' => true,
            'data' => ['data-bs-toggle="modal"', 'data-bs-target="#modal-add-vault"']
        ]
    ]
];

$this->registerJsFile('/static/js/vault.js', ['depends' => 'app\assets\AppAsset']);
?>
<div class="card">
    <?= GridView::widget([
        //'pageSize' => $pageSize,
        'dataProvider' => $provider,
        //'filterModel' => $filter,
        'columns' => [
            'description',
            'username',
            'url'
            //TODO: links, formatting, etc.
            //[
            //'attribute' => '',
            //'content' => function ($model) {
            //},
            //'filterInputOptions' => ['class' => 'form-control form-control-sm', 'id' => null]
            //]
        ]
    ]);
    ?>
</div>

<div class="modal modal-blur fade" id="modal-add-vault" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <label for="vault-description"
                                   class="form-label"><?= Yii::t('app', 'Description') ?></label>
                            <input id="vault-description" type="text" class="form-control" name="Vault[description]"
                                   placeholder="<?= Yii::t('app', 'Name you new vault') ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="vault-data" class="form-label"><?= Yii::t('app', 'Password') ?></label>
                            <input id="vault-data" type="text" class="form-control" name="Vault[data]"
                                   placeholder="<?= Yii::t('app', 'Password you wish to save') ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                            <label for="vault-username" class="form-label"><?= Yii::t('app', 'Username') ?></label>
                            <input id="vault-username" type="text" class="form-control" name="Vault[username]">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="vault-url" class="form-label">URL</label>
                            <input id="vault-url" type="text" class="form-control" name="Vault[url]">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div>
                            <label for="vault-notes"
                                   class="form-label"><?= Yii::t('app', 'Additional information') ?></label>
                            <textarea id="vault-notes" class="form-control" name="Vault[notes]" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary"
                        data-bs-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>

                <button type="button" class="btn btn-success ms-auto" data-bs-dismiss="modal">
                    <?= SvgIconIndex::icon(SvgIconIndex::CHECK) ?>
                    <?= Yii::t('app', 'Save') ?>
                </button>
            </div>
        </div>
    </div>
</div>
