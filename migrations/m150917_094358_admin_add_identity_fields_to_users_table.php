<?php

use yii\db\Migration;

class m150917_094358_admin_add_identity_fields_to_users_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%admin_users}}', 'is_identity', $this->boolean()->defaultValue(0));
        $this->addColumn('{{%admin_users}}', 'profile_finder_class', $this->string());
    }

    public function down()
    {
        $this->dropColumn('{{%admin_users}}', 'profile_finder_class');
        $this->dropColumn('{{%admin_users}}', 'is_identity');
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
