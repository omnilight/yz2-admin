<?php

namespace yz\admin\models;


/**
 * Class User
 */
class User extends BaseUser implements AdminableInterface
{
	/**
	 * Returns model title, ex.: 'Person', 'Book'
	 * @return string
	 */
	public static function modelTitle()
	{
		return \Yii::t('yz/admin', 'Administrator');
	}

	/**
	 * Returns plural form of the model title, ex.: 'Persons', 'Books'
	 * @return string
	 */
	public static function modelTitlePlural()
	{
		return \Yii::t('yz/admin', 'Administrators');
	}
} 