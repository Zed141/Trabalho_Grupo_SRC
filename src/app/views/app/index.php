<?php

use app\helpers\SvgIconIndex;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'CipheredLock';
?>

<div class="alert alert-info" role="alert">
    <div class="d-flex">
        <div>
            <?= SvgIconIndex::icon(SvgIconIndex::INFO) ?>
        </div>
        <div>
            <h4 class="alert-title">CipheredLock</h4>
            <div class="text-secondary">
                Welcome to you account. Check your passwords in the
                <a href="<?= Url::to(['/vault/index']) ?>">vaults</a> screen.
            </div>
        </div>
    </div>
</div>