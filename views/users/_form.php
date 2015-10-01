<?php

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

<div class="row">
    <div class="col-md-8">
        <?php $box = FormBox::begin(['title' => '', 'cssClass' => 'user-form box-primary']) ?>
        <?php $form = ActiveForm::begin(); ?>
        <?php $box->beginBody() ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

        <?php if ($model->is_identity): ?>

            <?= $form->field($model, 'login')->staticControl() ?>

            <?= $form->field($model, 'email')->staticControl() ?>

            <?= $form->field($model, 'is_super_admin')->staticControl([
                'value' => Yii::$app->formatter->asBoolean($model->is_super_admin),
            ]) ?>

            <?= $form->field($model, 'roles')->staticControl([
                'value' => implode('; ', \yii\helpers\ArrayHelper::getColumn($model->roles, 'description')),
            ]) ?>

            <?= $form->field($model, 'is_active')->checkbox() ?>

        <?php else: ?>
            <?= $form->field($model, 'login')->textInput(['maxlength' => 32]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
            <?= $form->field($model, 'is_super_admin')->checkbox() ?>
            <?= $form->field($model, 'is_active')->checkbox() ?>
            <?= $form->field($model, 'rolesItems')->select2($model->getRolesItemsValues(), ['multiple' => 'multiple', 'size' => 10]) ?>

            <?php if ($model->isNewRecord): ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>
            <?php endif ?>
        <?php endif ?>

        <?php $box->endBody() ?>

        <?php $box->actions([
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_STAY, $model->isNewRecord),
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_LEAVE, $model->isNewRecord),
            AdminHtml::actionButton(AdminHtml::ACTION_SAVE_AND_CREATE, $model->isNewRecord),
        ]) ?>

        <?php ActiveForm::end(); ?>

        <?php FormBox::end() ?>
    </div>
    <div class="col-md-4">
        <?php Box::begin(['title' => 'Свойства участника']) ?>

        <?= \yii\widgets\DetailView::widget([
            'model' => $model,
            'attributes' => [
                'access_token',
                'is_identity:boolean',
                [
                    'attribute' => 'profile.backendUserProfileType',
                    'label' => \Yii::t('admin/t', 'Profile type')
                ],
                [
                    'attribute' => 'profile.backendUserProfileTitle',
                    'label' => \Yii::t('admin/t', 'Profile title')
                ],
            ]
        ]) ?>

        <?php Box::end() ?>
    </div>
</div>

<?php if ($model->is_identity == 0): ?>
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

        </div>
    <?php endif ?>
<?php endif ?>
