<?php

namespace yz\admin\models;

use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_admin_auth_assignment".
 *
 * @property string $item_name
 * @property integer $user_id
 * @property string $biz_rule
 * @property string $data
 *
 * @property AdminUsers $user
 * @property AdminAuthItem $itemName
 */
class AuthAssignment extends \yz\db\ActiveRecord implements ModelInfoInterface
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%admin_auth_assignment}}';
	}

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return \Yii::t('admin/t', 'Auth Assignment');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('admin/t', 'Auth Assignments');
    }

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id'], 'integer'],
			[['biz_rule', 'data'], 'string'],
			[['item_name'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'item_name' => \Yii::t('admin/t','Item Name'),
			'user_id' => \Yii::t('admin/t','User ID'),
			'biz_rule' => \Yii::t('admin/t','Biz Rule'),
			'data' => \Yii::t('admin/t','Data'),
			'user' => \Yii::t('admin/t','User'),
			'itemName' => \Yii::t('admin/t','Item Name'),
		];
	}

	/**
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}

	/**
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getItemName()
	{
		return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
	}
}
