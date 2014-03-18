<?php

namespace yz\admin\forms;

use yii\base\Model;
use yz\admin\models\User;

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
            [['login', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => \Yii::t('yz/admin', 'Login'),
            'password' => \Yii::t('yz/admin', 'Password'),
        ];
    }


    public function validatePassword()
    {
        /** @var User $user */
        $user = User::findByLogin($this->login)->one();
        if (!$user || !$user->validatePassword($this->password)) {
            $this->addError('password', \Yii::t('yz/admin', 'Incorrect login or password'));
        }
    }

    public function login()
    {
        if ($this->validate()) {
            $user = User::findByLogin($this->login)->one();
            \Yii::$app->user->login($user);
            return true;
        } else
            return false;
    }
} 