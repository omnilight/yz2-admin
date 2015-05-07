<?php

namespace yz\admin\actions;
use yii\base\Action;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yz\db\ActiveRecord;


/**
 * Class ToggleAction
 */
class ToggleAction extends Action
{
    /**
     * @var string
     */
    public $modelClass;
    /**
     * @var \Closure Closure that should have notation
     * ```php
     * function ($id) {
     *
     * }
     * ```
     * and returns instance of the updated model
     */
    public $modelFinder;
    /**
     * @var array Attributes that are allowed to be processed with this action
     */
    public $attributes = [];
    /**
     * @var mixed
     */
    public $onValue = 1;
    /**
     * @var mixed
     */
    public $offValue = 0;
    /**
     * @var \Closure Format:
     * ```php
     * function ($model, $attribute, $newValue, $oldValue) {
     *
     * }
     * Can return false if processing should be done
     */
    public $beforeToggle;
    /**
     * @var \Closure Format:
     * ```php
     * function ($model, $attribute, $newValue, $oldValue) {
     *
     * }
     */
    public $afterToggle;

    public function run($id, $attribute)
    {
        if (!in_array($attribute, $this->attributes)) {
            throw new BadRequestHttpException();
        }

        if ($this->modelFinder) {
            $model = call_user_func($this->modelFinder, $id);
        } else {
            /** @var ActiveRecord $modelClass */
            $modelClass = $this->modelClass;
            $model =  $modelClass::findOne($id);
        }

        /** @var ActiveRecord $model */
        if ($model === null) {
            throw new NotFoundHttpException();
        }

        $oldValue = $model->{$attribute};

        if ($oldValue == $this->onValue) {
            $newValue = $this->offValue;
        } else {
            $newValue = $this->onValue;
        }

        if ($this->beforeToggle) {
            $process = call_user_func($this->beforeToggle, $model, $attribute, $newValue, $oldValue);
        } else {
            $process = true;
        }

        if ($process) {

            $model->{$attribute} = $newValue;
            $model->save(true, [$attribute]);

            if ($this->afterToggle) {
                call_user_func($this->beforeToggle, $model, $attribute, $newValue, $oldValue);
            }
        }

        if (\Yii::$app->request->isAjax) {
            return null;
        }

        return $this->controller->redirect(\Yii::$app->request->getReferrer());
    }
}