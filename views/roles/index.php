<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yz\admin\widgets\Box;
use yz\admin\grid\GridView;
use yz\admin\widgets\ActionButtons;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var yz\admin\models\search\RoleSearch $searchModel
 */

$this->title = yz\admin\models\Role::modelTitlePlural();
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?php $box = Box::begin(['cssClass' => 'box-primary']) ?>
<div class="text-right">
    <?php echo ActionButtons::widget([
        'order' => [['discover'], ['create', 'delete', 'return']],
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
<?php Box::end() ?>
