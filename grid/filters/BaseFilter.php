<?php

namespace yz\admin\grid\filters;
use yii\base\Object;
use yii\helpers\Html;
use yz\admin\grid\GridView;
use yii\grid\Column;
use yz\admin\grid\columns\DataColumn;


/**
 * Class BaseFilter
 */
abstract class BaseFilter extends Object
{
    /**
     * @var DataColumn
     */
    public $column;

    public function __construct(DataColumn $column, $config = [])
    {
        $this->column = $column;
        parent::__construct($config);
    }

    /**
     * @param array $config
     * @return FilterInstance
     */
    public static function instance($config = [])
    {
        return new FilterInstance(array_merge($config, [
            'class' => get_called_class(),
        ]));
    }

    /**
     * Returns error message if one's exist
     * @return string
     */
    protected function getError()
    {
        if ($this->column->grid->filterModel->hasErrors($this->column->attribute)) {
            Html::addCssClass($this->column->filterOptions, 'has-error');
            return Html::error($this->column->grid->filterModel, $this->column->attribute, $this->column->grid->filterErrorOptions);
        }

        return '';
    }

    /**
     * Renders the filter content
     * @return string
     */
    abstract public function render();

    /**
     * @return \yii\base\Model
     */
    protected function getModel()
    {
        return $this->column->grid->filterModel;
    }

    /**
     * @return string
     */
    protected function getAttribute()
    {
        return $this->column->attribute;
    }

}