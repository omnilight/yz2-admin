<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yz\interfaces\search\UserSearch $searchModel
 */

$this->title = \yz\interfaces\User::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

	<div class="btn-toolbar pull-right">
		<?=  ActionButtons::widget([
			'order' => [['search'], ['export', 'create', 'delete', 'return']],
			'gridId' => 'user-grid',
			'searchModel' => $searchModel,
			'modelClass' => '\yz\admin\models\User',
		]) ?>
	</div>

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
		'id' => 'user-grid',
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\CheckboxColumn'],

			'id',
			'login',
			'passhash',
			'auth_key',
			'is_super_admin:boolean',
			// 'is_active:boolean',
			// 'name',
			// 'email:email',
			// 'login_time',
			// 'create_time',
			// 'update_time',

			[
				'class' => 'yz\admin\widgets\ActionColumn',
				'template' => '{update} {delete}',
			],
		],
	]); ?>

</div>
