<?php

use yii\db\ActiveRecord;

final class Vault extends ActiveRecord {

    public static function tableName() {
        return '{{vaults}}';
    }

}