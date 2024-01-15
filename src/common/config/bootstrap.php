<?php
$parent = dirname(__DIR__, 2);

Yii::setAlias('@container', $parent);
Yii::setAlias('@bin', $parent . '/bin');
Yii::setAlias('@bin-runtime', $parent . '/bin/runtime');
Yii::setAlias('@common', $parent . '/common');
Yii::setAlias('@console', $parent . '/console');
Yii::setAlias('@app', $parent . '/app');
Yii::setAlias('@public', $parent . '/public');
