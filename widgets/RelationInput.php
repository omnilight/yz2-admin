<?php

namespace yz\admin\widgets;

use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveRelation;
use yii\widgets\InputWidget;
use Yii;
use yz\db\ActiveRecord;
use yz\interfaces\ModelInfoInterface;

/**
 * Class RelationInput
 * @package yz\admin\widgets
 */
class RelationInput extends InputWidget
{
    public $values;

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
        /** @var ActiveRecord|ModelInfoInterface $model */
        $model = $this->model;
        /** @var ActiveRelation $relation */
        $relation = $model->getRelation($this->attribute);

        if ($relation->multiple) {
            $class = HasManyRelationInput::className();
        } else {
            $class = HasOneRelationInput::className();
        }

        /** @var string $class */
        echo $class::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
        ]);
    }
}