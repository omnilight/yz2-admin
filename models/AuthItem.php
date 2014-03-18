<?php

namespace yz\admin\models;

use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_admin_auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $biz_rule
 * @property string $data
 *
 * @property AuthAssignment $adminAuthAssignment
 * @property AuthItem[] $users
 * @property AuthItemChild $adminAuthItemChild
 */
class AuthItem extends \yz\db\ActiveRecord implements ModelInfoInterface
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%admin_auth_item}}';
	}

	/**
     * Returns model title, ex.: 'Person', 'Book'
     * @return string
     */
    public static function modelTitle()
    {
        return \Yii::t('admin/t', 'Auth Item');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('admin/t', 'Auth Items');
    }

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['type'], 'required'],
			[['type'], 'integer'],
			[['description', 'biz_rule', 'data'], 'string'],
			[['name'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => \Yii::t('admin/t','Name'),
			'type' => \Yii::t('admin/t','Type'),
			'description' => \Yii::t('admin/t','Description'),
			'biz_rule' => \Yii::t('admin/t','Biz Rule'),
			'data' => \Yii::t('admin/t','Data'),
			'adminAuthAssignment' => \Yii::t('admin/t','Admin Auth Assignment'),
			'users' => \Yii::t('admin/t','Users'),
			'adminAuthItemChild' => \Yii::t('admin/t','Admin Auth Item Child'),
		];
	}

	/**
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getAdminAuthAssignment()
	{
		return $this->hasOne(AuthAssignment::className(), ['item_name' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getUsers()
	{
		return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%admin_auth_assignment}}', ['item_name' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getAdminAuthItemChild()
	{
		return $this->hasOne(AuthItemChild::className(), ['parent' => 'name']);
	}
}
