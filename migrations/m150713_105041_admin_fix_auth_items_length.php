<?php

use yii\db\Schema;
use yii\db\Migration;

class m150713_105041_admin_fix_auth_items_length extends Migration
{
    public function up()
    {
        $items = (new \yii\db\Query())
            ->select('name')
            ->from('{{%admin_auth_item}}')
            ->column($this->db);

        foreach ($items as $item) {
            if (strlen($item) <= 32) {
                continue;
            }

            $newItem = sprintf('%x', crc32($item)) . '_' . substr($item, -(32-9));

            $this->update('{{%admin_auth_item}}', [
                'name' => $newItem
            ], [
                'name' => $item
            ]);

            echo " rename: ".$item." => ".$newItem."\n";
        }
    }

    public function down()
    {
        return;
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
