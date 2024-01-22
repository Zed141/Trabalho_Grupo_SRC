<?php

use app\components\GridView;
use app\helpers\SvgIconIndex;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\filters\Vaults $provider */

$this->title = 'Vault List';
$this->params = [
    'buttons' => [
        (object)[
            'type' => 'group', 'url' => '#', 'labelSm' => 'Create Vault', 'label' => 'Create Vault', 'actionBtn' => true,
            'id' => 'add-vault-btn', 'smId' => 'add-vault-smbtn'
        ]
    ]
];

$this->registerJsFile('/static/js/vault.js', ['depends' => 'app\assets\AppAsset']);
?>
<div class="card">
    <?= GridView::widget([
        'dataProvider' => $provider,
        'columns' => [
            'description',
            'username',
            'url',
            [
                'label' => '',
                'headerOptions' => ['style' => 'width: 5rem;'],
                'content' => function ($model) {
                    return Html::a('Edit', '#', ['data-id' => $model['id'], 'class' => 'edit-btn']);
                }
            ]
        ]
    ]);
    ?>
</div>

<input type="hidden" id="details-url" value="<?= Url::to('/vault/details') ?>"/>

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

                <button type="button" class="btn btn-success ms-auto" data-bs-dismiss="modal" id="save-vault-btn"
                        data-createurl="<?= Url::to(['/vault/create']) ?>"
                        data-updateurl="<?= Url::to(['/vault/update']) ?>"
                        data-id="">
                    <?= SvgIconIndex::icon(SvgIconIndex::CHECK) ?>
                    <?= Yii::t('app', 'Save') ?>
                </button>
            </div>
        </div>
    </div>
</div>
