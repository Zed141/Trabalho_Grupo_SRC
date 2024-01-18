<?php

namespace app\forms;

use app\orm\User;
use app\orm\VaultAccess;
use Exception;
use Yii;
use yii\db\ActiveQuery;
use app\orm\Vault as Model;
use yii\db\Connection;
use yii\helpers\StringHelper;

/**
 *
 */
final class Vault extends \app\forms\BaseForm {

    private Connection $db;
    private User $user;

    /** @var string|null */
    public ?string $description = null;
    /** @var string|null */
    public ?string $username = null;
    /** @var string|null */
    public ?string $data = null;
    /** @var string|null */
    public ?string $url = null;
    /** @var string|null */
    public ?string $notes = null;

    public function __construct(Connection $db, USer $user, Model $vault = null, array $config = []) {
        $this->db = $db;
        $this->user = $user;
        $this->model = $vault;
        if ($vault) {
            $this->description = $vault->description;
            $this->username = $vault->username;
            $this->url = $vault->url;
            $this->notes = $vault->notes;
        }

        parent::__construct('id', $config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['description'], 'required'],
            [['url', 'notes', 'description', 'username', 'data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'description' => Yii::t('app', 'Description'),
            'url' => Yii::t('app', 'URL'),
            'username' => Yii::t('app', 'Username/Email'),
            'notes' => Yii::t('app', 'Notes'),
            'data' => Yii::t('app', 'Password')
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function save(): bool {
        if (!$this->validate()) {
            return false;
        }

        $transaction = $this->db->beginTransaction();
        try {

            $secret = null;
            $creating = false;
            if (!$this->model) {
                $creating = true;
                $this->model = new Model();
                $this->model->owner_id = $this->user->id;

                $encrypted = null;
                if (!openssl_public_encrypt(Yii::$app->security->generateRandomString(), $encrypted, $this->user->key)) {
                    $transaction->rollBack();
                    return false;
                }
                //TODO: subject to timing attacks
                $secret = StringHelper::base64UrlEncode($encrypted);
            }

            $this->model->description = $this->description;

            $this->model->username = $this->url ? trim($this->username) : null;
            $this->model->url = $this->url ? trim($this->url) : null;
            $this->model->notes = $this->notes ? trim($this->notes) : null;

            if (!$this->model->save(false)) {
                $transaction->rollBack();
                return false;
            }

            if ($creating) {
                $access = new VaultAccess();
                $access->user_id = $this->user->id;
                $access->vault_id = $this->model->id;
                $access->secret = $secret;

                if (!$access->save(false)) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $transaction->commit();
            return true;
        } catch (Exception $ex) {
            //TODO: ...
            $transaction->rollBack();
            return false;
        }
    }
}
