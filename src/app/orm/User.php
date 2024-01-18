<?php

namespace app\orm;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Saves data related to user accounts.
 *
 * @property int              $id          PK
 * @property string           $email       Email address, used to uniquely identify an user's account
 * @property string           $name        Display name, used for UI purposes
 * @property bool             $active      Flag, marks this user account/record as being enabled
 * @property string           $key         Public key value, used for authentication and key cipher
 *
 * @property \app\orm\Vault[] $ownedVaults List of vaults this user owns
 */
final class User extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{users}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnedVaults(): ActiveQuery {
        return $this->hasMany(Vault::class, ['owner_id' => 'id']);
    }
}