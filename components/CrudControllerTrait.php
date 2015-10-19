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
     * Name of the session parameter where to store last create action url
     * @var string
     */
    public $createUrlParam = '__createUrlParam';
    /**
     * Name of the session parameter where to store last index action url
     * @var string
     */
    public $indexUrlParam = '__indexUrlParam';

    /**
     * @param Action $action the action just executed.
     * @param mixed $result the action return result.
     * @return mixed the processed action result.
     */
    public function afterAction($action, $result)
    {
        if ($action->id == 'index') {
            Url::remember('', $this->indexUrlParam);
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
                /** @var Controller | CrudControllerTrait $this */
                return $this->redirect(['update', 'id' => $model->getPrimaryKey()]);
            },
            AdminHtml::ACTION_SAVE_AND_CREATE => function () use ($model) {
                /** @var Controller | CrudControllerTrait $this */
                if (($url = Url::previous($this->createUrlParam))) {
                    Url::remember(null, $this->createUrlParam);
                    return $this->redirect($url);
                }
                return $this->redirect(['create']);
            },
            AdminHtml::ACTION_SAVE_AND_LEAVE => function () use ($model) {
                /** @var Controller | CrudControllerTrait $this */
                if (($url = \Yii::$app->request->get('return'))) {
                    return $this->redirect($url);
                }
                if (($url = Url::previous($this->indexUrlParam))) {
                    Url::remember(null, $this->indexUrlParam);
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