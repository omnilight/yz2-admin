<?php

use yii\helpers\Html;
use yz\admin\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yz\interfaces\User $model
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<div class="user-form">

	<?php $form = ActiveForm::begin([
		'enableAjaxValidation' => true,
	]); ?>

		<?= $form->field($model, 'id')->textInput() ?>

		<?= $form->field($model, 'login')->textInput(['maxlength' => 32]) ?>

		<?= $form->field($model, 'passhash')->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'auth_key')->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'is_super_admin')->checkbox() ?>

		<?= $form->field($model, 'is_active')->checkbox() ?>

		<?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

		<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'login_time')->textInput() ?>

		<?= $form->field($model, 'create_time')->textInput() ?>

		<?= $form->field($model, 'update_time')->textInput() ?>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?= Html::submitButton($model->isNewRecord ? \Yii::t('yz/admin','Create') : \Yii::t('yz/admin','Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'save_and_stay']) ?>
				<?= Html::submitButton($model->isNewRecord ? \Yii::t('yz/admin','Create & Exit') : \Yii::t('yz/admin','Update & Exit'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				<?php if ($model->isNewRecord): ?>
					<?= Html::submitButton(\Yii::t('yz/admin','Create & Then Create Another One'), ['class' => 'btn btn-success', 'name' => 'save_and_create']) ?>
				<?php endif ?>
			</div>
		</div>

	<?php ActiveForm::end(); ?>

</div>
