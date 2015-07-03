<?php

namespace yz\admin\grid\columns;

use yii\base\Model;
use yii\grid\Column;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yz\admin\grid\filters\ActiveTextInputFilter;
use yz\admin\grid\filters\BaseFilter;
use yz\admin\grid\filters\DatePickerFilter;
use yz\admin\grid\filters\FilterInstance;
use yz\admin\grid\filters\InlineFilter;
use yz\admin\grid\filters\Select2Filter;
use yz\admin\grid\GridView;


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
    /**
     * @var array the HTML attributes for the filter input fields. This property is used in combination with
     * the [[filter]] property. When [[filter]] is not set or is an array, this property will be used to
     * render the HTML attributes for the generated filter input fields.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $filterInputOptions = ['class' => 'form-control input-sm', 'id' => null];

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
            return \Yii::$app->formatter->format($grid->getTotalData()[array_keys($this->total)[0]], $this->format);
        } else {
            return \Yii::$app->formatter->format($this->total, $this->format);
        }
    }

    protected function renderFilterCellContent()
    {
        $filter = $this->guessFilter();

        if ($filter instanceof BaseFilter) {
            return $filter->render();
        }

        return Column::renderFilterCellContent();
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $content = \yii\grid\DataColumn::renderDataCellContent($model, $key, $index);
        if ($this->titles) {
            $value = ArrayHelper::getValue($this->titles, $content, $content);
        } else {
            $value = $content;
        }
        if ($this->labels) {
            $content = Html::tag('span', $value, [
                'class' => 'label label-grid-view label-' . ArrayHelper::getValue($this->labels, $content, self::LABEL_DEFAULT)
            ]);
        } else {
            $content = $value;
        }
        return $content;
    }

    private function guessFilter()
    {
        if (is_string($this->filter)) {
            return \Yii::createObject([
                'class' => InlineFilter::class,
                'content' => $this->filter,
            ], [$this]);
        }

        if ($this->filter instanceof FilterInstance) {
            return \Yii::createObject($this->filter->config, [$this]);
        }

        $model = $this->grid->filterModel;

        if (!($model instanceof Model && $this->attribute !== null && $model->isAttributeActive($this->attribute))) {
            return null;
        }

        if (is_array($this->filter)) {
            return \Yii::createObject([
                'class' => Select2Filter::class,
                'values' => $this->filter,
            ], [$this]);
        }

        if ($this->format == 'datetime') {
            return \Yii::createObject(DatePickerFilter::instance(), [$this]);
        }

        return \Yii::createObject([
            'class' => ActiveTextInputFilter::class,
        ], [$this]);

    }
}