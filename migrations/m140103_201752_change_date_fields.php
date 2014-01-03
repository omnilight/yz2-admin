<?php

use yii\db\Schema;

class m140103_201752_change_date_fields extends \yii\db\Migration
{
	public function up()
	{
		$this->renameColumn('{{%admin_users}}', 'login_time', 'logged_on');
		$this->renameColumn('{{%admin_users}}', 'create_time', 'created_on');
		$this->renameColumn('{{%admin_users}}', 'update_time', 'updated_on');
	}

	public function down()
	{
		$this->renameColumn('{{%admin_users}}', 'logged_on', 'login_time');
		$this->renameColumn('{{%admin_users}}', 'created_on', 'create_time');
		$this->renameColumn('{{%admin_users}}', 'updated_on', 'update_time');
	}
}
