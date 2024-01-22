<?php

namespace app\controllers;

use app\filters\Vaults;
use app\orm\User;
use app\orm\Vault;
use app\forms\Vault as Form;
use app\orm\VaultAccess;
use Exception;
use phpseclib3\Crypt\PublicKeyLoader;
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
                    ['actions' => ['index', 'create', 'update', 'delete', 'revoke-access', 'share', 'details'], 'allow' => true, 'roles' => ['@']]
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string {
        $userId = Yii::$app->user->getId();

        $filter = new Vaults($userId);
        $dataProvider = $filter->search(Yii::$app->request->queryParams);
        return $this->render('index', ['provider' => $dataProvider]);
    }

    /**
     * @param int|null $id
     *
     * @return \yii\web\Response
     */
    public function actionDetails(?int $id = null): Response {
        if (!$id) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault.']);
        }

        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity;

        /** @var \app\orm\Vault $vault */
        $vault = Vault::find()->where(['id' => $id, 'owner_id' => $user->id])->one();
        if (!$vault) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault.']);
        }

        return $this->asJson(['ok' => true,
            'id' => $vault->id,
            'description' => $vault->description,
            //'data' => $vault->data,
            'username' => $vault->username,
            'url' => $vault->url,
            'notes' => $vault->notes
        ]);
    }

    /**
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
            $secret = null;

//            $encrypted = null;
//            //TODO: FIX ENCRYPTION!
//            if (!openssl_public_encrypt(Yii::$app->security->generateRandomString(), $encrypted, $user->key)) {
//                $transaction->rollBack();
//                //TDO:...
//            }
//            //TODO: subject to timing attacks
//            $secret = base64_encode($encrypted);

            $vault = new Vault();
            $vault->owner_id = $user->id;
            $vault->description = $description;
            $vault->username = $username ? trim($username) : null;
            $vault->url = $url ? trim($url) : null;
            $vault->notes = $notes ? trim($notes) : null;
            $vault->data = '';

            if (!$vault->save(false)) {
                $transaction->rollBack();
                return $this->asJson(['ok' => false, 'reason' => implode(' ', $vault->getErrorSummary(true))]);
            }

            $access = new VaultAccess();
            $access->user_id = $user->id;
            $access->vault_id = $vault->id;
            $access->secret = ''; //$secret;

            if (!$access->save(false)) {
                $transaction->rollBack();
                return $this->asJson(['ok' => false, 'reason' => implode(' ', $access->getErrorSummary(true))]);
            }

            $transaction->commit();
            return $this->asJson(['ok' => true]);
        } catch (Exception $ex) {
            //TODO: ...
            $transaction->rollBack();
            return $this->asJson(['ok' => false, 'reason' => $ex->getMessage()]);
        }
    }

    /**
     * @return \yii\web\Response|string
     */
    public function actionUpdate(): Response|string {
        $request = Yii::$app->request;
        $id = $request->post('id');
        if (!$id) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault']);
        }

        /** @var \app\orm\Vault $vault */
        $vault = Vault::find()->where(['id' => $id, 'owner_id' => 1, /*$user->id*/])->one();
        if (!$vault) {
            return $this->asJson(['ok' => false, 'reason' => 'Unknown or invalid vault']);
        }

        $data = [
            'Vault[description]' => $request->post('description'),
            'Vault[data]' => $request->post('data'),
            'Vault[username]' => $request->post('username'),
            'Vault[url]' => $request->post('url'),
            'Vault[notes]' => $request->post('notes')
        ];

        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity;
        $form = new Form(Yii::$app->getDb(), $user, $vault);
        if ($form->load($data)) {
            if ($form->save()) {
                return $this->asJson(['ok' => true]);
            }
            return $this->asJson(['ok' => false, 'reason' => implode(' ', $form->getErrorSummary(true))]);
        }

        return $this->asJson(['ok' => false, 'reason' => 'Invalid request']);
    }

    /**
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

        $secret = Yii::$app->request->post('secret');
        if (empty($secret)) {
            return $this->asJson(['ok' => false, 'reason' => '//TODO: ...']);
        }

        //TODO: ....
        //decoded vault secret

        $challenge = strtoupper(hash('sha256', $this->user->email . Yii::$app->security->generateRandomString()));

        $loadedKey = PublicKeyLoader::load($key);
        $ciphered = $loadedKey->withHash('sha256')
            ->withMGFHash('sha256')
            ->encrypt($challenge);

//        $ciphered = null;
//        if (!openssl_public_encrypt($secret, $ciphered, $sharedWith->key)) {
//            return $this->asJson(['ok' => false, 'reason' => 'Encryption error.']);
//        }

        $newAccess = new VaultAccess();
        $newAccess->vault_id = $vid;
        $newAccess->user_id = $sharedWith->id;
        $newAccess->secret = base64_encode($ciphered);
        if (!$newAccess->save(false)) {
            return $this->asJson(['ok' => false, 'message' => $newAccess->getErrorSummary(true)]);
        }

        $userName = $sharedWith->name;
        $vaultDescription = $accessInfo->vault->description;
        return $this->asJson(['ok' => true, 'message' => "Vault '$vaultDescription' shared with $userName."]);
    }
}
