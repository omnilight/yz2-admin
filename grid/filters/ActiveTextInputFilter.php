<?php

namespace yz\admin\grid\filters;
use yii\helpers\Html;


/**
 * Class ActiveTextInputFilter
 */
class ActiveTextInputFilter extends BaseFilter
{
    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        return Html::activeTextInput($this->getModel(), $this->getAttribute(), array_merge([
            'placeholder' => \Yii::t('admin/gridview', 'Search'),
        ], $this->column->filterInputOptions)) . ' ' . $this->getError();
    }
}