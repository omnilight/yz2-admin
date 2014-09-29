<?php

namespace yz\admin\controllers;

use backend\base\Controller;
use yii\base\NotSupportedException;
use yii\helpers\Url;
use yii\web\NotAcceptableHttpException;
use yii\web\Response;


/**
 * Class GeneralController
 * @package \yz\admin\controllers
 */
class GeneralController extends Controller
{
    public function actionInfo()
    {
        return $this->render('info');
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
            return ['success' => true, 'url' => Url::to($route)];
        }
    }
} 