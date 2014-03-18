<?php
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 */

$this->title = Yii::t('admin/sysinfo','General system info');
$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?= Yii::t('admin/sysinfo','General system info') ?></h1>

<table class="table">
    <tr>
        <td><?= Yii::t('admin/sysinfo','Server') ?></td>
        <td><?= php_uname() ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('admin/sysinfo','PHP version') ?></td>
        <td><?= phpversion() ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('admin/sysinfo','Yii version') ?></td>
        <td><?= Yii::getVersion() ?></td>
    </tr>
</table>