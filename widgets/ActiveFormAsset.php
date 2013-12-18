<?php

namespace yz\admin\widgets;

use yii\web\AssetBundle;

class ActiveFormAsset extends AssetBundle
{
	/**
	 * @inheritdoc
	 */
	public $sourcePath = '@yz/admin/widgets/assets/activeForm';
	/**
	 * @inheritdoc
	 */
	public $css = [
		'form.css',
	];
}