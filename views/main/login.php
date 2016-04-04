<?php
use yz\admin\widgets\ActiveForm;
use yii\helpers\Html;
use yz\icons\Icons;

/**
 * @var \yii\web\View $this
 * @var \yz\admin\forms\LoginForm $loginForm
 */
\yz\admin\assets\LoginAsset::register($this);
$this->params['body-extra-class'] = 'login-page'
?>
<div class="container">
	<div class="b-login">
		<h1><?= Yii::t('admin/t', 'Administration panel'); ?></h1>

		<?php $form = ActiveForm::begin([
			'id' => 'login-form',
			'fieldConfig' => [
				'horizontalCssClasses' => [
					'label' => 'col-sm-3',
					'wrapper' => 'col-sm-7'
				]
			]
		]) ?>

		<?= $form->field($loginForm, 'login')->textInput(['autofocus' => '']); ?>

		<?= $form->field($loginForm, 'password')->passwordInput(); ?>

		<?= Html::submitButton(Icons::p('unlock-alt') . Yii::t('admin/t', 'Sign in'), ['class' => 'btn btn-success']); ?>

		<?php ActiveForm::end() ?>
	</div>
</div>