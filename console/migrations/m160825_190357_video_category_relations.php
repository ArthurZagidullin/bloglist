<?php

use yii\db\Migration;

class m160825_190357_video_category_relations extends Migration
{
    public function up()
    {
        $this->createTable('video_category_relation', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'video_id' => $this->integer()->notNull(),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    public function down()
    {
        $this->dropTable('video_category_relation');
        echo "m160825_190357_video_category_relations be reverted.\n";
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
