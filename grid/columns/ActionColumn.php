<?php

namespace yz\admin\grid\columns;

use Closure;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yz\admin\helpers\Rbac;
use yz\admin\helpers\RouteNormalizer;
use yz\icons\Icons;


/**
 * Class ActionColumn
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * Indicates whether to add return URL to the default buttons
     * @var bool
     */
    public $addReturnUrl = true;
    /**
     * @var callable a callback that checks access for the button.
     * ```php
     * function (ActionColumn $column, $action, $model, $key, $index)
     * {
     *
     * }
     * ```
     */
    public $checkAccess;
    private $_checkAccessCache = [];

    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model) {
                return Html::a(Icons::i('eye fa-lg'), $url, [
                    'title' => Yii::t('admin/t', 'View'),
                    'class' => 'btn btn-info btn-sm',
                    'data-pjax' => '0',
                ]);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model) {
                return Html::a(Icons::i('pencil-square-o fa-lg'), $url, [
                    'title' => Yii::t('admin/t', 'Edit'),
                    'class' => 'btn btn-success btn-sm',
                    'data-pjax' => '0',
                ]);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
                return Html::a(Icons::i('trash-o fa-lg'), $url, [
                    'title' => Yii::t('admin/t', 'Delete'),
                    'data-confirm' => Yii::t('admin/t', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                    'class' => 'btn btn-danger btn-sm',
                    'data-pjax' => '0',
                ]);
            };
        }
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        return Html::tag('nobr', preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
            if (isset($this->buttons[$name]) && $this->checkAccessInternal($name, $model, $key, $index)) {
                $url = $this->createUrl($name, $model, $key, $index);

                return call_user_func($this->buttons[$name], $url, $model, $key);
            } else {
                return '';
            }
        }, $this->template));
    }

    /**
     * @param $action
     * @param $model
     * @param $key
     * @param $index
     * @return bool
     */
    public function checkAccess($action, $model, $key, $index)
    {
        $params = is_array($key) ? $key : ['id' => (string)$key];
        $params[0] = $this->controller ? $this->controller . '/' . $action : $action;
        if (!isset($this->_checkAccessCache[$params[0]])) {
            $operation = Rbac::routeToOperation(RouteNormalizer::normalizeRoute($params[0]));
            $this->_checkAccessCache[$params[0]] = Yii::$app->user->can($operation);
        }

        return $this->_checkAccessCache[$params[0]];
    }

    protected function checkAccessInternal($action, $model, $key, $index)
    {
        if ($this->checkAccess instanceof Closure) {
            return call_user_func($this->checkAccess, $this, $action, $model, $key, $index);
        } else {
            return $this->checkAccess($action, $model, $key, $index);
        }
    }

    /**
     * @inheritdoc
     */
    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index);
        } else {
            $params = is_array($key) ? $key : ['id' => (string) $key];
            if ($this->addReturnUrl) {
                $params['return'] = Url::current();
            }
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return Url::toRoute($params);
        }
    }
}