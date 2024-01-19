<?php

namespace app\controllers;

use app\orm\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

final class RegisterController extends Controller {


    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['actions' => ['index', 'store'], 'allow' => true, 'roles' => ['?']]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'store' => ['post']
                ]
            ]
        ];
    }

    /**
     * @return string
     */
    public function actionIndex(): string {
        $this->layout = 'register';
        return $this->render('index');
    }

    /**
     * @return \yii\web\Response
     */
    public function actionStore(): Response {
        $request = Yii::$app->request;
        $name = $request->post('name');
        if (empty($name)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', '"Name" is a mandatory field'), 'field' => 'register-name']);
        }

        $email = $request->post('email');
        if (empty($email)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', '"Email" is a mandatory field'), 'field' => 'register-name']);
        }

        $key = $request->post('key');
        if (empty($key)) {
            return $this->asJson(['ok' => false, 'reason' => Yii::t('app', 'Missing RSA Public Key'), 'field' => 'register-key']);
        }

        $user = new User();
        $user->active = true;
        $user->name = $name;
        $user->email = $email;
        $user->key = $key;

        if (!$user->save(false)) {
            return $this->asJson(['ok' => false, 'reason' => implode(' ', $user->getErrorSummary(true))]);
        }

        //TODO: Register new logged in user
        //Yii::$app->user->login(new UserIdentity())
        return $this->asJson(['ok' => true]);
    }
}