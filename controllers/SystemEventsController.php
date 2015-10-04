<?php

namespace yz\admin\controllers;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yz\admin\models\SystemEvent;
use yz\admin\traits\CheckAccessTrait;


/**
 * Class SystemEventsController
 * @package \yz\admin\controllers
 */
class SystemEventsController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'accessControl' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@'],
                    ]
                ]
            ]
        ]);
    }


    public function actionView($id)
    {
        $event = $this->findModel($id);

        $event->is_viewed = true;
        $event->save();

        $url = $event->url;
        if ($url == null)
            return $this->goBack();
        else
            return $this->redirect($url);
    }

    /**
     * @param $id
     * @return SystemEvent
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        /** @var SystemEvent $event */
        $event = SystemEvent::findOne([
            'id' => $id,
            'user_id' => \Yii::$app->user->id,
        ]);

        if ($event === null) {
            throw new NotFoundHttpException();
        }
        return $event;
    }
} 