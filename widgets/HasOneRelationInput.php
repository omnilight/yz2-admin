<?php

namespace yz\admin\widgets;

use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use yz\db\ActiveQuery;
use yz\interfaces\ModelInfoInterface;

/**
 * Class HasOneRelationInput
 * @package yz\admin\widgets
 */
class HasOneRelationInput extends InputWidget
{
    public $items;
    public $options = [];

    public function init()
    {
        parent::init();

        if ($this->hasModel() == false) {
            throw new InvalidConfigException('This widget can be used only with $model parameter passed');
        }

        if (!($this->model instanceof ModelInfoInterface)) {
            throw new Exception('Model must implement ModelInfoInterface');
        }
    }

    public function run()
    {
        /** @var \yz\db\ActiveRecord $model */
        $model = $this->model;
        $relation = $model->getRelation($this->attribute);

        if ($this->items) {
            $items = $this->items;
        } else {
            /** @var ActiveQuery $query */
            $query = call_user_func($relation->modelClass, 'find');
            if ($query instanceof ActiveQuery) {
                $items = $query->asMap()->all();
            } else {
                throw new Exception('You can use HasOneRelationWidget only with models that has yz\db\ActiveQuery children');
            }
        }
        echo Html::activeDropDownList($this->model, $this->attribute, $items, $this->options);
    }
}