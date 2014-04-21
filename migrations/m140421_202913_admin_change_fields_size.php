<?php

use yii\db\Schema;

class m140421_202913_admin_change_fields_size extends \yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('{{%admin_auth_rule}}', 'name', Schema::TYPE_STRING.'(255)');
        $this->alterColumn('{{%admin_auth_item}}', 'rule_name', Schema::TYPE_STRING.'(255)');
        $this->alterColumn('{{%admin_auth_item}}', 'name', Schema::TYPE_STRING.'(255)');
        $this->alterColumn('{{%admin_auth_item_child}}', 'parent', Schema::TYPE_STRING.'(255)');
        $this->alterColumn('{{%admin_auth_item_child}}', 'child', Schema::TYPE_STRING.'(255)');
    }

    public function down()
    {
        $this->alterColumn('{{%admin_auth_item_child}}', 'child', Schema::TYPE_STRING.'(64)');
        $this->alterColumn('{{%admin_auth_item_child}}', 'parent', Schema::TYPE_STRING.'(64)');
        $this->alterColumn('{{%admin_auth_item}}', 'name', Schema::TYPE_STRING.'(64)');
        $this->alterColumn('{{%admin_auth_item}}', 'rule_name', Schema::TYPE_STRING.'(64)');
        $this->alterColumn('{{%admin_auth_rule}}', 'name', Schema::TYPE_STRING.'(64)');
    }
}
