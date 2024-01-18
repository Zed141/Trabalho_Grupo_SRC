<?php

namespace app\orm;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property int           $id          PK
 * @property string        $description Name, or description, of the vault, used for UI purposes
 * @property int           owner_id     FK, owner ID
 * @property string        $username
 * @property string        $data        Encrypted and encoded data safe in this vault
 * @property string|null   $url         Optional URL where the data is used
 * @property string|null   $notes       Optional notes that add context or other visible/public info about this vault
 *
 * @property \app\orm\User $owner
 */
final class Vault extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{vaults}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner(): ActiveQuery {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

}