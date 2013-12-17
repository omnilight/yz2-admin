<?php

namespace yz\admin;


/**
 * Class Extension
 */
class Extension extends \yii\base\Extension
{
	public static function init()
	{
		parent::init();

		\Yii::$app->i18n->translations['yz/admin'] = [
			'class' => 'yii\i18n\PhpMessageSource',
			'basePath' => '@yz/admin/messages',
			'sourceLanguage' => 'en-US',
		];
	}

} 