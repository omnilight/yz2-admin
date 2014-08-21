<?php

use yii\helpers\Html;
use yz\admin\helpers\AdminHtml;
use yz\admin\widgets\ActiveForm;
use yz\admin\widgets\Box;
use yz\admin\widgets\FormBox;

/**
 * @var yii\web\View $this
 * @var \yz\admin\models\User $model
 * @var \yz\admin\forms\ChangeUserPasswordForm $passwordForm
 * @var yz\admin\widgets\ActiveForm $form
 */
?>

<?php $box = FormBox::begin(['title' => '', 'cssClass' => 'user-form box-primary']) ?>
    <?php $form = ActiveForm::begin(); ?>
    <?php $box->beginBody() ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
        <?= $form->field($model, 'login')->textInput(['maxlength' => 32]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
        <?= $form->field($model, 'is_super_admin')->checkbox() ?>
        <?= $form->field($model, 'is_active')->checkbox() ?>
        <?= $form->field($model, 'rolesItems')->dropDownList($model->getRolesItemsValues(), ['multiple' => 'multiple', 'size' => 10]) ?>

        <?php if ($model->isNewRecord): ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>
        <?php endif ?>

    <?php $box->endBody() ?>

    <?php $box->actions([
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
        AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
    ]) ?>

    <?php ActiveForm::end(); ?>

<?php FormBox::end() ?>

<?php if ($model->isNewRecord == false): ?>
    <div class="row">
        <div class="col-md-6">
            <?php $box = FormBox::begin(['title' => Yii::t('admin/t', 'Change password'), 'cssClass' => 'user-form box-primary']) ?>
                <?php $form = ActiveForm::begin(); ?>
                <?php $box->beginBody() ?>

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
        <div class="col-md-6">
            <?php $box = FormBox::begin([
                'title' => Yii::t('admin/t','User access token'),
                'cssClass' => 'user-form box-primary',
                'actionsOptions' => ['class' => '']
            ]) ?>
                <?php $form = ActiveForm::begin(); ?>

                <?php $box->beginBody() ?>
                    <pre><?= $model->access_token ?></pre>
                <?php $box->endBody() ?>
                <?php $box->actions([
                    AdminHtml::actionButton('reset_access_token', null, ['content' => \Yii::t('admin/t', 'Reset token')]),
                ]) ?>

                <?php ActiveForm::end(); ?>
            <?php FormBox::end() ?>
        </div>
    </div>
<?php endif ?>
