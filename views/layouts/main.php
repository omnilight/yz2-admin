<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yz\icons\Icons;

/**
 * @var \yii\base\View $this
 * @var string $content
 */
?>
<?php $this->beginContent('@yz/admin/views/layouts/base.php'); ?>
    <!-- Top bar-->
    <nav class="navbar navbar-fixed-top b-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand"
                   href="<?= Yii::$app->homeUrl ?>"><?= Yii::$app->getModule('admin')->adminTitle ?: Yii::t('admin/t', 'Administration panel') ?></a>
            </div>

            <?php echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'encodeLabels' => false,
                'items' => [
                    [
                        'label' => Icons::p('user fa-fw') . Html::encode(Yii::$app->user->identity->name),
                        'items' => [
                            ['label' => Icons::p('user fa-fw') . Yii::t('admin/t', 'Your profile'), 'url' => ['/admin/profile/index']],
                            ['label' => Icons::p('power-off fa-fw') . Yii::t('admin/t', 'Logout'), 'url' => ['/admin/main/logout']],
                        ]
                    ]
                ]
            ]); ?>
        </div>
    </nav>

<?= \yz\admin\widgets\MainMenu::widget(); ?>

<?php echo \yii\widgets\Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]); ?>

<?php echo \yz\admin\widgets\Alerts::widget(); ?>

    <div class="container-fluid b-content">
        <div class="col-md-12">

            <?= \yz\admin\widgets\Alerts::widget(); ?>

            <?= $content; ?>
        </div>
    </div>
<?php $this->endContent(); ?>