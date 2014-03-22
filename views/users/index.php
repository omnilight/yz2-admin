<?php

use yii\helpers\Html;
use yz\admin\widgets\ActionButtons;
use yz\admin\widgets\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var \yz\admin\models\search\UserSearch $searchModel
 */

$this->title = \yz\admin\models\User::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="btn-toolbar pull-right">
        <?=
        ActionButtons::widget([
            'order' => [['export', 'create', 'delete', 'return']],
            'gridId' => 'user-grid',
            'searchModel' => $searchModel,
            'modelClass' => '\yz\admin\models\User',
        ]) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>


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
            // 'is_active:boolean',
            // 'email:email',
            'logged_at',
            'created_at',
            'updated_at',

            [
                'class' => 'yz\admin\widgets\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
