<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yz\admin\models\search\UserSearch $searchModel
 */

$this->title = yz\admin\models\User::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

	<div class="btn-toolbar pull-right">
		<?=  ActionButtons::widget([
			'order' => [['search'], ['export', 'create', 'delete', 'return']],
		]) ?>
	</div>

	<h1><?= Html::encode($this->title) ?></h1>

	<?php echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php echo GridView::widget([
		'dataProvider' => $dataProvider,
		//'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\CheckBoxColumn'],

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
				'class' => 'yii\grid\ActionColumn',
				'template' => '{update} {delete}',
			],
		],
	]); ?>

</div>
