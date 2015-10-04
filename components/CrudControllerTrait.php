<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 21.08.14
 * Time: 23:53
 */

namespace yz\admin\components;

use yii\base\Action;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yz\admin\helpers\AdminHtml;

/**
 * Class CrudControllerTrait
 * @deprecated Use [[yz\admin\traits\CrudTrait]]
 */
trait CrudControllerTrait
{
    /**
     * @param Action $action the action just executed.
     * @param mixed $result the action return result.
     * @return mixed the processed action result.
     */
    public function afterAction($action, $result)
    {
        if ($action->id == 'index') {
            Url::remember();
        }
        return parent::afterAction($action, $result);
    }

    /**
     * @param ActiveRecord $model
     * @param array $actions Custom actions array in the form of
     * ```php
     * 'name' => function() {
     *
     * }
     * ```
     * @param bool $addDefaultActions If true default actions will be added
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    protected function getCreateUpdateResponse($model, $actions = [], $addDefaultActions = true)
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

        if ($addDefaultActions) {
            $actions = array_merge($defaultActions, $actions);
        }
        $actionName = \Yii::$app->request->post(AdminHtml::ACTION_BUTTON_NAME, AdminHtml::ACTION_SAVE_AND_LEAVE);

        if (isset($actions[$actionName]))
            return call_user_func($actions[$actionName]);
        else
            throw new BadRequestHttpException('Unknown action: ' . $actionName);
    }
} 