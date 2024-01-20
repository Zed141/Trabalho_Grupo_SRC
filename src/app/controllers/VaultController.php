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
//                'only' => ['logout', 'signup'],
//                'rules' => [
//                    ['actions' => ['logout'], 'allow' => true, 'roles' => ['@'],],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::class,
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    public function actionIndex(): string {
        //TODO: $user = Yii::$app->user->getId();
        $userId = 1;

        $filter = new Vaults();
        $dataProvider = $filter->search(Yii::$app->request->queryParams);
        return $this->render('index', ['provider' => $dataProvider]);
    }

    /**
     * @return \yii\web\Response|string
     */
    public function actionCreate(): Response|string {
        //TODO: rewrite,it should be AJAX based!
        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity;
        $form = new Form(Yii::$app->getDb(), $user);
        if ($form->load(Yii::$app->request->post())) {
            if ($form->save()) {
                //TODO: show message to user
                return $this->redirect(['update', 'id' => $form->getId()]);
            }
            //TODO: show message to user
        }

        return $this->render('create', [
            'model' => $form
        ]);
    }

    /**
     * @param int|null $id
     *
     * @return \yii\web\Response|string
     */
    public function actionUpdate(?int $id = null): Response|string {
        //TODO: rewrite,it should be AJAX based!
        /** @var \app\orm\User $user */
        $user = Yii::$app->user->identity;
        if (!$id) {
            //TODO: ... error
            return $this->redirect(['index']);
        }

        /** @var \app\orm\Vault $vault */
        $vault = Vault::find()->where(['id' => $id, 'owner_id' => $user->id])->one();
        if (!$vault) {
            //TODO: ... error
            return $this->redirect(['index']);
        }

        $form = new Form(Yii::$app->getDb(), $user, $vault);
        if ($form->load(Yii::$app->request->post())) {
            if ($form->save()) {
                //TODO: show message to user
                return $this->redirect(['update', 'id' => $form->getId()]);
            }
            //TODO: show message to user
        }

        return $this->render('update', [
            'model' => $form
        ]);
    }

    public function actionDelete(int $id) {
        //TODO: ...
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

        $newAccess = new VaultAccess();
        $newAccess->vault_id = $vid;
        $newAccess->user_id = $sharedWith->id;
        //TODO: ...
        $newAccess->secret = 'DUMMY';
        if (!$newAccess->save(false)) {
            return $this->asJson(['ok' => false, 'message' => $newAccess->getErrorSummary(true)]);
        }

        //TODO: ....
        //new user ID
        //decoded vault secret

        $vaultDescription = $accessInfo->vault->description;
        $userName = 'TMP';
        return $this->asJson(['ok' => true, 'message' => "Vault '$vaultDescription' shared with $userName."]);
    }
}
