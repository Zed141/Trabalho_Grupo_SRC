<?php

namespace app\orm;

use yii\db\ActiveRecord;

/**
 * Stores login details on login tokens, used to store the token during second authentication stage.
 * Token data should be deleted upon successful login, the remaining data can be kept for auditing purposes.
 *
 * @property int    $id                    PK
 * @property int    $user_id               FK owner's ID.
 * @property string $created_at            Date/time of the record's creation.
 * @property bool   $expired               Flag, identifies expired tokens that should not be used.
 * @property string $token                 Token's data.
 * @property string $used_at               Date/time of the successful login action.
 */
final class LoginToken extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%login_tokens}}';
    }
}