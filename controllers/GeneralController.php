<?php

namespace yz\admin\controllers;

use backend\base\Controller;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotAcceptableHttpException;
use yii\web\Response;
use yz\admin\forms\GridViewSettingsForm;
use yz\admin\helpers\OpCacheDataModel;


/**
 * Class GeneralController
 * @package \yz\admin\controllers
 */
class GeneralController extends Controller
{
    protected function getAccessRules()
    {
        return ArrayHelper::merge([
            [
                'allow' => true,
                'actions' => ['grid-view-settings'],
                'roles' => ['@'],
            ]
        ], parent::getAccessRules());
    }

    public function actionInfo()
    {
        return $this->render('info');
    }

    public function actionOpCache()
    {
        $this->layout = false;
        $dataModel = new OpCacheDataModel();
        return $this->renderPartial('op-cache', [
            'dataModel' => $dataModel
        ]);
    }

    public function actionRouteToUrl()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $route = \Yii::$app->request->get('route');
        if ($route === null)
            throw new NotAcceptableHttpException();

        if (is_string($route)) {
            switch ($route) {
                case 'home':
                    return ['success' => true, 'url' => Url::home()];
                default:
                    throw new NotSupportedException();
            }
        } else {
            if (ArrayHelper::isIndexed($route)) {
                $url = Url::to($route);
            } else {
                $url = Url::to(array_merge(
                    [ArrayHelper::getValue($route, 'route', '')],
                    ArrayHelper::getValue($route, 'params', [])
                ));
            }
            return ['success' => true, 'url' => $url];
        }
    }

    public function actionGridViewSettings()
    {
        $params = \Yii::$app->request->get('data');
        if ($params === null) {
            throw new NotAcceptableHttpException();
        }

        $model = new GridViewSettingsForm();
        $model->userId = \Yii::$app->user->id;
        $model->gridId = ArrayHelper::getValue($params, 'griduniqueid');
        $model->pageSizeValues = ArrayHelper::getValue($params, 'pagesizes', [20, 30, 50, 100]);
        $model->pageSize = ArrayHelper::getValue($params, 'currentpagesize', 20);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(\Yii::$app->request->referrer);
        }

        return $this->renderAjax('grid-view-settings', [
            'model' => $model,
        ]);
    }
} 