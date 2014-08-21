<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use yz\icons\Icons;

/**
 * @var \yii\base\View $this
 * @var string $content
 */
?>
<?php $this->beginContent('@yz/admin/views/layouts/base.php'); ?>
    <header class="header">
        <a href="<?= Url::home() ?>" class="logo">
            <?= Yii::$app->getModule('admin')->adminTitle ?: Yii::t('admin/t', 'Administration panel') ?>
        </a>
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
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
        </nav>
    </header>
    <div class="wrapper row-offcanvas row-offcanvas-left">
        <aside class="left-side sidebar-offcanvas">
            <section class="sidebar">
                <?= \yz\admin\widgets\MainMenu::widget(); ?>
            </section>
        </aside>
        <aside class="right-side">
            <section class="content-header">
                <?php echo \yii\widgets\Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]); ?>
            </section>
            <section class="content">
                <?php echo \yz\admin\widgets\Alerts::widget(); ?>
                <?= $content; ?>
            </section>
        </aside>
    </div>
<?php $this->endContent(); ?>