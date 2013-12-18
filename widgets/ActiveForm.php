<?php

namespace yz\admin\widgets;
use yii\helpers\Html;
use yii\helpers\Json;


/**
 * Class ActiveForm
 * @package yz\admin\widgets
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
	const TYPE_INLINE = 'form-inline';
	const TYPE_VERTICAL = 'form-vertical';
	const TYPE_HORIZONTAL = 'form-horizontal';

	/**
	 * Type of the form: TYPE_INLINE, TYPE_VERTICAL or TYPE_HORIZONTAL
	 * @var string
	 */
	public $type = self::TYPE_HORIZONTAL;

    public function init()
    {
        if (!isset($this->fieldConfig['class'])) {
            $this->fieldConfig['class'] = \yz\admin\widgets\ActiveField::className();
        }

		Html::addCssClass($this->options, static::$_configuration[$this->type]['options']['class']);
		Html::addCssClass($this->fieldConfig['labelOptions'],
			static::$_configuration[$this->type]['fieldConfig']['labelOptions']['class']);

        parent::init();
    }

	public function run()
	{
		$view = $this->getView();
		ActiveFormAsset::register($view);
		parent::run();
	}

	static protected $_configuration = [
		self::TYPE_INLINE => [
			'options' => ['class' => 'form-inline',],
			'fieldConfig' => [
				'labelOptions' => ['class' => 'sr-only',]
			],
		],
		self::TYPE_VERTICAL => [
			'options' => ['class' => 'form-vertical',],
			'fieldConfig' => [
				'labelOptions' => ['class' => '',]
			]
		],
		self::TYPE_HORIZONTAL => [
			'options' => ['class' => 'form-horizontal',],
			'fieldConfig' => [
				'labelOptions' => ['class' => '',]
			]
		]
	];
}