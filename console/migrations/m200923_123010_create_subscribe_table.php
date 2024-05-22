<?php

use backend\models\menu\MenuItems;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscribe}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m200923_123010_create_subscribe_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscribe}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'user_id' => $this->integer()->null(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-subscribe-user_id}}',
            '{{%subscribe}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-subscribe-user_id}}',
            '{{%subscribe}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-subscribe-created_by}}',
            '{{%subscribe}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-subscribe-created_by}}',
            '{{%subscribe}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-subscribe-updated_by}}',
            '{{%subscribe}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-subscribe-updated_by}}',
            '{{%subscribe}}',
            'updated_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        $this->batchInsert(MenuItems::tableName(),
            ['menu_id', 'parent_id', 'label', 'url', 'class', 'icon', 'description', 'weight', 'status', 'created_at', 'updated_at'],
            [
                [1, 18, 'Subscribes', '/site/subscribes', 'fa fa-bookmark-o', '', '', 2, MenuItems::STATUS_ACTIVE, time(), time()],
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-subscribe-user_id}}',
            '{{%subscribe}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-subscribe-user_id}}',
            '{{%subscribe}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-subscribe-created_by}}',
            '{{%subscribe}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-subscribe-created_by}}',
            '{{%subscribe}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-subscribe-updated_by}}',
            '{{%subscribe}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-subscribe-updated_by}}',
            '{{%subscribe}}'
        );

        $this->dropTable('{{%subscribe}}');
    }
}
