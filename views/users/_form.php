<?php

use yii\helpers\Html;
use yz\admin\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \yz\admin\models\User $model
 * @var \yz\admin\forms\ChangeUserPasswordForm $passwordForm
 * @var yz\admin\widgets\ActiveForm $form
 */
$passwordId = Html::getInputId($model, 'password');
$passwordRepeatId = Html::getInputId($model, 'passwordRepeat');
$message = \yii\helpers\Json::encode(Yii::t('admin/t','New password is: {password}'));
$generatorJs =<<<JS
(function($) {
    var passwordLength = 12;
    var alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789001234567890';

    $('#generate_password').on('click', function() {
        var password = '';
        for (var i = 0; i < passwordLength; i++) {
            var charPos = Math.floor(Math.random() * alphabet.length);
            password += alphabet.substring(charPos, charPos+1);
        }
        $('#{$passwordId}').val(password);
        $('#{$passwordRepeatId}').val(password);

        alert($message.replace('{password}', password));
    });
})(jQuery);
JS;
?>

<div class="user-form crud-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => 32]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'is_super_admin')->checkbox() ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <?= $form->field($model, 'rolesItems')->dropDownList($model->getRolesItemsValues(), ['multiple' => 'multiple', 'size' => 10]) ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>
        <a href="#" id="generate_password"><?= Yii::t('admin/t','Generate password') ?></a>
        <?php $this->registerJs($generatorJs) ?>
    <?php endif ?>


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

<?php if ($model->isNewRecord == false): ?>
    <div class="user-form crud-form">

        <h2><?= Yii::t('admin/t', 'Change password') ?></h2>

        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
        ]); ?>

        <?= $form->field($passwordForm, 'password')->passwordInput() ?>
        <?= $form->field($passwordForm, 'passwordRepeat')->passwordInput() ?>
        <a href="#" id="generate_password"><?= Yii::t('admin/t','Generate password') ?></a>
        <?php $this->registerJs($generatorJs) ?>

        <div class="form-group form-actions">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton($model->isNewRecord ? \Yii::t('admin/t', 'Create') : \Yii::t('admin/t', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => '__action', 'value' => 'save_and_stay']) ?>
                <?= Html::submitButton($model->isNewRecord ? \Yii::t('admin/t', 'Create & Exit') : \Yii::t('admin/t', 'Update & Exit'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="user-form crud-form">
        <h2><?= Yii::t('admin/t','User access token') ?></h2>

        <div class="col-sm-offset-1 col-sm-8">
            <pre><?= $model->access_token ?></pre>
        </div>

        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
        ]); ?>

        <div class="form-group form-actions">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton(\Yii::t('admin/t', 'Reset token'), ['class' => 'btn btn-primary', 'name' => '__action', 'value' => 'reset_access_token']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
<?php endif ?>
