<?php

class m131214_130539_yz_admin_add_default_admin extends \yii\db\Migration
{
    public function up()
    {
        \yii\helpers\Console::output("No more admin creation here. Use admin-users/create command instead");

        return true;
    }

    public function down()
    {
        \yii\helpers\Console::output("No more admin delete here. Use admin-users/delete command instead");
        return true;
    }
}
