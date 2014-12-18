<?php

use yii\db\Schema;
use yii\db\Migration;

class m141218_111729_yz_admin_change_user_settings extends Migration
{
    public function up()
    {
        $this->dropColumn('{{%admin_users_settings}}', 'group');
    }

    public function down()
    {
        echo "m141218_111729_yz_admin_change_user_settings cannot be reverted.\n";

        return false;
    }
}
