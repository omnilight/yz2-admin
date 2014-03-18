<?php

namespace yz\admin\models;

use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use yz\db\ActiveRecord;
use yz\interfaces\ModelInfoInterface;

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
class User extends \yz\db\ActiveRecord implements IdentityInterface, ModelInfoInterface
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
        return \Yii::t('admin/t', 'Administrator');
    }

    /**
     * Returns plural form of the model title, ex.: 'Persons', 'Books'
     * @return string
     */
    public static function modelTitlePlural()
    {
        return \Yii::t('admin/t', 'Administrators');
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function($event) { return new Expression('NOW()'); }
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login','email','name'], 'required'],
            [['is_super_admin', 'is_active'], 'boolean'],
            [['logged_at', 'created_at', 'updated_at'], 'safe'],
            [['login'], 'string', 'max' => 32],
            [['passhash', 'auth_key', 'email'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('admin/t', 'ID'),
            'login' => \Yii::t('admin/t', 'Login'),
            'passhash' => \Yii::t('admin/t', 'Passhash'),
            'auth_key' => \Yii::t('admin/t', 'Auth Key'),
            'is_super_admin' => \Yii::t('admin/t', 'Is Super Admin'),
            'is_active' => \Yii::t('admin/t', 'Is Active'),
            'name' => \Yii::t('admin/t', 'Name'),
            'email' => \Yii::t('admin/t', 'Email'),
            'login_time' => \Yii::t('admin/t', 'Login Time'),
            'created_at' => \Yii::t('admin/t', 'Create Time'),
            'updated_at' => \Yii::t('admin/t', 'Update Time'),
            'adminAuthAssignment' => \Yii::t('admin/t', 'Admin Auth Assignment'),
            'itemNames' => \Yii::t('admin/t', 'Item Names'),
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
     * @throws \yii\base\NotSupportedException
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
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
        if ($this->isNewRecord)
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
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Security::generateRandomKey(static::AUTH_KEY_LENGTH);
            }
            return true;
        } else
            return false;
    }
}
