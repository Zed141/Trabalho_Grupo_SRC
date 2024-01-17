<?php

use yii\helpers\Url;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $name string */

//$this->registerJs('var globalX = "' . $name . '";', View::POS_HEAD);
$this->registerJsFile('/static/js/register.js');
?>

<p>&nbsp;</p>

<h1>Vista Exemplo</h1>

<p><?= $name ?></p>

<input type="hidden" id="shared-param" value="<?= $name ?>"/>
