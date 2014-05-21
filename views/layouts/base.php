<?php
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
    <?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody() ?>
    <?= $content; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>