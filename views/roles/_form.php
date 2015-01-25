<?php

use yii\helpers\Html;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\FormBox;

/**
 * @var yii\web\View $this
 * @var yz\admin\models\Role $model
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<?php $box = FormBox::begin(['cssClass' => 'roles-form box-primary', 'title' => '']) ?>

    <?php $form = ActiveForm::begin(); ?>

    <?php $box->beginBody() ?>
        <?= $form->field($model, 'description')->textInput() ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'childRoles')->select2($model->getChildRolesValues(),['multiple' => 'multiple', 'size' => 10]) ?>
        <?= $form->field($model, 'childPermissions')->select2($model->getChildPermissionsValues(),['multiple' => 'multiple', 'size' => 20]) ?>

    <?php $box->endBody() ?>

    <?php $box->actions([
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
    ]) ?>

    <?php ActiveForm::end(); ?>

<?php FormBox::end() ?>
