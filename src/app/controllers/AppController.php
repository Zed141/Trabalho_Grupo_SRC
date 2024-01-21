<?php

namespace app\controllers;

use app\models\Account;
use app\orm\LoginToken;
use app\orm\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Main application controller.
 * Handles all generic actions, site and user related.
 */
final class AppController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['actions' => ['documentation', 'copyright', 'changelog'], 'allow' => true, 'roles' => ['*']],
                    ['actions' => ['profile', 'settings', 'logout'], 'allow' => true, 'roles' => ['@']],
                    ['actions' => ['login', 'get-public-pem', 'bootstrap-login', 'confirm-login'], 'allow' => true, 'roles' => ['*']]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
    public function actionStartLogin(): Response {
        $request = Yii::$app->request;
        $email = $request->post('email');
        if (empty($email)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Email is a required field.')]);
        }

        $account = Account::findByEmail($email);
        if (!$account) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Wrong user or credentials.')]);
        }

        [$challenge, $ciphered] = $account->generateChallenge();

        $token = new LoginToken();
        $token->user_id = $account->getId();
        $token->created_at = date('Y-m-d H:i:s');
        $token->expired = false;
        $token->token = $challenge;
        if (!$token->save(false)) {
            return $this->asJson(['ok' => false, 'reason' => 'Internal Error.']);
        }

        return $this->asJson(['ok' => true, 'challenge' => base64_encode($ciphered)]);
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

    public function actionGetPublicPem(): Response {
        $request = Yii::$app->request;
        $email = $request->post('email');
        $user = User::find()->where(['email' => $email])->one();
        if ($user) {
            return $this->asJson(['ok' => true, 'publicKeyPEM' => $user->key]);
        } else {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Wrong Credentials.')]);
        }
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
