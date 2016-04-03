<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yz\admin\assets\AdminAsset;
use yz\icons\Icons;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

AdminAsset::register($this);
Icons::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= Yii::t('admin/t','Administration panel'); ?></title>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=1008">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head(); ?>
</head>
<body class="<?= ArrayHelper::getValue($this->params, 'body-class', 'skin-blue') ?> <?= ArrayHelper::getValue($this->params, 'body-extra-class', 'default-page') ?>">
<?php $this->beginBody() ?>
    <?= $content; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>