<?php

namespace yz\admin\widgets;
use yii\helpers\Html;


/**
 * Class ActiveField
 */
class ActiveField extends \yii\widgets\ActiveField
{
	/**
	 * @var ActiveForm the form that this field is associated with.
	 */
	public $form;

	public function init()
	{
		if (!isset($this->inputOptions['placeholder']) && $this->form->type == ActiveForm::TYPE_INLINE) {
			$this->inputOptions['placeholder'] = Html::encode($this->model->getAttributeLabel($this->attribute));
		}
		parent::init();
	}
}