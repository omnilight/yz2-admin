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
use yz\admin\helpers\AdminHtml;

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
            AdminHtml::ACTION_SAVE_AND_STAY => function () use ($model, $me) {
                    return $me->redirect(['update', 'id' => $model->getPrimaryKey()]);
                },
            AdminHtml::ACTION_SAVE_AND_CREATE => function () use ($model, $me) {
                    return $me->redirect(['create']);
                },
            AdminHtml::ACTION_SAVE_AND_LEAVE => function () use ($model, $me) {
                    return $me->redirect(['index']);
                },
        ];

        $actions = array_merge($defaultActions, $actions);
        $actionName = \Yii::$app->request->post(AdminHtml::ACTION_BUTTON_NAME, AdminHtml::ACTION_SAVE_AND_LEAVE);

        if (isset($actions[$actionName]))
            return call_user_func($actions[$actionName]);
        else
            throw new BadRequestHttpException('Unknown action: ' . $actionName);
    }
} 