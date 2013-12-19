<?php

use yii\helpers\Html;
use yz\admin\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yz\admin\models\search\UserSearch $model
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<div class="user-search hidden" id="filter-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'login') ?>

		<?= $form->field($model, 'passhash') ?>

		<?= $form->field($model, 'auth_key') ?>

		<?= $form->field($model, 'is_super_admin')->checkbox() ?>

		<?php // echo $form->field($model, 'is_active')->checkbox() ?>

		<?php // echo $form->field($model, 'name') ?>

		<?php // echo $form->field($model, 'email') ?>

		<?php // echo $form->field($model, 'login_time') ?>

		<?php // echo $form->field($model, 'create_time') ?>

		<?php // echo $form->field($model, 'update_time') ?>

		<div class="form-group">
			<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
			<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
		</div>

	<?php ActiveForm::end(); ?>

</div>
