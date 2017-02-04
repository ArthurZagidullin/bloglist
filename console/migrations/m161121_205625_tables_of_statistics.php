<?php

use yii\db\Migration;

class m161121_205625_tables_of_statistics extends Migration
{
    public function up()
    {
        $this->createTable('video_statistic', [
            'id' => $this->primaryKey(),
            'video_id' => $this->integer()->notNull(),
            'view' => $this->integer(),
            'like' => $this->integer(),
            'dislike' => $this->integer(),
            'favorite' => $this->integer(),
            'comment' => $this->integer(),
            'datetime' => $this->dateTime()->notNull(),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->createIndex('idx_views_video_statistic', 'video_statistic', 'view');
        $this->createIndex('idx_view_like_statistic', 'video_statistic', 'favorite');

        $this->addForeignKey(
            'FK-video_statistics',
            'video_statistic',
            'video_id',
            'video',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {

        $this->dropForeignKey(
            'FK-video_statistics',
            'video_statistic'
        );

        $this->dropIndex('idx_views_video_statistic','video_statistic');
        $this->dropIndex('idx_view_like_statistic','video_statistic');

        $this->dropTable('video_statistic');

        echo "m161121_205625_tables_of_statistics reverted.\n";
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
