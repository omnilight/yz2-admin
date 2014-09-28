<?php

namespace yz\admin\widgets;

use yii\web\AssetBundle;


/**
 * Class ActionButtonsAsset
 */
class ActionButtonsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@yz/admin/widgets/assets/actionButtons';
    /**
     * @inheritdoc
     */
    public $js = [
        'actions.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yz\admin\assets\AdminAsset',
    ];
} 