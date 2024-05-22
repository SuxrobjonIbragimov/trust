<?php

namespace backend\modules\telegram\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bot_user_message}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bot_user}}`
 * - `{{%bot_user_message}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m221213_132810_create_bot_user_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bot_user_message}}', [
            'id' => $this->primaryKey(),
            'bot_user_id' => $this->integer()->null(),
            'title' => $this->string(),
            'type' => $this->string(),
            'message' => $this->text(),
            'parent_id' => $this->integer()->null(),
            'image' => $this->string(),
            'status' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);

        // creates index for column `bot_user_id`
        $this->createIndex(
            '{{%idx-bot_user_message-bot_user_id}}',
            '{{%bot_user_message}}',
            'bot_user_id'
        );

        // add foreign key for table `{{%bot_user}}`
        $this->addForeignKey(
            '{{%fk-bot_user_message-bot_user_id}}',
            '{{%bot_user_message}}',
            'bot_user_id',
            '{{%bot_user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-bot_user_message-parent_id}}',
            '{{%bot_user_message}}',
            'parent_id'
        );

        // add foreign key for table `{{%bot_user_message}}`
        $this->addForeignKey(
            '{{%fk-bot_user_message-parent_id}}',
            '{{%bot_user_message}}',
            'parent_id',
            '{{%bot_user_message}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-bot_user_message-created_by}}',
            '{{%bot_user_message}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-bot_user_message-created_by}}',
            '{{%bot_user_message}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-bot_user_message-updated_by}}',
            '{{%bot_user_message}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-bot_user_message-updated_by}}',
            '{{%bot_user_message}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bot_user}}`
        $this->dropForeignKey(
            '{{%fk-bot_user_message-bot_user_id}}',
            '{{%bot_user_message}}'
        );

        // drops index for column `bot_user_id`
        $this->dropIndex(
            '{{%idx-bot_user_message-bot_user_id}}',
            '{{%bot_user_message}}'
        );

        // drops foreign key for table `{{%bot_user_message}}`
        $this->dropForeignKey(
            '{{%fk-bot_user_message-parent_id}}',
            '{{%bot_user_message}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-bot_user_message-parent_id}}',
            '{{%bot_user_message}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-bot_user_message-created_by}}',
            '{{%bot_user_message}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-bot_user_message-created_by}}',
            '{{%bot_user_message}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-bot_user_message-updated_by}}',
            '{{%bot_user_message}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-bot_user_message-updated_by}}',
            '{{%bot_user_message}}'
        );

        $this->dropTable('{{%bot_user_message}}');
    }
}
