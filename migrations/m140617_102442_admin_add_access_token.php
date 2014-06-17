<?php

use yii\db\Schema;

class m140617_102442_admin_add_access_token extends \yii\db\Migration
{
    public function up()
    {
        $this->addColumn('{{%admin_users}}', 'access_token', Schema::TYPE_STRING.'(32)');
    }

    public function down()
    {
        $this->dropColumn('{{%admin_users}}', 'access_token');
    }
}
