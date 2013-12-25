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
		'fieldConfig' => [
			'horizontal' => ['label' => 'col-sm-3', 'input' => 'col-sm-5', 'offset' => 'col-sm-offset-3 col-sm-5'],
		],
		'method' => 'get',
	]); ?>

		<?= $form->field($model, 'id') ?>

		<?= $form->field($model, 'login') ?>

		<?= $form->field($model, 'passhash') ?>

		<?= $form->field($model, 'auth_key') ?>

		<?= $form->field($model, 'is_super_admin')->radioList(['' => \Yii::t('yz/admin','All records'), '1' => \Yii::t('yz/admin','Yes'), '0' => \Yii::t('yz/admin','No')]) ?>

		<?php // echo $form->field($model, 'is_active')->radioList(['' => \Yii::t('yz/admin','All records'), '1' => \Yii::t('yz/admin','Yes'), '0' => \Yii::t('yz/admin','No')]) ?>

		<?php // echo $form->field($model, 'name') ?>

		<?php // echo $form->field($model, 'email') ?>

		<?php // echo $form->field($model, 'login_time') ?>

		<?php // echo $form->field($model, 'create_time') ?>

		<?php // echo $form->field($model, 'update_time') ?>

		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-5">
				<?= Html::submitButton(\Yii::t('yz/admin','Search'), ['class' => 'btn btn-primary']) ?>
			</div>
		</div>

	<?php ActiveForm::end(); ?>

</div>
