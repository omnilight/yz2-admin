<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use yz\admin\assets\AdminAsset;

/**
 * @var \yii\base\View $this
 * @var string $content
 */
?>
<?php $this->beginContent('@yz/admin/views/layouts/base'); ?>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::t('yz/admin','Administration panel'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ]
    ]);

        echo Nav::widget([

        ]);

    NavBar::end();
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <?= \yz\admin\widgets\MainMenu::widget(); ?>
            </div>
            <div class="col-md-8">
                <?= $content; ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>