<?php

namespace yz\admin\models;


use app\models\backend\Users;
use yii\base\Model;

/**
 * Class LoginForm
 * @package yz\admin\models
 */
class LoginForm extends Model
{
    public $login;
    public $password;

    public function rules()
    {
        return [
            ['login, password', 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword()
    {
        /** @var Users $user */
        $user = Users::findByLogin($this->login)->one();
        return $user->validatePassword($this->password);
    }

    public function login()
    {
        if($this->validate()) {
            $user = Users::findByLogin($this->login)->one();
            \Yii::$app->user->login($user);
            return true;
        } else
            return false;
    }
} 