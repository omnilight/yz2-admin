<?php

namespace yz\admin\models;

use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_admin_auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $childRecord
 * @property AuthItem $parentRecord
 */
class AuthItemChild extends \yz\db\ActiveRecord implements ModelInfoInterface
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%admin_auth_item_child}}';
	}

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return \Yii::t('yz/admin', 'Auth Item Child');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('yz/admin', 'Auth Item Children');
    }

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['parent', 'child'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'parent' => \Yii::t('yz/admin','Parent'),
			'child' => \Yii::t('yz/admin','Child'),
			'childRecord' => \Yii::t('yz/admin','Child'),
			'parentRecord' => \Yii::t('yz/admin','Parent'),
		];
	}

	/**
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getChildRecord()
	{
		return $this->hasOne(AuthItem::className(), ['name' => 'child']);
	}

	/**
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getParentRecord()
	{
		return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
	}
}
