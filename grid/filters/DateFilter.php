<?php

namespace yz\admin\grid\filters;
use omnilight\datetime\DatePickerConfig;
use omnilight\widgets\DatePicker;
use yii\helpers\ArrayHelper;


/**
 * Class DateFilter
 */
class DateFilter extends BaseFilter
{
    public $options = [];
    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        return DatePicker::widget(ArrayHelper::merge(DatePickerConfig::get($this->getModel(), $this->getAttribute(), [
            'model' => $this->getModel(),
            'attribute' => $this->getAttribute(),
            'options' => ['class' => 'form-control', 'placeholder' => \Yii::t('admin/gridview', 'Search')],
        ], DatePicker::class), $this->options));
    }
}