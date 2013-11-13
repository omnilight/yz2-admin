<?php

namespace yz\admin\models;

use yii\db\ActiveQuery;
use yii\helpers\Security;
use yii\web\IdentityInterface;
use yz\db\ActiveRecord;

/**
 * Class BaseUsers implements admin panel user
 * @property string $login
 * @property string $passhash
 * @property string $auth_key
 * @property bool $is_super_admin
 * @package yz\admin\models
 */
class BaseUsers extends ActiveRecord implements IdentityInterface
{
    const AUTH_KEY_LENGTH = 32;

    public static function tableName()
    {
        return '{{admin_users}}';
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

    }

    /**
     * @inheritdoc
     */
    public function getId()
    {

    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {

    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {

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