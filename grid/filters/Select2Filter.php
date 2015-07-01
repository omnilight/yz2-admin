<?php

namespace yz\admin\grid\filters;
use yii\helpers\ArrayHelper;
use yz\admin\widgets\Select2;


/**
 * Class Select2Filter
 */
class Select2Filter extends BaseFilter
{
    /**
     * @var array
     */
    public $values;
    /**
     * @var array
     */
    public $options = [];

    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        $options = array_merge([
            'prompt' => '',
            'class' => 'form-control',
        ], $this->options);

        return Select2::widget([
            'bootstrap' => true,
            'model' => $this->column->grid->filterModel,
            'attribute' => $this->column->attribute,
            'items' => ArrayHelper::merge([
                '' => '',
            ], $this->values),
            'options' => $options,
        ]) . $this->getError();
    }
}