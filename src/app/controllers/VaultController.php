<?php

namespace app\controllers;

use app\filters\Vaults;
use app\orm\User;
use app\orm\Vault;
use app\forms\Vault as Form;
use app\orm\VaultAccess;
use Exception;
use Yii;
use yii\web\Controller;
use yii\web\Response;

final class VaultController extends Controller {

//    //TODO: Enable once APP is pre-live
//    /**
//     * {@inheritdoc}
//     */
//    public function behaviors() {
//        return [
//            'access' => [
//                'class' => AccessControl::class,
//                'rules' => [
//                    ['actions' => ['index', 'create', 'update', 'delete', 'revoke-access', 'share'], 'allow' => true, 'roles' => ['@'],],
//                ],
//            ]
//        ];
//    }

    /**
     * @return string
     */
    public function actionIndex(): string {
        //TODO: $user = Yii::$app->user->getId();
        $userId = 1;

        $filter = new Vaults();
        $dataProvider = $filter->search(Yii::$app->request->queryParams);
        return $this->render('index', ['provider' => $dataProvider]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionCreate(): Response {
        $request = Yii::$app->request;
        $data = [
            'Vault[description]' => $request->post('description'),
            'Vault[data]' => $request->post('data'),
            'Vault[username]' => $request->post('username'),
            'Vault[url]' => $request->post('url'),
            'Vault[notes]' => $request->post('notes')
        ];

        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity;
        $form = new Form(Yii::$app->getDb(), $user);
        if ($form->load($data)) {
            if ($form->save()) {
                return $this->asJson(['ok' => true]);
            }
            return $this->asJson(['ok' => false, 'reason' => implode(' ', $form->getErrorSummary(true))]);
        }

        return $this->asJson(['ok' => false, 'reason' => 'Invalid request']);
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

        //TODO: Fix login process!
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
        //TODO: $user = Yii::$app->user->identity;

        /** @var \app\orm\Vault $vault */
        //TODO: Fix login process!
        $vault = Vault::find()->where(['id' => $id, 'owner_id' => 1, /*$user->id*/])->one();
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

        /** @var \app\orm\VaultAccess $accessInfo */
        $accessInfo = VaultAccess::find()
            ->where([
                'vault_id' => $vid,
                'user_id' => Yii::$app->user->identity->getId()
            ])
            ->one();

        if (!$accessInfo) {
            return $this->asJson(['ok' => false, 'message' => 'Unknown or invalid vault.']);
        }

        //TODO: ....
        //decoded vault secret
        $secret = 'DUMMY'; //TODO: secret from client in post data

        $ciphered = null;
        if (!openssl_public_encrypt($secret, $ciphered, $sharedWith->key)) {
            return $this->asJson(['ok' => false, 'reason' => 'Encryption error.']);
        }

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
