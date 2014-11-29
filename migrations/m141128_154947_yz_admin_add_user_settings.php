<?php

use yii\db\Schema;
use yii\db\Migration;

class m141128_154947_yz_admin_add_user_settings extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%admin_users_settings}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'group' => Schema::TYPE_STRING,
            'name' => Schema::TYPE_STRING,
            'value_raw' => Schema::TYPE_TEXT,
        ], $tableOptions);
        $this->createIndex('user_id', '{{%admin_users_settings}}', 'user_id');
        $this->addForeignKey('{{%fk_admin_user_settings_users}}', '{{%admin_users_settings}}', 'user_id', '{{%admin_users}}', 'id');
    }

    public function down()
    {
        $this->dropTable('{{%admin_users_settings}}');
    }
}
