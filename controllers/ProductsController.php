<?php

namespace app\controllers;

use app\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class ProductsController extends ActiveController
{

    public $modelClass = 'app\models\Products';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'auth' => function ($username, $password) {
                $user = User::findByUsername($username);
                if ($user && $user->validatePassword($password, $username)) {
                    return $user;
                }
            }
        ];

        return $behaviors;
    }

}
