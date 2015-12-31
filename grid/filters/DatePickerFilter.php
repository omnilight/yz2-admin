<?php

namespace yz\admin\grid\filters;
use omnilight\widgets\DatePicker;
use omnilight\datetime\DatePickerConfig;


/**
 * Class DatePickerFilter
 * @deprecated This filter is returing empty string. Use DateFilter instead
 */
class DatePickerFilter extends BaseFilter
{

    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        return '';
    }
}