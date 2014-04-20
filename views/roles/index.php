<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yz\admin\widgets\GridView;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yz\admin\models\search\RoleSearch $searchModel
 */

$this->title = yz\admin\models\Role::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <div class="btn-toolbar pull-right">
        <?=
        ActionButtons::widget([
            'order' => [['create', 'delete', 'return'],['discover']],
            'buttons' => [
                'discover' => \yii\bootstrap\ButtonDropdown::widget([
                        'tagName' => 'a',
                        'label' => Yii::t('admin/t', 'Permissions'),
                        'encodeLabel' => false,
                        'options' => [
                            'class' => 'btn btn-default',
                        ],
                        'dropdown' => [
                            'items' => [
                                ['label' => Yii::t('admin/t', 'Discover...'), 'url' => Url::to(['discover-auth-items'])],
                                ['label' => Yii::t('admin/t', 'Delete all...'), 'url' => Url::to(['delete-permissions']),
                                'linkOptions' => [
                                    'data-confirm' => Yii::t('admin/t','Do you really want to delete all permissions?'),
                                ]]
                            ]
                        ]
                    ]),
            ],
            'gridId' => 'role-grid',
            'searchModel' => $searchModel,
            'modelClass' => 'yz\admin\models\Role',
        ]) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo GridView::widget([
        'id' => 'role-grid',
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],

            'description:ntext',
            'name',
//            'type',
//            'biz_rule:ntext',
//            'data:ntext',

            [
                'class' => 'yz\admin\widgets\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>

</div>
