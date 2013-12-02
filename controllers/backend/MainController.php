<?php

namespace yz\admin\controllers\backend;

use yii\helpers\ArrayHelper;
use yz\admin\components\BackendController;
use yz\admin\models\LoginForm;

/**
 * Class MainController
 * @package yz\admin\controllers\backend
 */
class MainController extends BackendController
{
    public function actionLogin()
    {
        $this->layout = '@yz/admin/views/layouts/base';

        $model = new LoginForm();
        if($model->load($_POST) && $model->login()) {
            return $this->goBack(['index']);
        } else {
            return $this->render('login',[
                'loginForm' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionAccessDenied()
    {
        return $this->render('accessDenied');
    }

    protected function getAccessRules()
    {
        return ArrayHelper::merge([
            [
                'allow' => true,
                'actions' => ['login'],
            ],
            [
                'allow' => true,
                'actions' => ['index','logout','accessDenied'],
                'roles' => '@',
            ]
        ],parent::getAccessRules());
    }


}