<?php

use yii\db\Migration;

class m160812_185441_create_blog extends Migration
{
    private $table = 'blog';
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('blog', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'url' => $this->string()->notNull()->unique(),
            'owner_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ], $tableOptions);

        // creates index for column `author_id`
        $this->createIndex(
            'idx-blog-owner_id',
            'blog',
            'owner_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-blog-owner_id',
            'blog',
            'owner_id',
            'user',
            'id',
            'CASCADE'
        );


    }

    public function down()
    {

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-blog-owner_id',
            'blog'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-blog-owner_id',
            'blog'
        );

        $this->dropTable('blog');

        echo "m160812_185441_blog reverted.\n";
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
