<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;

/**
 * @var yii\web\View $this
 * @var yz\admin\models\Role $model
 */

$this->title = \Yii::t('admin/t','Create object "{item}"', ['item' => yz\admin\models\Role::modelTitle()]);
$this->params['breadcrumbs'][] = ['label' => yz\admin\models\Role::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="role-create">

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
