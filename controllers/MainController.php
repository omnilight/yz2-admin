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
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        $this->layout = '//base';

        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
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
        $this->redirect(['login']);
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
                'actions' => ['index', 'logout', 'access-denied'],
                'roles' => ['@'],
            ]
        ], parent::getAccessRules());
    }
}