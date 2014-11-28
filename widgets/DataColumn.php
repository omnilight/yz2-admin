<?php

namespace yz\admin\widgets;

use yii\base\Model;
use yii\grid\Column;
use yii\helpers\Html;
use yii\jui\DatePicker;


/**
 * Class DataColumn
 */
class DataColumn extends \yii\grid\DataColumn
{
    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }

        $model = $this->grid->filterModel;

        if ($this->filter !== false && $model instanceof Model && $this->attribute !== null && $model->isAttributeActive($this->attribute)) {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }
            if (is_array($this->filter)) {
                $options = array_merge(['prompt' => ''], $this->filterInputOptions);
                return Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . ' ' . $error;
            } else {
                if ($this->format == 'datetime') {
                    return DatePicker::widget([
                        'model' => $model,
                        'attribute' => $this->attribute,
                        'options' => ['class' => 'form-control'],
                    ]);
                } else {
                    return Html::activeTextInput($model, $this->attribute, $this->filterInputOptions) . ' ' . $error;
                }
            }
        } else {
            return Column::renderFilterCellContent();
        }
    }

} 