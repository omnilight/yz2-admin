<?php

namespace yz\admin\models;


/**
 * Class UserCreate
 * @package \yz\admin\models
 */
class UserCreate extends User
{
    public $password;
    public $passwordRepeat;

    public function rules()
    {
        return array_merge(parent::rules(),[
            [['password','passwordRepeat'], 'required'],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'password' => \Yii::t('admin/t','Password'),
            'passwordRepeat' => \Yii::t('admin/t','Repeat Password'),
        ]);
    }

    public function beforeSave($insert)
    {
        $this->passhash = User::hashPassword($this->password);
        return parent::beforeSave($insert);
    }


} 