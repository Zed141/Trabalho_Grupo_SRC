<?php

namespace app\controllers;

use yii\web\Controller;

final class RegisterController extends Controller {

    public function actionIndex(): string {

        //REGISTO!
        //nome do user, e-mail do user, pública
        //
        // Gerar o para RSA em JS
        //
        //controlador: verificar se o e-mail existe
        //SE NOK -> ERRO
        //SE OK -> $user->new User();
        //  preencher os atributos; $user->name = ...
        //                          $user->email = ...
        //  $user->save();
        //
        //



        return $this->render('index', ['name' => date('Y-m-d H:i:s')]);
    }
}