<?php

namespace app\controllers;

use app\models\Account;
use Yii;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Main application controller.
 * Handles all generic actions, site and user related.
 */
final class AppController extends Controller {

//    //TODO: Enable once  APP is pre-live
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

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string {
        return $this->render('index');
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionLogin(): Response|string {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('login');
    }

    /**
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionBootstrapLogin(): Response {
        $request = Yii::$app->request;
        $email = $request->post('email');
        if (empty($email)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Email is a required field.')]);
        }

        $account = Account::findByEmail($email);
        if (!$account) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Wrong user or credentials.')]);
        }

        $challenge = $account->generateChallenge();
        return $this->asJson(['ok' => true, 'challenge' => $challenge]);
    }

    /**
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionConfirmLogin(): Response {
        $request = Yii::$app->request;
        $token = $request->post('token');
        if (empty($token)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed.')]);
        }

        $email = $request->post('email');
        if (empty($email)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed.')]);
        }

        $account = Account::findByEmail($email);
        if (!$account) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed.')]);
        }

        if (!$account->isTokenValid($token)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed.')]);
        }

        $user = $account->getUser();
        $user->last_login = date('Y-m-d H:i:s');
        if (!$user->save(false)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed.')]);
        }

        Yii::$app->user->login($account);
        return $this->asJson(['ok' => true]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout(): Response {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionProfile(): string {
        //TODO: Not implemented yet';
        return $this->render('profile');
    }

    public function actionSettings(): string {
        //TODO: Not implemented yet';
        return $this->render('settings');
    }

    public function actionDocumentation(): string {
        //TODO: Not implemented yet';
        return $this->render('documentation');
    }

    public function actionCopyright(): string {
        //TODO: Not implemented yet';
        return $this->render('copyright');
    }

    public function actionChangelog(): string {
        //TODO: Not implemented yet';
        return $this->render('changelog');
    }
}
