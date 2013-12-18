<?php

namespace yz\admin\models;

use yii\db\ActiveQuery;
use yii\helpers\Security;
use yii\web\IdentityInterface;

/**
 * Class BaseUser implements admin panel user
 * @property string $login
 * @property string $passhash
 * @property string $auth_key
 * @property bool $is_super_admin
 * @package yz\admin\models
 */
class BaseUser extends \yz\db\ActiveRecord implements IdentityInterface
{
    const AUTH_KEY_LENGTH = 32;

    public static function tableName()
    {
        return '{{%admin_users}}';
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
