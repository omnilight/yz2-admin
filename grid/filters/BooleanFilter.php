<?php

namespace yz\admin\grid\filters;
use yii\bootstrap\Html;


/**
 * Class BooleanFilter
 */
class BooleanFilter extends BaseFilter
{

    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        return Html::activeDropDownList($this->getModel(), $this->getAttribute(), [
            '' => 'Все',
            '0' => 'Нет',
            '1' => 'Да',
        ], [
            'class' => 'form-control',
            'style' => 'min-width: 90px',
        ]);
    }
}