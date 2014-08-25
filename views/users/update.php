<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;

/**
 * @var yii\web\View $this
 * @var \yz\admin\models\User $model
 * @var \yz\admin\forms\ChangeUserPasswordForm $passwordForm
 */

$this->title = \Yii::t('admin/t', 'Update object "{item}": {title}', [
    'item' => \yz\admin\models\User::modelTitle(),
    'title' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => \yz\admin\models\User::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="user-update">

    <div class="text-right">
        <?php Box::begin() ?>
        <?php echo ActionButtons::widget([
            'order' => [['index', 'create', 'return',]],
            'addReturnUrl' => false,
        ]) ?>
        <?php Box::end() ?>
    </div>

    <?php echo $this->render('_form', [
        'model' => $model,
        'passwordForm' => $passwordForm,
    ]); ?>


</div>
