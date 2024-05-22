<?php

namespace backend\modules\telegram\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bot_user_log}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bot_user}}`
 */
class m220209_082923_create_bot_user_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bot_user_log}}', [
            'id' => $this->primaryKey(),
            'bot_user_id' => $this->integer(),
            'data' => $this->binary(),
        ]);

        // creates index for column `bot_user_id`
        $this->createIndex(
            '{{%idx-bot_user_log-bot_user_id}}',
            '{{%bot_user_log}}',
            'bot_user_id'
        );

        // add foreign key for table `{{%bot_user}}`
        $this->addForeignKey(
            '{{%fk-bot_user_log-bot_user_id}}',
            '{{%bot_user_log}}',
            'bot_user_id',
            '{{%bot_user}}',
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
            '{{%fk-bot_user_log-bot_user_id}}',
            '{{%bot_user_log}}'
        );

        // drops index for column `bot_user_id`
        $this->dropIndex(
            '{{%idx-bot_user_log-bot_user_id}}',
            '{{%bot_user_log}}'
        );

        $this->dropTable('{{%bot_user_log}}');
    }
}
