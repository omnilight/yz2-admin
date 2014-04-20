<?php

use yii\db\Schema;

class m140417_192304_change_auth_tables extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $this->createTable('{{%admin_auth_rule}}', [
            'name' => 'string(64) NOT NULL',
            'data' => 'text',
            'created_at' => 'integer',
            'updated_at' => 'integer',
            'PRIMARY KEY (`name`)',
        ], $tableOptions);

        $this->dropColumn('{{%admin_auth_item}}', 'biz_rule');
        $this->addColumn('{{%admin_auth_item}}', 'created_at', 'integer');
        $this->addColumn('{{%admin_auth_item}}', 'updated_at', 'integer');
        $this->addColumn('{{%admin_auth_item}}', 'rule_name', 'string(64)');
        $this->createIndex('rule_name', '{{%admin_auth_item}}', 'rule_name');
        $this->addForeignKey('{{%fk_admin_auth_item_rule_name}}', '{{%admin_auth_item}}', 'rule_name',
            '{{%admin_auth_rule}}', 'name', 'SET NULL', 'CASCADE');

        $this->dropColumn('{{%admin_auth_assignment}}', 'biz_rule');
        $this->dropColumn('{{%admin_auth_assignment}}', 'data');
        $this->addColumn('{{%admin_auth_assignment}}', 'created_at', 'integer');
    }

    public function down()
    {
        $this->dropColumn('{{%admin_auth_assignment}}', 'created_at');
        $this->addColumn('{{%admin_auth_assignment}}', 'data', 'text');
        $this->addColumn('{{%admin_auth_assignment}}', 'biz_rule', 'text');
        $this->dropForeignKey('{{%fk_admin_auth_item_rule_name}}', '{{%admin_auth_item}}');
        $this->dropColumn('{{%admin_auth_item}}', 'rule_name');
        $this->dropColumn('{{%admin_auth_item}}', 'updated_at');
        $this->dropColumn('{{%admin_auth_item}}', 'created_at');
        $this->addColumn('{{%admin_auth_item}}', 'biz_rule', 'text');
        $this->dropTable('{{%admin_auth_rule}}');
    }
}
