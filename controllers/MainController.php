<?php

namespace yz\admin\controllers;

use backend\base\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yz\admin\forms\LoginForm;

/**
 * Class MainController
 * @package yz\admin\controllers\backend
 */
class MainController extends Controller
{
    public function actionLogin()
    {
        $this->layout = '@yz/admin/views/layouts/base';

        $model = new LoginForm();
        if ($model->load($_POST) && $model->login()) {
            return $this->goBack(Url::toRoute('index'));
        } else {
            return $this->render('login', [
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
                'actions' => ['index', 'logout', 'accessDenied'],
                'roles' => ['@'],
            ]
        ], parent::getAccessRules());
    }


}