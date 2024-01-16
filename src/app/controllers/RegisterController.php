<?php

namespace app\controllers;

use yii\web\Controller;

final class RegisterController extends Controller {

    public function actionIndex(): string {
        return $this->render('index', ['name' => date('Y-m-d H:i:s')]);
    }
}