<?php

namespace yz\admin\grid\filters;

use yii\base\Model;
use yii\base\Object;
use yii\helpers\Html;
use yz\admin\grid\columns\DataColumn;


/**
 * Class BaseFilter
 * @property Model $model
 * @property string $attribute
 */
abstract class BaseFilter extends Object
{
    /**
     * @var DataColumn
     */
    public $column;
    /**
     * @var Model
     */
    private $_model;
    /**
     * @var string
     */
    private $_attribute;

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
     * Renders the filter content
     * @return string
     */
    abstract public function render();

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
     * @return \yii\base\Model
     */
    protected function getModel()
    {
        if ($this->_model !== null) {
            return $this->_model;
        }
        return $this->column->grid->filterModel;
    }

    /**
     * @param Model $model
     */
    public function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * @return string
     */
    protected function getAttribute()
    {
        if ($this->_attribute !== null) {
            return $this->_attribute;
        }
        return $this->column->attribute;
    }

    /**
     * @param string $attribute
     */
    public function setAttribute($attribute)
    {
        $this->_attribute = $attribute;
    }

}