<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 21.08.14
 * Time: 23:53
 */

namespace yz\admin\components;

use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

trait CrudControllerTrait
{
    /**
     * @param ActiveRecord $model
     * @param array $actions
     * @throws BadRequestHttpException
     * @return \yii\web\Response
     */
    protected function getCreateUpdateResponse($model, $actions = [])
    {
        /** @var Controller $this */
        $me = $this;
        $defaultActions = [
            'save_and_stay' => function () use ($model, $me) {
                    return $me->redirect(['update', 'id' => $model->getPrimaryKey()]);
                },
            'save_and_create' => function () use ($model, $me) {
                    return $me->redirect(['create']);
                },
            'save_and_leave' => function () use ($model, $me) {
                    return $me->redirect(['index']);
                },
        ];

        $actions = array_merge($defaultActions, $actions);
        $actionName = \Yii::$app->request->post('__action', 'save_and_leave');

        if (isset($actions[$actionName]))
            return call_user_func($actions[$actionName]);
        else
            throw new BadRequestHttpException('Unknown action: ' . $actionName);
    }
} 