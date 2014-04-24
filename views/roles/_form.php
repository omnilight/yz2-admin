<?php

use yii\helpers\Html;
use yz\admin\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yz\admin\models\Role $model
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<div class="role-form crud-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'description')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'childRoles')->dropDownList($model->getChildRolesValues(),['multiple' => 'multiple', 'size' => 10]) ?>

    <?= $form->field($model, 'childPermissions')->dropDownList($model->getChildPermissionsValues(),['multiple' => 'multiple', 'size' => 20]) ?>


    <div class="form-group form-actions">
        <div class="col-sm-offset-2 col-sm-10">
            <?= Html::submitButton($model->isNewRecord ? \Yii::t('admin/t', 'Create') : \Yii::t('admin/t', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => '__action', 'value' => 'save_and_stay']) ?>
            <?= Html::submitButton($model->isNewRecord ? \Yii::t('admin/t', 'Create & Exit') : \Yii::t('admin/t', 'Update & Exit'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?php if ($model->isNewRecord): ?>
                <?= Html::submitButton(\Yii::t('admin/t', 'Create & Then Create Another One'), ['class' => 'btn btn-success', 'name' => '__action', 'value' => 'save_and_create']) ?>
            <?php endif ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
