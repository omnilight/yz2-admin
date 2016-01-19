<?php

use yii\db\Migration;

class m160119_143540_admin_add_login_history extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%admin_login_history_records}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'remote_address' => $this->string(40),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%admin_login_history_records}}', 'user_id');
        $this->addForeignKey('{{%fk-login_history-admin-users}}',
            '{{%admin_login_history_records}}', 'user_id',
            '{{%admin_users}}', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    public function down()
    {
        $this->dropTable('{{%admin_login_history_records}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
