<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var \yz\admin\models\User $model
 * @var \yz\admin\forms\ChangeUserPasswordForm $passwordForm
 */

$this->title = \Yii::t('admin/t','Update object "{item}": {title}', [
	'item' => \yz\admin\models\User::modelTitle(),
	'title' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => \yz\admin\models\User::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-update">

	<div class="btn-toolbar pull-right">
		<?=  ActionButtons::widget([
			'order' => [['index', 'update', 'return']],
			'addReturnUrl' => false,
		]) ?>
	</div>

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
        'passwordForm' => $passwordForm,
	]); ?>

</div>
