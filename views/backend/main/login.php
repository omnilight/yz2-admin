<?php
use yz\admin\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var \yii\base\View $this
 * @var \yz\admin\forms\LoginForm $loginForm
 */
\yz\admin\assets\LoginAsset::register($this);
?>
<div class="container">
	<div class="b-login">
		<h1><?= Yii::t('yz/admin', 'Administration panel'); ?></h1>

		<?php $form = ActiveForm::begin([
			'id' => 'login-form',
			'type' => ActiveForm::TYPE_INLINE,
		]) ?>

		<?= $form->errorSummary([$loginForm]); ?>

		<?= $form->field($loginForm, 'login')->textInput(); ?>

		<?= $form->field($loginForm, 'password')->passwordInput(); ?>

		<?= Html::submitButton(Yii::t('yz/admin', 'Sign in'), ['class' => 'btn btn-success']); ?>

		<?php ActiveForm::end() ?>
	</div>
</div>