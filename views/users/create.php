<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var \yz\admin\models\User $model
 */

$this->title = \Yii::t('yz/admin','Create {item}', ['{item}' => \yz\interfaces\User::modelTitle()]);
$this->params['breadcrumbs'][] = ['label' => \yz\interfaces\User::modelTitlePlural(), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

	<div class="btn-toolbar pull-right">
		<?=  ActionButtons::widget([
			'order' => [['index', 'create', 'return']],
			'addReturnUrl' => false,
		]) ?>
	</div>

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
