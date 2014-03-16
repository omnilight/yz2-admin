<?php

namespace yz\admin\models;

use yii\db\ActiveQuery;
use yii\helpers\Security;
use yii\web\IdentityInterface;

/**
 * Class BaseUser implements admin panel user
 * @property integer $id
 * @property string $login
 * @property string $passhash
 * @property string $auth_key
 * @property boolean $is_super_admin
 * @property boolean $is_active
 * @property string $name
 * @property string $email
 * @property string $logged_at
 * @property string $created_at
 * @property string $updated_at
 * @package yz\admin\models
 */
class User extends \yz\db\ActiveRecord implements IdentityInterface
{
	const AUTH_KEY_LENGTH = 32;

	public static function tableName()
	{
		return '{{%admin_users}}';
	}

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

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['is_super_admin', 'is_active'], 'boolean'],
			[['login_time', 'create_time', 'update_time'], 'safe'],
			[['login'], 'string', 'max' => 32],
			[['passhash', 'auth_key', 'email'], 'string', 'max' => 255],
			[['name'], 'string', 'max' => 64]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => \Yii::t('yz/admin','ID'),
			'login' => \Yii::t('yz/admin','Login'),
			'passhash' => \Yii::t('yz/admin','Passhash'),
			'auth_key' => \Yii::t('yz/admin','Auth Key'),
			'is_super_admin' => \Yii::t('yz/admin','Is Super Admin'),
			'is_active' => \Yii::t('yz/admin','Is Active'),
			'name' => \Yii::t('yz/admin','Name'),
			'email' => \Yii::t('yz/admin','Email'),
			'login_time' => \Yii::t('yz/admin','Login Time'),
			'create_time' => \Yii::t('yz/admin','Create Time'),
			'update_time' => \Yii::t('yz/admin','Update Time'),
			'adminAuthAssignment' => \Yii::t('yz/admin','Admin Auth Assignment'),
			'itemNames' => \Yii::t('yz/admin','Item Names'),
		];
	}


	/**
	 * @return bool
	 */
	public function getIsSuperAdmin()
	{
		return $this->is_super_admin;
	}

	/**
	 * @param bool $isSuperAdmin
	 * @property bool
	 */
	public function setIsSuperAdmin($isSuperAdmin)
	{
		$this->is_super_admin = $isSuperAdmin;
	}

	/**
	 * @param $login
	 * @return ActiveQuery
	 */
	public static function findByLogin($login)
	{
		return static::find()
			->where(['login' => $login]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentity($id)
	{
		return static::find($id);
	}

	/**
	 * Finds an identity by the given secrete token.
	 * @param string $token the secrete token
	 * @return IdentityInterface the identity object that matches the given token.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentityByAccessToken($token)
	{
		return null;
	}


	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * @param $password
	 * @return bool
	 */
	public function validatePassword($password)
	{
		if($this->isNewRecord)
			return false;

		return Security::validatePassword($password, $this->passhash);
	}

	/**
	 * @param $password
	 * @return string
	 */
	public static function hashPassword($password)
	{
		return Security::generatePasswordHash($password);
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert)) {
			if($this->isNewRecord) {
				$this->auth_key = Security::generateRandomKey(static::AUTH_KEY_LENGTH);
			}
			return true;
		} else
			return false;
	}
}
