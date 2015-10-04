<?php

namespace yz\admin\grid\columns;
use Yii;
use yii\helpers\Html;
use yz\admin\helpers\AdminUrl;
use yz\icons\Icons;


/**
 * Class ActionColumn
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * Indicates whether to add return URL to the default buttons
     * @var bool
     * @deprecated
     */
    public $addReturnUrl = true;

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
        $data = parent::renderDataCellContent($model, $key, $index);
        return "<nobr>{$data}</nobr>";
    }
}