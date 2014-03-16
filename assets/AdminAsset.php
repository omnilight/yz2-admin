<?php

namespace yz\admin\backend\assets;

use yii\web\AssetBundle;


/**
 * Class AdminAsset
 * @package yz\admin\assets
 */
class AdminAsset extends AssetBundle
{
	public $publishOptions = [
		'forceCopy' => true,
	];
    /**
     * @inheritdoc
     */
    public $sourcePath = '@yz/admin/assets/adminAsset';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/admin.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/admin.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yz\assets\YzAsset'
    ];
}
