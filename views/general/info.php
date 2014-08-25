<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yz\admin\helpers\SystemInfo;
use yz\admin\widgets\Box;

/**
 * @var \yii\web\View $this
 */

$this->title = Yii::t('admin/sysinfo','General system info');
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = Yii::t('admin/sysinfo','General system info')
?>

<div class="row">
    <div class="col-md-6">
        <?php Box::begin(['title' => Yii::t('admin/sysinfo','Server')]) ?>
        <table class="table">
            <tr>
                <td><?= Yii::t('admin/sysinfo','OS') ?></td>
                <td><?= SystemInfo::getOS() ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('admin/sysinfo','Server system') ?></td>
                <td><?= SystemInfo::getServerSoftware() ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('admin/sysinfo','CPU Architecture') ?></td>
                <td><?= SystemInfo::getArchitecture() ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('admin/sysinfo','Load averages') ?></td>
                <td><?= implode(', ', SystemInfo::getLoadAverage()) ?></td>
            </tr>
        </table>
        <?php Box::end() ?>
    </div>
    <div class="col-md-6">
        <?php Box::begin(['title' => Yii::t('admin/sysinfo','Software')]) ?>
        <table class="table">
            <tr>
                <td><?= Yii::t('admin/sysinfo','PHP') ?></td>
                <td><?= SystemInfo::getPhpVersion() ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('admin/sysinfo','DB') ?></td>
                <td><?= SystemInfo::getDbType() ?></td>
            </tr>
            <tr>
                <td><?= Yii::t('admin/sysinfo','DB Version') ?></td>
                <td><?= SystemInfo::getDbVersion() ?></td>
            </tr>
        </table>
        <?php Box::end() ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php Box::begin(['title' => Yii::t('admin/sysinfo','Engine')]) ?>
        <table class="table">
            <tr>
                <td><?= Yii::t('admin/sysinfo','Yii') ?></td>
                <td><?= Yii::getVersion() ?></td>
            </tr>
        </table>
        <?php Box::end() ?>
    </div>
</div>