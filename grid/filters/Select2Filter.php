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
     * If true, it will be possible to select multiple values
     * @var bool
     */
    public $multiple = false;

    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        $defaults = [
            'class' => 'form-control',
        ];
        if ($this->multiple) {
            $defaults['multiple'] = 'multiple';
        } else {
            $defaults['prompt'] = \Yii::t('admin/gridview', 'All');
        }
        $options = array_merge($defaults, $this->options);

        return Select2::widget([
            'bootstrap' => true,
            'model' => $this->getModel(),
            'attribute' => $this->getAttribute(),
            'items' => $this->values,
            'options' => $options,
        ]) . $this->getError();
    }
}