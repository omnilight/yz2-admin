<?php

namespace yz\admin\widgets;

use yii\web\AssetBundle;

class MainMenuAsset extends AssetBundle
{
	public $publishOptions = [
		'forceCopy' => true,
	];
	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@yz/admin/widgets/assets/mainMenu';
	/**
	 * @inheritdoc
	 */
	public $css = [
		'menu.css',
	];
}