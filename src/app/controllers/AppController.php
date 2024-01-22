<?php

namespace app\controllers;

use app\models\Account;
use app\orm\LoginToken;
use app\orm\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
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
                    ['actions' => ['profile', 'settings', 'logout', 'index'], 'allow' => true, 'roles' => ['@']],
                    ['actions' => ['login', 'get-public-pem', 'start-login', 'confirm-login'], 'allow' => true, 'roles' => ['?']]
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => ErrorAction::class
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
     * Shows login page with authentication UI.
     *
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
     * Handles first stage of the login process, creating the required challenge and saving login token's details.
     *
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

        [$challengeToken, $challengeData] = $account->generateChallenge();

        $token = new LoginToken();
        $token->user_id = $account->getId();
        $token->created_at = date('Y-m-d H:i:s');
        $token->expired = false;
        $token->token = $challengeToken;
        if (!$token->save(false)) {
            return $this->asJson(['ok' => false, 'reason' => 'Internal Error.']);
        }

        return $this->asJson(['ok' => true, 'challenge' => $challengeData]);
    }

    /**
     * Validates the token sent by the user. If the token is valid, the user has the private key and can login into
     * the system.
     *
     * @return \yii\web\Response
     * @throws \Exception
     */
    public function actionConfirmLogin(): Response {
        $request = Yii::$app->request;
        $token = $request->post('token');
        if (empty($token)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed, missing token.')]);
        }

        $email = $request->post('email');
        if (empty($email)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed, missing email.')]);
        }

        $account = Account::findByEmail($email);
        if (!$account) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed, account not found.')]);
        }

        if (!$account->isTokenValid($token)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed token is invalid.')]);
        }

        $user = $account->getUser();
        $user->last_login = date('Y-m-d H:i:s');
        if (!$user->save(false)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Login failed, internal error.')]);
        }

        Yii::$app->user->login($account);
        Yii::$app->session->set('token', $token);
        return $this->asJson(['ok' => true, 'to' => Url::to(['/app/index'])]);
    }

    public function actionGetPublicPem(): Response {
        //TODO: Validar necessidade
        $request = Yii::$app->request;
        $email = $request->post('email');

        /** @var \app\orm\User $user */
        $user = User::find()->where(['email' => $email])->one();
        if ($user) {
            return $this->asJson(['ok' => true, 'publicKeyPEM' => $user->key]);
        }

        return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Wrong Credentials.')]);
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
