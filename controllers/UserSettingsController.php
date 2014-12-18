<?php

namespace yz\admin\controllers;
use backend\base\Controller;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yz\admin\models\UserSetting;


/**
 * Class UserSettingsController
 */
class UserSettingsController extends Controller
{
    protected function getAccessRules()
    {
        return ArrayHelper::merge([
            [
                'allow' => true,
                'actions' => ['set', 'get'],
                'roles' => ['@'],
            ]
        ], parent::getAccessRules());
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