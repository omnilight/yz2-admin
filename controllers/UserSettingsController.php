<?php

namespace yz\admin\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yz\admin\contracts\AccessControlInterface;
use yz\admin\models\UserSetting;


/**
 * Class UserSettingsController
 */
class UserSettingsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'accessControl' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['set', 'get'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                    ]
                ]
            ]
        ]);
    }

    public function actionSet($name, $value)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        UserSetting::set(\Yii::$app->user->id, $name, $value);

        return ['result' => true];
    }

    public function actionGet($name)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return UserSetting::get(\Yii::$app->user->id, $name);
    }
}