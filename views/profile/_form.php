<?php

use yii\helpers\Html;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\FormBox;

/**
 * @var yii\web\View $this
 * @var \yz\admin\models\User $model
 * @var \yz\admin\forms\ChangeUserPasswordForm $passwordForm
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<div class="row">
    <div class="col-sm-6">
        <?php $box = FormBox::begin(['title' => Yii::t('admin/t', 'Main settings'), 'cssClass' => 'profile-form box-primary']) ?>

        <?php $form = ActiveForm::begin(); ?>
        <?php $box->beginBody() ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

        <?php $box->endBody() ?>

        <?php $box->actions([
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
        ]) ?>

        <?php ActiveForm::end(); ?>

        <?php FormBox::end() ?>
    </div>
    <div class="col-sm-6">
        <?php $box = FormBox::begin(['title' => Yii::t('admin/t', 'Change password'), 'cssClass' => 'password-form box-primary']) ?>

        <?php $form = ActiveForm::begin([]); ?>
        <?php $box->beginBody() ?>

        <?= $form->field($passwordForm, 'oldPassword')->passwordInput() ?>

        <?= $form->field($passwordForm, 'password')->passwordInput() ?>

        <?= $form->field($passwordForm, 'passwordRepeat')->passwordInput() ?>

        <?php $box->endBody() ?>

        <?php $box->actions([
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
        ]) ?>

        <?php ActiveForm::end(); ?>

        <?php FormBox::end() ?>
    </div>
</div>




