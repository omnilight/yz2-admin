<?php

namespace yz\admin\widgets;


use yii\bootstrap\Button;
use yii\helpers\Html;
use Yii;
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
			$this->buttons['view'] = function ($model, $key, $index, $column) {
				/** @var ActionColumn $column */
				if ($this->addReturnUrl)
					$key = (is_array($key) ? $key : ['id' => $key]) + AdminHelper::returnUrlRoute();
				$url = $column->createUrl($model, $key, $index, 'view');
				return Html::a(Icons::i('eye fa-lg'), $url, [
					'title' => Yii::t('yz/admin', 'View'),
					'class' => 'btn btn-info btn-sm',
				]);
			};
		}
		if (!isset($this->buttons['update'])) {
			$this->buttons['update'] = function ($model, $key, $index, $column) {
				/** @var ActionColumn $column */
				if ($this->addReturnUrl)
					$key = (is_array($key) ? $key : ['id' => $key]) + AdminHelper::returnUrlRoute();
				$url = $column->createUrl($model, $key, $index, 'update');
				return Html::a(Icons::i('pencil-square-o fa-lg'), $url, [
					'title' => Yii::t('yz/admin', 'Update'),
					'class' => 'btn btn-success btn-sm',
				]);
			};
		}
		if (!isset($this->buttons['delete'])) {
			$this->buttons['delete'] = function ($model, $key, $index, $column) {
				/** @var ActionColumn $column */
				if ($this->addReturnUrl)
					$key = (is_array($key) ? $key : ['id' => $key]) + AdminHelper::returnUrlRoute();
				$url = $column->createUrl($model, $key, $index, 'delete');
				return Html::a(Icons::i('trash-o fa-lg'), $url, [
					'title' => Yii::t('yz/admin', 'Delete'),
					'data-confirm' => Yii::t('yz/admin', 'Are you sure to delete this item?'),
					'data-method' => 'post',
					'class' => 'btn btn-danger btn-sm',
				]);
			};
		}
	}
} 