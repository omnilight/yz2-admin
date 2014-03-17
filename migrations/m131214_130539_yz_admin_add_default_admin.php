<?php

class m131214_130539_yz_admin_add_default_admin extends \yii\db\Migration
{
    public function up()
    {
        $validator = new \yii\validators\StringValidator(['min' => 1,]);
        $password = \yii\helpers\Console::prompt('Enter administrator password', [
            'default' => 'password',
            'validator' => [$validator, 'validate'],
        ]);
        $validator = new \yii\validators\EmailValidator();
        $email = \yii\helpers\Console::prompt('Enter administrator email', [
            'default' => 'admin@domain.com',
            'validator' => [$validator, 'validate'],
        ]);

        $this->insert('{{%admin_users}}', [
            'login' => 'admin',
            'passhash' => \yii\helpers\Security::generatePasswordHash($password),
            'is_super_admin' => 1,
            'auth_key' => \yii\helpers\Security::generateRandomKey(\yz\admin\models\User::AUTH_KEY_LENGTH),
            'email' => $email,
            'name' => Yii::t('yz/admin', 'Administrator'),
            'create_time' => new \yii\db\Expression('NOW()'),
        ]);

        \yii\helpers\Console::output("Initial administration panel user was created!");
        \yii\helpers\Console::output("Login: admin\nPassword: {$password}");

        return true;
    }

    public function down()
    {
        $this->delete('{{%admin_users}}', 'login = :login', [
            ':login' => 'admin',
        ]);
        return true;
    }
}
