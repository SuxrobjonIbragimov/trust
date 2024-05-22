<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%translate}}`.
 */
class m200519_064816_create_translate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%translate}}', [
            'id' => $this->primaryKey(),
            'param' => $this->string(),
            'content' => $this->text(),
            'model' => $this->string(),
            'revision_id' => $this->integer(),
            'lang' => $this->string(5),
            'source' => $this->boolean(),
            'status' => $this->smallInteger()->defaultValue(1),
            'weight' => $this->integer()->defaultValue(1),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'deleted_by' => $this->integer()->null(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'deleted_at' => $this->dateTime(),
        ]);


        // creates index for column `param`
        $this->createIndex(
            '{{%idx-translate-param}}',
            '{{%translate}}',
            'param'
        );
        // creates index for column `model`
        $this->createIndex(
            '{{%idx-translate-model}}',
            '{{%translate}}',
            'model'
        );
        // creates index for column `revision_id`
        $this->createIndex(
            '{{%idx-translate-revision_id}}',
            '{{%translate}}',
            'revision_id'
        );
        // creates index for column `lang`
        $this->createIndex(
            '{{%idx-translate-lang}}',
            '{{%translate}}',
            'lang'
        );

        // add foreign key for table `{{%language}}`
//        $this->addForeignKey(
//            '{{%fk-translate-lang}}',
//            '{{%translate}}',
//            'lang',
//            '{{%language}}',
//            'language_id',
//            'CASCADE'
//        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-translate-created_by}}',
            '{{%translate}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-translate-created_by}}',
            '{{%translate}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-translate-updated_by}}',
            '{{%translate}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-translate-updated_by}}',
            '{{%translate}}',
            'updated_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        // creates index for column `deleted_by`
        $this->createIndex(
            '{{%idx-translate-deleted_by}}',
            '{{%translate}}',
            'deleted_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-translate-deleted_by}}',
            '{{%translate}}',
            'deleted_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%language}}`
        $this->dropForeignKey(
            '{{%fk-translate-lang}}',
            '{{%translate}}'
        );

        // drops index for column `lang`
        $this->dropIndex(
            '{{%idx-translate-lang}}',
            '{{%translate}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-translate-created_by}}',
            '{{%translate}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-translate-created_by}}',
            '{{%translate}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-translate-updated_by}}',
            '{{%translate}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-translate-updated_by}}',
            '{{%translate}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-translate-deleted_by}}',
            '{{%translate}}'
        );

        // drops index for column `deleted_by`
        $this->dropIndex(
            '{{%idx-translate-deleted_by}}',
            '{{%translate}}'
        );

        $this->dropTable('{{%translate}}');
    }
}
