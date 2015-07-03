<?php

namespace yz\admin\grid\filters;
use omnilight\widgets\DatePicker;
use omnilight\datetime\DatePickerConfig;


/**
 * Class DatePickerFilter
 */
class DatePickerFilter extends BaseFilter
{

    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        return DatePicker::widget(DatePickerConfig::get($this->getModel(), $this->getAttribute(), [
            'model' => $this->getModel(),
            'attribute' => $this->getAttribute(),
            'options' => ['class' => 'form-control', 'placeholder' => \Yii::t('admin/gridview', 'Search')],
        ]));
    }
}