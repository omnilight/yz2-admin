<?php
use \yz\admin\widgets\ActiveForm;
/**
 * @var \yii\base\View $this
 * @var \yz\admin\forms\LoginForm $loginForm
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md4"></div>
    </div>
    <div class="row">
        <div class="col-md4">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
            ]) ?>

            <?= $form->errorSummary([$loginForm]); ?>

            <?= $form->field($loginForm, 'login')->textInput(); ?>
            <?= $form->field($loginForm, 'password')->passwordInput(); ?>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>