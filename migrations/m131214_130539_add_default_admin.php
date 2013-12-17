<?php

use yii\db\Schema;

class m131214_130539_add_default_admin extends \yii\db\Migration
{
	public function up()
	{
        $validator = new \yii\validators\StringValidator(['min' => 1,]);
        $password = \yii\helpers\Console::prompt('Enter administrator password', [
            'default' => 'password',
            'validator' => [$validator, 'validate'],
        ]);

        $this->insert('{{%admin_users}}',[
            'login' => 'admin',
            'passhash' => \yii\helpers\Security::generatePasswordHash($password),
            'is_super_admin' => 1,
            'email' => 'admin@example.com',
            'name' => Yii::t('yz/admin','Administrator'),
        ]);

        return true;
	}

	public function down()
	{
		$this->delete('{{%admin_users}}', 'id = 1 AND email = :email',[
            ':email' => 'admin@example.com',
        ]);
		return true;
	}
}
