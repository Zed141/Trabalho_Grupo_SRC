<?php

use app\components\GridView;
use app\helpers\SvgIconIndex;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\filters\Vaults $provider */
/** @var string $userId */

$this->title = 'Vault List';
$this->params = [
    'buttons' => [
        (object)[
            'type' => 'group', 'url' => '#', 'labelSm' => 'Create Vault', 'label' => 'Create Vault', 'actionBtn' => true,
            'id' => 'create-vault-btn', 'smId' => 'create-vault-smbtn'
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
                [
                    'attribute' => 'url',
                    'content' => function ($model) {
                        if (empty($model['url'])) {
                            return '';
                        }
                        return Html::a(SvgIconIndex::icon(SvgIconIndex::EXTERNAL_LINK) . ' ' . $model['url'], $model['url'], ['target' => '_blank']);
                    }
                ],
                [
                    'label' => '',
                    'headerOptions' => ['style' => 'width: 10rem;'],
                    'content' => function ($model) use ($userId) {
                        if ($userId == $model['owner_id']) {
                            return '<div class="btn-list">'
                                . Html::button(SvgIconIndex::icon(SvgIconIndex::SHARE) . Yii::t('app', 'Share'), ['type' => 'button', 'data-id' => $model['id'], 'class' => 'share-btn btn btn-sm btn-ghost-info'])
                                . Html::button(SvgIconIndex::icon(SvgIconIndex::EDIT) . Yii::t('app', 'Edit'), ['type' => 'button', 'data-id' => $model['id'], 'class' => 'edit-btn btn btn-sm btn-ghost-success'])
                                . '</div>';
                        }

                        return Html::button(SvgIconIndex::icon(SvgIconIndex::EYE) . Yii::t('app', 'Edit'), ['type' => 'button', 'data-id' => $model['id'], 'class' => 'edit-btn btn btn-sm btn-ghost-success']);
                    }
                ]
            ]
        ]);
        ?>
    </div>

    <input type="hidden" id="vault-secret-url" value="<?= Url::to('/vault/vault-secret') ?>"/>
    <input type="hidden" id="details-url" value="<?= Url::to('/vault/details') ?>"/>
    <input type="hidden" id="users-url" value="<?= Url::to('/vault/available-user-list') ?>"/>

<?=
$this->render('_create-vault-modal'),
$this->render('_edit-vault-modal'),
$this->render('_share-vault-modal');
