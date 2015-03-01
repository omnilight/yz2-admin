<?php

namespace yz\admin\widgets;

use yii\base\Model;
use yii\db\Expression;
use yii\grid\Column;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\DatePicker;


/**
 * Class DataColumn
 */
class DataColumn extends \yii\grid\DataColumn
{
    const LABEL_DEFAULT = 'default';
    const LABEL_PRIMARY = 'primary';
    const LABEL_SUCCESS = 'success';
    const LABEL_INFO = 'info';
    const LABEL_WARNING = 'warning';
    const LABEL_DANGER = 'danger';

    /**
     * @var array The array of titles that should replace original values.
     */
    public $titles;
    /**
     * @var array The array of key-value pairs that should color the titles
     */
    public $labels;
    /**
     * @var string|array The string or array (in case of using active records) to represent expression for total counting
     * of this column
     */
    public $total;

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
                if (ArrayHelper::remove($this->filterInputOptions, 'filterSuggest', false) && class_exists('vova07\select2\Widget')) {
                    return \vova07\select2\Widget::widget([
                        'bootstrap' => true,
                        'model' => $model,
                        'attribute' => $this->attribute,
                        'items' => $this->filter,
                        'options' => $options,
                    ]);
                } else {
                    return Html::activeDropDownList($model, $this->attribute, $this->filter, $options) . ' ' . $error;
                }
            } else {
                if ($this->format == 'datetime') {
                    return DatePicker::widget([
                        'model' => $model,
                        'attribute' => $this->attribute,
                        'options' => ['class' => 'form-control', 'placeholder' => \Yii::t('admin/gridview', 'Search')],
                    ]);
                } else {
                    return Html::activeTextInput($model, $this->attribute, array_merge([
                        'placeholder' => \Yii::t('admin/gridview', 'Search'),
                    ], $this->filterInputOptions)) . ' ' . $error;
                }
            }
        } else {
            return Column::renderFilterCellContent();
        }
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $content = parent::renderDataCellContent($model, $key, $index);
        if ($this->titles) {
            $value = ArrayHelper::getValue($this->titles, $content, $content);
        } else {
            $value = $content;
        }
        if ($this->labels) {
            $content = Html::tag('span', $value, ['class' => 'label label-grid-view label-' . ArrayHelper::getValue($this->labels, $content, self::LABEL_DEFAULT)]);
        } else {
            $content = $value;
        }
        return $content;
    }

    /**
     * @return string
     */
    public function renderTotalCell()
    {
        return Html::tag('td', $this->renderTotalCellContent());
    }

    protected function renderTotalCellContent()
    {
        if (is_array($this->total)) {
            /** @var GridView $grid */
            $grid = $this->grid;
            return $grid->getTotalData()[array_keys($this->total)[0]];
        } else {
            return $this->total;
        }
    }
}