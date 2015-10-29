<?php

namespace yz\admin\controllers;

use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yz\admin\AdminModuleTrait;
use yz\admin\forms\LoginForm;
use yz\admin\models\User;
use yz\admin\tokens\AdminLoginToken;

/**
 * Class MainController
 * @package yz\admin\controllers\backend
 */
class MainController extends Controller
{
    use AdminModuleTrait;

    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'error') {
            $this->layout = '//base';
        }
        return parent::beforeAction($action);
    }


    public function actionLogin()
    {
        $this->layout = '//base';

        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->goBackAndForget();
        } else {
            return $this->render('login', [
                'loginForm' => $model,
            ]);
        }
    }

    public function actionLoginByToken($id, $token)
    {
        if (self::getAdminModule()->allowLoginViaToken == false) {
            throw new NotFoundHttpException();
        }

        /** @var User $user */
        $user = User::findOne($id);
        if ($user === null) {
            throw new NotFoundHttpException();
        }
        $token = AdminLoginToken::compareToken($user->id, $token);
        if ($token === null) {
            throw new ForbiddenHttpException();
        }
        \Yii::$app->user->login($user);
        return $this->goHome();
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

    /**
     * @param $url
     * @return \yii\web\Response
     */
    public function actionReturn($url)
    {
        Url::remember(null);
        return $this->redirect($url);
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'accessControl' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'error', 'login-by-token'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }

    /**
     * @return \yii\web\Response
     */
    private function goBackAndForget()
    {
        $url = Url::previous();
        Url::remember(null);
        return $this->redirect($url ?: Url::toRoute('index'));
    }
}