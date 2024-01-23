<?php

namespace app\models;

use app\orm\LoginToken;
use app\orm\User;
use Exception;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use Yii;
use yii\base\BaseObject;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * @property integer $id
 */
final class Account extends BaseObject implements IdentityInterface {

    private ?User $user;

    public function getUser(): ?User {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        if (!$id) {
            return null;
        }

        $user = User::findOne(['id' => (int)$id, 'active' => true]);
        if (!$user) {
            return null;
        }

        $account = new self();
        $account->user = $user;
        return $account;
    }

    /**
     * {@inheritdoc}
     * @throws \yii\base\NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('Method is not supported.');
    }

    /**
     * @param string $email
     *
     * @return \app\orm\User|null
     */
    public static function findByEmail(string $email): ?Account {
        if (!$email) {
            return null;
        }

        $user = User::findOne(['email' => $email, 'active' => true]);
        if (!$user) {
            return null;
        }

        $account = new self();
        $account->user = $user;
        return $account;
    }

    /**
     * @param \app\orm\User $user
     *
     * @return \app\models\Account
     */
    public static function factoryFromUser(User $user): Account {
        $account = new self();
        $account->user = $user;
        return $account;
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->user?->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string {
        return $this->user?->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return false;
    }

    /**
     * @return array|null
     * @throws \yii\base\Exception
     */
    public function generateChallenge(): ?array {
        if (!$this->user) {
            return null;
        }

        $key = $this->user->key;
        $challenge = strtoupper(hash('sha256', $this->user->email . Yii::$app->security->generateRandomString()));

        $loadedKey = PublicKeyLoader::load($key);
        $ciphered = $loadedKey->withHash('sha256')
            ->withMGFHash('sha256')
            ->encrypt($challenge);

        return [$challenge, base64_encode($ciphered)];
    }

    /**
     * @param string $token
     *
     * @return bool
     * @throws \Exception
     */
    public function isTokenValid(string $token): bool {
        if (!$this->user) {
            throw new Exception('Invalid user authentication.');
        }

        /** @var \app\orm\LoginToken $login */
        $login = LoginToken::find()
            ->where(['expired' => false, 'token' => $token, 'user_id' => $this->user->id])
            ->one();

        if (!$login || $login->token != $token) {
            return false;
        }

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');
            $this->user->last_login = $now;
            if (!$this->user->save(false)) {
                $transaction->rollBack();
                return false;
            }

            $login->token = null;
            $login->used_at = $now;
            if (!$login->save(false)) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        } catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }
}
