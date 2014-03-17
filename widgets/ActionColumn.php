<?php

namespace yz\admin\widgets;


use Yii;
use yii\helpers\Html;
use yz\admin\helpers\AdminHelper;
use yz\icons\Icons;

/**
 * Class ActionColumn
 * @package yz\admin\widgets
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * Indicates whether to add return URL to the default buttons
     * @var bool
     */
    public $addReturnUrl = true;

    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model) {
                return Html::a(Icons::i('eye fa-lg'), $url, [
                    'title' => Yii::t('yz/admin', 'View'),
                    'class' => 'btn btn-info btn-sm',
                ]);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model) {
                return Html::a(Icons::i('pencil-square-o fa-lg'), $url, [
                    'title' => Yii::t('yz/admin', 'Update'),
                    'class' => 'btn btn-success btn-sm',
                ]);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
                return Html::a(Icons::i('trash-o fa-lg'), $url, [
                    'title' => Yii::t('yz/admin', 'Delete'),
                    'data-confirm' => Yii::t('yz/admin', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                    'class' => 'btn btn-danger btn-sm',
                ]);
            };
        }
    }

    public function createUrl($action, $model, $key, $index)
    {
        if ($this->addReturnUrl)
            $key = (is_array($key) ? $key : ['id' => $key]) + AdminHelper::returnUrlRoute();
        return parent::createUrl($action, $model, $key, $index);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        $data = parent::renderDataCellContent($model, $key, $index);
        return "<nobr>{$data}</nobr>";
    }


} 