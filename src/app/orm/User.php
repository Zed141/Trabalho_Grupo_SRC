<?php

use yii\db\ActiveRecord;

final class User extends ActiveRecord {

    public static function tableName() {
        return '{{users}}';
    }

}