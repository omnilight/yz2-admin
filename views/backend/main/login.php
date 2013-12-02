<?php
use yii\widgets\ActiveForm;
/**
 * @var \yii\base\View $this
 * @var \yz\admin\models\LoginForm $loginForm
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
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
            ]) ?>

            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>