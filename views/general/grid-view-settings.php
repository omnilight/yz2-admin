<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yz\admin\widgets\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var \yz\admin\forms\GridViewSettingsForm $model
 */
?>
<div id="grid-view-settings-wrapper">
    <div class="modal fade" id="grid-view-settings-dialog" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><?= Yii::t('admin/gridview', 'Grid settings') ?></h4>
                </div>
                <?php $form = ActiveForm::begin(['layout' => 'default']) ?>
                <div class="modal-body">

                    <?= $form->field($model, 'pageSize')->dropDownList($model->getPageSizeValues()) ?>

                </div>
                <div class="modal-footer">
                    <?= Html::submitButton(Yii::t('admin/gridview', 'Save & Apply'), ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>
<?php

$js =<<<JS
    $('#grid-view-settings-dialog').modal('show');
JS;
$this->registerJs($js);
