<?php

namespace app\orm;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Stores the relationship between a user account and a vault.
 *
 * Each record provides the user that has access, the vault the user has access to and the encoded (base64) and encrypted
 * secret used to decrypt the vault's data.
 *
 * @property int            $user_id  PK
 * @property int            $vault_id PK
 * @property string         $secret   Encoded and encrypted secret, used when accessing a vault's data
 * @property string|null    $nonce    Reserved for future use; may need to be "time based"
 *
 * @property \app\orm\User  $user
 * @property \app\orm\Vault $vault
 **/
final class VaultAccess extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%vault_access}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): ActiveQuery {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVault(): ActiveQuery {
        return $this->hasOne(Vault::class, ['id' => 'vault_id']);
    }
}