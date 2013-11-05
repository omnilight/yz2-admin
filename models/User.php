<?php

namespace yz\admin\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User implements admin panel user
 * @package yz\admin\models
 */
class User extends ActiveRecord implements IdentityInterface
{
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

}