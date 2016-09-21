<?php

use yii\db\Migration;

class m160825_182932_category_create extends Migration
{
    public function up()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'parent_id' => $this->integer(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        // creates index for column `name`
        $this->createIndex(
            'idx-category-parent_id',
            'category',
            'parent_id'
        );
        // creates index for column `name`
        $this->createIndex(
            'idx-category-name',
            'category',
            'name'
        );

        // add foreign key for self
        $this->addForeignKey(
            'fk-category-parent_id',
            'category',
            'parent_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {

        $this->dropForeignKey(
            'fk-category-parent_id',
            'category'
        );

        $this->dropIndex(
            'idx-category-parent_id',
            'category'
        );
        $this->dropIndex(
            'idx-category-name',
            'category'
        );

        $this->dropTable('category');

        echo "m160825_182932_category_create be reverted.\n";

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
