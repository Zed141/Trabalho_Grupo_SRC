<?php

namespace app\controllers;

use app\orm\User;
use app\orm\Vault;
use app\forms\Vault as Form;
use app\orm\VaultAccess;
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

        //TODO: Mover para gridview && provider
        $vaultListQry = Vault::find()
            ->alias('v')
            ->innerJoin(VaultAccess::tableName() . ' AS va', 'v.id = va.vault_id')
            ->where(['va.user_id' => $userId])
            ->asArray();

        return $this->render('index', ['data' => $vaultListQry->all()]);
    }

    /**
     * @return \yii\web\Response|string
     */
    public function actionCreate(): Response|string {
        //TODO: User web != User orm!
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
        //TODO: User web != User orm!
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

        return $this->render('create', [
            'model' => $form
        ]);
    }

    public function actionDelete(int $id) {
        //TODO: ...
    }

    public function actionRevokeAccess(int $vid, int $uid) {
        //TODO: ...
    }

    public function actionShare(int $vid, int $uid) {
        //TODO: ...
        $userId = 1;
        $sharedWith = User::find()->where(['id' => $uid, 'active' => 1])->one();
        if (!$sharedWith) {
            return $this->asJson(['ok' => false, 'message' => 'Unknown or invalid user.']);
        }

        $accessInfo = VaultAccess::find()->where(['vault_id' => $vid, 'user_id' => $userId])->one();
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
