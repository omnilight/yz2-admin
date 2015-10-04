<?php
namespace yz\admin\traits;

use yii\base\Action;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yz\admin\helpers\AdminHtml;

trait CrudTrait
{
    public $createUrlParam = '__createUrlParam';

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
        if ($action->id == 'create') {
            Url::remember('', $this->createUrlParam);
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
        $defaultActions = [
            AdminHtml::ACTION_SAVE_AND_STAY => function () use ($model) {
                /** @var Controller | CrudTrait $this */
                return $this->redirect(['update', 'id' => $model->getPrimaryKey()]);
            },
            AdminHtml::ACTION_SAVE_AND_CREATE => function () use ($model) {
                /** @var Controller | CrudTrait $this */
                if (($url = Url::previous($this->createUrlParam))) {
                    Url::remember(null, $this->createUrlParam);
                    return $this->redirect($url);
                }
                return $this->redirect(['create']);
            },
            AdminHtml::ACTION_SAVE_AND_LEAVE => function () use ($model) {
                /** @var Controller | CrudTrait $this */
                if (($url = Url::previous())) {
                    Url::remember(null);
                    return $this->redirect($url);
                }
                return $this->redirect(['index']);
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