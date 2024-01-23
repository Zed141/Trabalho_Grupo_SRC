<?php

namespace app\controllers;

use app\filters\Vaults;
use app\orm\User;
use app\orm\Vault;
use app\forms\Vault as Form;
use app\orm\VaultAccess;
use Exception;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\Random;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

final class VaultController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['actions' => [
                        'index', 'create', 'update', 'delete', 'revoke-access', 'share', 'details', 'available-user-list', 'vault-secret'
                    ], 'allow' => true, 'roles' => ['@']]
                ]
            ]
        ];
    }

    /**
     * Lists existing vaults the user has access to.
     *
     * @return string
     */
    public function actionIndex(): string {
        $userId = Yii::$app->user->getId();

        $filter = new Vaults($userId);
        $dataProvider = $filter->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'provider' => $dataProvider,
            'userId' => $userId
        ]);
    }

    /**
     * Returns a vault' secret information, needed for subsequent calls to the vault details.
     *
     * @param int|null $id
     *
     * @return \yii\web\Response
     */
    public function actionVaultSecret(?int $id = null): Response {
        if (!$id) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault.']);
        }

        /** @var \app\models\Account $account */
        $account = Yii::$app->user->identity;
        $email = $account->getEmail();

        /** @var \app\orm\Vault $vaultAccess */
        $vaultAccess = VaultAccess::find()->where(['vault_id' => $id, 'user_id' => $account->getId()])->one();
        if (!$vaultAccess) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault.']);
        }

        return $this->asJson([
            'ok' => true,
            'nonce' => $vaultAccess->nonce,
            'secret' => $vaultAccess->secret,
            'tag' => $vaultAccess->tag,
            'email' => $email
        ]);
    }

    /**
     * Loads a vault's details and encryption information.
     *
     * @param int|null $id
     *
     * @return \yii\web\Response
     */
    public function actionDetails(?int $id = null): Response {
        if (!$id) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault.']);
        }

        $request = Yii::$app->request;
        $nonce = base64_decode($request->post('nonce'));
        $tag = base64_decode($request->post('tag'));
        $secret = base64_decode($request->post('secret'));

        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity->getUser();

        /** @var \app\orm\Vault $vault */
        $vault = Vault::find()->where(['id' => $id, 'owner_id' => $user->id])->one();
        if (!$vault) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault.']);
        }

        $dataEncrypted = base64_decode($vault->data);

        $vaultAES = new AES('gcm');
        $vaultAES->setNonce($nonce);
        $vaultAES->setKey($secret);
        $vaultAES->setTag($tag);
        $dataDecrypted = $vaultAES->decrypt($dataEncrypted);

        $description = $vault->description;
        $username = $vault->username;
        $url = $vault->url;
        $notes = $vault->notes;

        return $this->asJson(['ok' => true,
            'data' => $dataDecrypted,
            'description' => $description,
            'username' => $username,
            'url' => $url,
            'notes' => $notes
        ]);
    }

    /**
     * Registers a new vault in the system (and database).
     *
     * @return \yii\web\Response
     */
    public function actionCreate(): Response {
        $request = Yii::$app->request;
        $description = $request->post('description');
        $data = $request->post('data');

        $username = $request->post('username');
        $url = $request->post('url');
        $notes = $request->post('notes');

        if (empty($description)) {
            return $this->asJson(['ok' => false, 'reason' => 'Description is a required field.']);
        }

        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity->getUser();
        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            //1: Dados do user (password) cifrados com AES, modo GCM
            //2: Chave AES  cifrada com chave pública do user
            //3: Nonce cifrado com chave pública do user

            $key = $user->key;
            $aesSecret = Yii::$app->security->generateRandomString(16);
            $nonce = Random::string(16);

            $loadedKey = PublicKeyLoader::load($key);
            $cipheredAESSecret = $loadedKey->withHash('sha256')
                ->withMGFHash('sha256')
                ->encrypt($aesSecret);

            $cipheredNonce = $loadedKey->withHash('sha256')
                ->withMGFHash('sha256')
                ->encrypt($nonce);

            $vaultAES = new AES('gcm');
            $vaultAES->setNonce($nonce);
            $vaultAES->setKey($aesSecret);
            $vaultData = $vaultAES->encrypt($data);

            $tag = $vaultAES->getTag();
            $cipheredTag = $loadedKey->withHash('sha256')
                ->withMGFHash('sha256')
                ->encrypt($tag);

            $vault = new Vault();
            $vault->owner_id = $user->id;
            $vault->description = $description;
            $vault->username = $username ? trim($username) : null;
            $vault->url = $url ? trim($url) : null;
            $vault->notes = $notes ? trim($notes) : null;
            $vault->data = base64_encode($vaultData);

            if (!$vault->save(false)) {
                $transaction->rollBack();
                return $this->asJson(['ok' => false, 'reason' => implode(' ', $vault->getErrorSummary(true))]);
            }

            $access = new VaultAccess();
            $access->user_id = $user->id;
            $access->vault_id = $vault->id;
            $access->secret = base64_encode($cipheredAESSecret);
            $access->nonce = base64_encode($cipheredNonce);
            $access->tag = base64_encode($cipheredTag);

            if (!$access->save(false)) {
                $transaction->rollBack();
                return $this->asJson(['ok' => false, 'reason' => implode(' ', $access->getErrorSummary(true))]);
            }

            $transaction->commit();
            return $this->asJson(['ok' => true]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            return $this->asJson(['ok' => false, 'reason' => $ex->getMessage()]);
        }
    }

    /**
     * Updates a vault's details.
     *
     * @return \yii\web\Response
     */
    public function actionUpdate(): Response {
        $request = Yii::$app->request;

        $id = (int)$request->post('id');
        if (!$id) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault ID.']);
        }

        $description = $request->post('description');
        $data = $request->post('data');

        $username = $request->post('username');
        $url = $request->post('url');
        $notes = $request->post('notes');

        $nonce = base64_decode($request->post('nonce'));
        $tag = base64_decode($request->post('tag'));
        $secret = base64_decode($request->post('secret'));

        if (empty($description)) {
            return $this->asJson(['ok' => false, 'reason' => 'Description is a required field.']);
        }

        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity->getUser();

        /** @var \app\orm\Vault $vault */
        $vault = Vault::find()
            ->alias('v')
            ->innerJoin(VaultAccess::tableName() . ' AS va', 'v.id = va.vault_id')
            ->where(['va.user_id' => $user->id, 'vault_id' => $id])
            ->one();

        if (!$vault) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault.']);
        }

        $vaultAES = new AES('gcm');
        $vaultAES->setNonce($nonce);
        $vaultAES->setKey($secret);
        $vaultAES->setTag($tag);
        $decryptedData = $vaultAES->decrypt(base64_decode($vault->data));

        if ($data != $decryptedData) {
            $vault->data = base64_encode($vaultAES->encrypt($data));
        }

        $vault->description = $description;
        $vault->username = $username ? trim($username) : null;
        $vault->url = $url ? trim($url) : null;
        $vault->notes = $notes ? trim($notes) : null;
        if (!$vault->save(false)) {
            return $this->asJson(['ok' => false, 'reason' => implode(' ', $vault->getErrorSummary(true))]);
        }

        return $this->asJson(['ok' => true]);
    }

    /**
     * Removes a vault from the system (and database).
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \Throwable
     */
    public function actionDelete(int $id): Response {
        if (!$id) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault']);
        }

        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity;

        /** @var \app\orm\Vault $vault */
        $vault = Vault::find()->where(['id' => $id, 'owner_id' => 1, $user->id])->one();
        if (!$vault) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault']);
        }

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            VaultAccess::deleteAll(['vault_id' => $id]);
            if ($vault->delete() === false) {
                $transaction->rollBack();
                return $this->asJson(['ok' => false, 'reason' => implode(' ', $vault->getErrorSummary(true))]);
            }

            $transaction->commit();
            return $this->asJson(['ok' => true, 'message' => Yii::t('app', 'Vault deleted successfully.')]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            return $this->asJson(['ok' => false, 'reason' => $ex->getMessage()]);
        }
    }

    /**
     * Removes access to a vault, from the specified user. The user making the call has to own the vault.
     *
     * @param int $vid Vault ID
     * @param int $uid ID of the user being revoked.
     *
     * @return \yii\web\Response
     */
    public function actionRevokeAccess(int $vid, int $uid): Response {
        $vault = Vault::find()->where(['id' => $vid, 'owner_id' => Yii::$app->user->identity->getId()])->one();
        if (!$vault) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Unknown or invalid vault.')]);
        }

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            VaultAccess::deleteAll(['user_id' => $uid, 'vault_id' => $vid]);
            $transaction->commit();

            return $this->asJson(['ok' => true]);
        } catch (Exception $ex) {
            $transaction->rollBack();
            return $this->asJson(['ok' => false, 'reason' => $ex->getMessage()]);
        }
    }

    /**
     * Lists available users that a vault can be shared with.
     *
     * @return \yii\web\Response
     */
    public function actionAvailableUserList(): Response {
        $usersQry = User::find()->select(['id', 'name', 'email'])
            ->where(['active' => true])
            ->andWhere('id <> :id', [':id' => Yii::$app->user->getId()])
            ->orderBy(['name' => SORT_ASC])
            ->asArray();

        $users = [];
        foreach ($usersQry->all() as $row) {
            //TODO: user image
            $avatarContent = '';

            $pieces = explode(' ', $row['name']);
            $avatarContent = strtoupper(substr($pieces[0], 0, 1));
            if (count($pieces) > 1) {
                $avatarContent = strtoupper(substr(reset($pieces), 0, 1));
            }

            $users[] = (object)[
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email'],
                'avatar' => (object)[
                    'img' => false,
                    'content' => $avatarContent
                ]
            ];
        }

        return $this->asJson(['ok' => true, 'users' => $users]);
    }

    /**
     * Shares a vault with a specific user.
     *
     * @param int $vid
     * @param int $uid
     *
     * @return \yii\web\Response
     */
    public function actionShare(int $vid, int $uid): Response {
        /** @var \app\orm\User $sharedWith */
        $sharedWith = User::find()->where(['id' => $uid, 'active' => 1])->one();
        if (!$sharedWith) {
            return $this->asJson(['ok' => false, 'message' => 'Unknown or invalid user.']);
        }

        /** @var \app\models\Account $account */
        $account = Yii::$app->user->identity;

        /** @var \app\orm\VaultAccess $accessInfo */
        $accessInfo = VaultAccess::find()
            ->where([
                'vault_id' => $vid,
                'user_id' => $account->getId()
            ])
            ->one();

        if (!$accessInfo) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault.']);
        }

        $request = Yii::$app->request;
        $nonce = base64_decode($request->post('nonce'));
        $tag = base64_decode($request->post('tag'));
        $secret = base64_decode($request->post('secret'));

        $key = $sharedWith->key;
        $loadedKey = PublicKeyLoader::load($key);
        $cipheredAESSecret = $loadedKey->withHash('sha256')
            ->withMGFHash('sha256')
            ->encrypt($secret);

        $cipheredNonce = $loadedKey->withHash('sha256')
            ->withMGFHash('sha256')
            ->encrypt($nonce);

        $cipheredTag = $loadedKey->withHash('sha256')
            ->withMGFHash('sha256')
            ->encrypt($tag);

        $access = new VaultAccess();
        $access->user_id = $sharedWith->id;
        $access->vault_id = $accessInfo->vault_id;
        $access->secret = base64_encode($cipheredAESSecret);
        $access->nonce = base64_encode($cipheredNonce);
        $access->tag = base64_encode($cipheredTag);

        if (!$access->save(false)) {
            return $this->asJson(['ok' => false, 'message' => $access->getErrorSummary(true)]);
        }

        $userName = $sharedWith->name;
        $vaultDescription = $accessInfo->vault->description;
        return $this->asJson(['ok' => true, 'message' => "Vault '$vaultDescription' shared with $userName."]);
    }
}
