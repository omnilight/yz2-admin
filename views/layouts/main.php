<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use yz\admin\widgets\SystemEvents;
use yz\icons\Icons;

/**
 * @var \yii\base\View $this
 * @var string $content
 */
?>
<?php $this->beginContent('@yz/admin/views/layouts/base.php'); ?>
    <!-- Site wrapper -->
    <div class="wrapper">
    <header class="main-header">
        <a href="<?= Url::home() ?>" class="logo">
            <small><?= Yii::$app->getModule('admin')->adminTitle ?: Yii::t('admin/t', 'Administration panel') ?></small>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?= Yii::t('admin/t','Toggle navigation') ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <?php $events = SystemEvents::begin() ?>
                    <?php if ($events->items): ?>
                        <li class="dropdown notifications-menu">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <?= Icons::i('warning fa-fw') ?>
                                <span class="label label-warning"><?= count($events->items) ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">
                                    <?= Yii::t('admin/t', 'You have {n} notifications', ['n' => count($events->items)]) ?>
                                </li>
                                <li>
                                    <ul class="menu">
                                        <?php foreach ($events->items as $event): ?>
                                            <li>
                                                <a href="<?= Url::to(['/admin/system-events/view', 'id' => $event->id]) ?>" class="clearfix">
                                                    <div class="pull-left"><?= Icons::i('warning ' . $event->type) ?></div>
                                                    <span><?= $event->message ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php endif ?>
                    <?php SystemEvents::end() ?>

                    <li class="dropdown user user-menu">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <?= Icons::i('user fa-fw') ?>
                            <span><?= Html::encode(Yii::$app->user->identity->name) ?> <i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header bg-light-blue">
                                <p>
                                    <?= Yii::$app->user->identity->name ?>
                                    <small><?= Yii::$app->user->identity->email ?></small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a class="btn btn-default btn-flat" href="<?= Url::to(['/admin/profile/index']) ?>"><?= Yii::t('admin/t', 'Your profile') ?></a>
                                </div>
                                <div class="pull-right">
                                    <a class="btn btn-default btn-flat" href="<?= Url::to(['/admin/main/logout']) ?>"><?= Yii::t('admin/t', 'Logout') ?></a>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <?= \yz\admin\widgets\MainMenu::widget(); ?>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <?php if (isset($this->params['header'])): ?>
                <h1><?= $this->params['header'] ?></h1>
            <?php endif ?>
            <?php echo \yii\widgets\Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]); ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <?php echo \yz\admin\widgets\Alerts::widget(); ?>
            <?= $content; ?>
        </section>
    </div>
<?php $this->endContent(); ?>