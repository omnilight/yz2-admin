<?php
use yii\helpers\Html;
use yz\admin\assets\AdminAsset;

/**
 * @var \yii\base\View $this
 * @var string $content
 */

AdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <title><?= Yii::t('yz/admin','Administration panel'); ?></title>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody() ?>
    <?= $content; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>