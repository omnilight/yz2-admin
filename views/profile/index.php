<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var \yz\admin\models\User $model
 * @var \yz\admin\forms\ChangeUserPasswordForm $passwordForm
 */

$this->title = \Yii::t('admin/t','Your profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-edit">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_form', [
        'model' => $model,
        'passwordForm' => $passwordForm,
    ]); ?>

</div>
