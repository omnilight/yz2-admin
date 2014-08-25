<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var yz\admin\models\Role $model
 */

$this->title = \Yii::t('admin/t','Update object "{item}": {title}', [
    'item' => yz\admin\models\Role::modelTitle(),
    'title' => $model->description,
]);
$this->params['breadcrumbs'][] = ['label' => yz\admin\models\Role::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="role-update">

    <div class="text-right">
        <?php Box::begin() ?>
        <?php echo ActionButtons::widget([
            'order' => [['index', 'create', 'return']],
            'addReturnUrl' => false,
        ]) ?>
        <?php Box::end() ?>
    </div>

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>
