<?php

/** @var yii\web\View $this */

use app\helpers\SvgIconIndex;

$this->title = 'Copyright Info';
?>

<div class="alert alert-info" role="alert">
    <div class="d-flex">
        <div>
            <?= SvgIconIndex::icon(SvgIconIndex::INFO) ?>
        </div>
        <div>
            <h4 class="alert-title">Masters in Cybersecurity and Digital Forensics</h4>
            <div class="text-secondary">
                <p>ESTG, IPL</p>
                <p>José Irio<br />Sérgio Lopes</p>
            </div>
        </div>
    </div>
</div>
