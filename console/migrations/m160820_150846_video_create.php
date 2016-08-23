<?php

use yii\db\Migration;

class m160820_150846_video_create extends Migration
{
    public function up()
    {
        $this->createTable('video', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
            'video_id' => $this->string()->notNull()->unique(),
            'blog_id' => $this->integer()->notNull(),
            'published_at' => $this->timestamp()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        // creates index for column `video_id`
        $this->createIndex(
            'idx-video-video_id',
            'video',
            'video_id'
        );

//        // creates index for column `blog_id`
//        $this->createIndex(
//            'idx-video-blog_id',
//            'video',
//            'blog_id'
//        );

        // add foreign key for table `blog`
        $this->addForeignKey(
            'fk-video-blog_id',
            'video',
            'blog_id',
            'blog',
            'id',
            'CASCADE'
        );

    }

    public function down()
    {

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-video-blog_id',
            'video'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-video-video_id',
            'video'
        );

        $this->dropTable('video');

        echo "m160820_150846_video_create reverted.\n";

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
