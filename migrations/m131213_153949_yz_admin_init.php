<?php

use yii\db\Schema;

class m131213_153949_yz_admin_init extends \yii\db\Migration
{
	public function up()
	{
        $this->createTable('{{%admin_users}}',[
            'id' => Schema::TYPE_PK,
            'login' => Schema::TYPE_STRING.'(32)',
            'passhash' => Schema::TYPE_STRING,
            'auth_key' => Schema::TYPE_STRING,
            'is_super_admin' => Schema::TYPE_BOOLEAN,
            'is_active' => Schema::TYPE_BOOLEAN,
            'name' => Schema::TYPE_STRING.'(64)',
            'email' => Schema::TYPE_STRING,
            'login_time' => Schema::TYPE_DATETIME,
            'create_time' => Schema::TYPE_DATETIME,
            'update_time' => Schema::TYPE_DATETIME,
        ], 'ENGINE=InnoDB CHARSET=utf8');

        $this->createTable('{{%admin_auth_item}}',[
            'name' => Schema::TYPE_STRING.'(64)',
            'type' => Schema::TYPE_INTEGER.' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'biz_rule' => Schema::TYPE_TEXT,
            'data' => Schema::TYPE_TEXT,
            'PRIMARY KEY (`name`)',
        ], 'ENGINE=InnoDB CHARSET=utf8');

        $this->createIndex('type', '{{%admin_auth_item}}', 'type');

        $this->createTable('{{%admin_auth_item_child}}',[
            'parent' => Schema::TYPE_STRING.'(64)',
            'child' => Schema::TYPE_STRING.'(64)',
            'PRIMARY KEY (`parent`, `child`)',
        ], 'ENGINE=InnoDB CHARSET=utf8');

        $this->createIndex('parent', '{{%admin_auth_item_child}}', 'parent');
        $this->createIndex('child', '{{%admin_auth_item_child}}', 'child');
        $this->addForeignKey('parent','{{%admin_auth_item_child}}', 'parent',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('child','{{%admin_auth_item_child}}', 'child',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->createTable('{{%admin_auth_assignment}}',[
            'item_name' => Schema::TYPE_STRING.'(64)',
            'user_id' => Schema::TYPE_INTEGER,
            'biz_rule' => Schema::TYPE_TEXT,
            'data' => Schema::TYPE_TEXT,
            'PRIMARY KEY (`item_name`, `user_id`)'
        ], 'ENGINE=InnoDB CHARSET=utf8');
        $this->createIndex('item_name', '{{%admin_auth_assignment}}', 'item_name');
        $this->createIndex('user_id', '{{%admin_auth_assignment}}', 'user_id');
        $this->addForeignKey('item_name','{{%admin_auth_assignment}}', 'item_name',
            '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('user_id', '{{%admin_auth_assignment}}', 'user_id',
            '{{%admin_users}}', 'id');

        return true;
	}

	public function down()
	{
        $this->dropTable('{{%admin_auth_item_child}}');
        $this->dropTable('{{%admin_auth_assignment}}');
        $this->dropTable('{{%admin_auth_item}}');
        $this->dropTable('{{%admin_users}}');
		return true;
	}
}
