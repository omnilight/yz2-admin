<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\Box;
use yz\admin\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \yz\admin\models\search\UserSearch $searchModel
 */

$this->title = \yz\admin\models\User::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?php $box = Box::begin(['cssClass' => 'box-primary']) ?>
<div class="text-right">
    <?php echo ActionButtons::widget([
        'order' => [['create', 'delete', 'return']],
        'gridId' => 'user-grid',
        'searchModel' => $searchModel,
        'modelClass' => '\yz\admin\models\User',
    ]) ?>
</div>
<?php echo GridView::widget([
    'id' => 'user-grid',
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\CheckboxColumn'],

        'id',
        'login',
        'name',
        'email:email',
//			'passhash',
//			'auth_key',
        'is_super_admin:boolean',
        'is_active:boolean',
        // 'email:email',
        'logged_at:datetime',
        'created_at:datetime',
        'updated_at:datetime',

        [
            'class' => 'yz\admin\widgets\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
]); ?>
<?php Box::end() ?>
