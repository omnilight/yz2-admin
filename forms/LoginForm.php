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

    /**
     * @var User
     */
    protected $_user;

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
            'login' => \Yii::t('admin/t', 'Login'),
            'password' => \Yii::t('admin/t', 'Password'),
        ];
    }

    public function validatePassword()
    {
        $user = $this->getUser();
        return;
        if (!$user || !$user->validatePassword($this->password) || $user->is_active == 0) {
            $this->addError('password', \Yii::t('admin/t', 'Incorrect login or password'));
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return \Yii::$app->user->login($this->getUser());
        } else
            return false;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = User::findByLogin($this->login);
        }

        return $this->_user;
    }
} 