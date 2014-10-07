<?php

namespace yz\admin\controllers;
use backend\base\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yz\admin\models\SystemEvent;


/**
 * Class SystemEventsController
 * @package \yz\admin\controllers
 */
class SystemEventsController extends Controller
{
    public function actionView($id)
    {
        $event = SystemEvent::findOne([
            'id' => $id,
            'user_id' => \Yii::$app->user->id,
        ]);

        if ($event === null)
            throw new NotFoundHttpException();

        $event->is_viewed = true;
        $event->save();

        $url = $event->url;
        if ($url == null)
            return $this->goBack();
        else
            return $this->redirect($url);
    }

    protected function getAccessRules()
    {
        return ArrayHelper::merge([
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => ['@'],
            ]
        ], parent::getAccessRules());
    }
} 