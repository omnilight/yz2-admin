<?php

namespace yz\admin\forms;
use yii\base\Model;
use Yii;
use yz\admin\models\User;


/**
 * Class ChangeUserPasswordForm
 * @package \yz\admin\forms
 */
class ChangeUserPasswordForm extends Model
{
    public $oldPassword;
    public $password;
    public $passwordRepeat;

    public $askOldPassword = true;

    /**
     * @var User
     */
    protected $_user;

    /**
     * @param User $user
     * @param array $config
     */
    public function __construct($user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }


    public function rules()
    {
        $rules = [
            [['password','passwordRepeat'], 'required'],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password']
        ];

        if ($this->askOldPassword) {
            $rules[] = [['oldPassword'], 'required'];
            $rules[] = [['oldPassword'], 'myOldPasswordValidator'];
        }

        return $rules;
    }

    public function myOldPasswordValidator()
    {
        if ($this->_user->validatePassword($this->oldPassword) == false)
            $this->addError('oldPassword',Yii::t('yz/admin','Old password is incorrect'));
    }

    public function attributeLabels()
    {
        return [
            'password' => \Yii::t('yz/admin','Password'),
            'passwordRepeat' => \Yii::t('yz/admin','Repeat Password'),
        ];
    }

    public function process()
    {
        if ($this->validate()) {

            $this->_user->passhash = User::hashPassword($this->password);
            return $this->_user->save();
        }
        return false;
    }
}