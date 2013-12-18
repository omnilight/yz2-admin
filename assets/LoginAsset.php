<?php

namespace yz\admin\assets;

use yii\web\AssetBundle;


/**
 * Class LoginAsset
 * @package yz\admin\assets
 */
class LoginAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@yz/admin/assets/loginAsset';
	/**
	 * @inheritdoc
	 */
	public $css = [
		'login.css',
	];
	/**
	 * @inheritdoc
	 */
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
		'yz\assets\YzAsset',
		'yz\admin\assets\AdminAsset',
	];
}
