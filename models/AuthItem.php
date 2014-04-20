<?php

namespace yz\admin\models;

use yz\interfaces\ModelInfoInterface;

/**
 * This is the model class for table "yz_admin_auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $rule_name
 *
 * @property AuthAssignment $adminAuthAssignment
 * @property AuthItem[] $users
 * @property AuthItemChild $adminAuthItemChild
 * @property AuthRule $rules
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
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64]
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
            'created_at' => \Yii::t('admin/t', 'Created At'),
            'updated_at' => \Yii::t('admin/t', 'Updated At'),
            'rule_name' => \Yii::t('admin/t', 'Rule Name'),
		];
	}

	/**
	 * @return \yii\db\ActiveRecord
	 */
	public function getAdminAuthAssignment()
	{
		return $this->hasOne(AuthAssignment::className(), ['item_name' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveRecord
	 */
	public function getUsers()
	{
		return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%admin_auth_assignment}}', ['item_name' => 'name']);
	}

	/**
	 * @return \yii\db\ActiveRecord
	 */
	public function getAdminAuthItemChild()
	{
		return $this->hasOne(AuthItemChild::className(), ['parent' => 'name']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }
}
