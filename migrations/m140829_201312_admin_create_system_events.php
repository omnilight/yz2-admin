<?php

use yii\db\Schema;
use yii\db\Migration;

class m140829_201312_admin_create_system_events extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%admin_system_events}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'type' => Schema::TYPE_STRING.'(16)',
            'message' => Schema::TYPE_TEXT,
            'url_raw' => Schema::TYPE_TEXT,
            'created_at' => Schema::TYPE_DATETIME,
            'is_viewed' => Schema::TYPE_BOOLEAN.' DEFAULT 0',
            'last_viewed_at' => Schema::TYPE_DATETIME,
            'FOREIGN KEY (user_id) REFERENCES {{%admin_users}} (id) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%admin_system_events}}');
    }
}
