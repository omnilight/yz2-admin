<?php

namespace yz\admin\grid\filters;

use omnilight\datetime\DateRangeConfig;
use omnilight\widgets\DateRangePicker;
use yii\helpers\ArrayHelper;


/**
 * Class DateRangeFilter
 */
class DateRangeFilter extends BaseFilter
{
    /**
     * @var string
     */
    public $rangeAttribute;
    /**
     * @var array
     */
    public $options = [];

    public function init()
    {
        parent::init();

        if ($this->rangeAttribute === null) {
            $this->rangeAttribute = $this->getAttribute() . '_range';
        }
    }

    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        $gridId = $this->column->grid->id;

        return DateRangePicker::widget(DateRangeConfig::get($this->getModel(), $this->rangeAttribute, ArrayHelper::merge([
            'model' => $this->getModel(),
            'attribute' => $this->rangeAttribute,
            'options' => [
                'class' => 'form-control input-sm',
            ],
            'clientEvents' => [
                'apply.daterangepicker' => "function () {
                    $('#$gridId').yiiGridView('applyFilter');
                }"
            ]
        ], $this->options))) . $this->getError();
    }
}