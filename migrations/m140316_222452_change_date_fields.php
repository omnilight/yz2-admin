<?php

class m140316_222452_change_date_fields extends \yii\db\Migration
{
    public function up()
    {
        $this->renameColumn('{{%admin_users}}', 'logged_on', 'logged_at');
        $this->renameColumn('{{%admin_users}}', 'created_on', 'created_at');
        $this->renameColumn('{{%admin_users}}', 'updated_on', 'updated_at');
    }

    public function down()
    {
        $this->renameColumn('{{%admin_users}}', 'logged_at', 'logged_on');
        $this->renameColumn('{{%admin_users}}', 'created_at', 'created_on');
        $this->renameColumn('{{%admin_users}}', 'updated_at', 'updated_on');
    }
}
