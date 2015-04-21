<?php

use yii\db\Schema;

class m140421_202913_admin_change_fields_size extends \yii\db\Migration
{
    public function up()
    {
        $this->dropForeignKey('{{%fk_admin_auth_item_rule_name}}', '{{%admin_auth_item}}');
        $this->dropForeignKey('{{%fk_admin_auth_item_child_child}}', '{{%admin_auth_item_child}}');
        $this->dropForeignKey('{{%fk_admin_auth_item_child_parent}}', '{{%admin_auth_item_child}}');
        $this->dropForeignKey('{{%fk_admin_auth_assignment_item_name}}', '{{%admin_auth_assignment}}');

        $this->alterColumn('{{%admin_auth_rule}}', 'name', Schema::TYPE_STRING.'(255)');
        $this->alterColumn('{{%admin_auth_item}}', 'rule_name', Schema::TYPE_STRING.'(255)');
        $this->alterColumn('{{%admin_auth_item}}', 'name', Schema::TYPE_STRING.'(255)');
        $this->alterColumn('{{%admin_auth_item_child}}', 'parent', Schema::TYPE_STRING.'(255)');
        $this->alterColumn('{{%admin_auth_item_child}}', 'child', Schema::TYPE_STRING.'(255)');

        $this->addForeignKey('{{%fk_admin_auth_item_child_parent}}', '{{%admin_auth_item_child}}', 'parent',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk_admin_auth_item_child_child}}', '{{%admin_auth_item_child}}', 'child',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk_admin_auth_item_rule_name}}', '{{%admin_auth_item}}', 'rule_name',
            '{{%admin_auth_rule}}', 'name', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%fk_admin_auth_assignment_item_name}}', '{{%admin_auth_assignment}}', 'item_name',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('{{%fk_admin_auth_item_rule_name}}', '{{%admin_auth_item}}');
        $this->dropForeignKey('{{%fk_admin_auth_item_child_child}}', '{{%admin_auth_item_child}}');
        $this->dropForeignKey('{{%fk_admin_auth_item_child_parent}}', '{{%admin_auth_item_child}}');
        $this->dropForeignKey('{{%fk_admin_auth_assignment_item_name}}', '{{%admin_auth_assignment}}');

        $this->alterColumn('{{%admin_auth_item_child}}', 'child', Schema::TYPE_STRING.'(64)');
        $this->alterColumn('{{%admin_auth_item_child}}', 'parent', Schema::TYPE_STRING.'(64)');
        $this->alterColumn('{{%admin_auth_item}}', 'name', Schema::TYPE_STRING.'(64)');
        $this->alterColumn('{{%admin_auth_item}}', 'rule_name', Schema::TYPE_STRING.'(64)');
        $this->alterColumn('{{%admin_auth_rule}}', 'name', Schema::TYPE_STRING.'(64)');

        $this->addForeignKey('{{%fk_admin_auth_item_child_parent}}', '{{%admin_auth_item_child}}', 'parent',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk_admin_auth_item_child_child}}', '{{%admin_auth_item_child}}', 'child',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk_admin_auth_item_rule_name}}', '{{%admin_auth_item}}', 'rule_name',
            '{{%admin_auth_rule}}', 'name', 'SET NULL', 'CASCADE');
        $this->addForeignKey('{{%fk_admin_auth_assignment_item_name}}', '{{%admin_auth_assignment}}', 'item_name',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
    }
}
