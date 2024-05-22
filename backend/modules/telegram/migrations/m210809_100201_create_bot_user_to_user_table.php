<?php
namespace backend\modules\telegram\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bot_user_to_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bot_user}}`
 * - `{{%user}}`
 */
class m210809_100201_create_bot_user_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bot_user_to_user}}', [
            'bot_user_id' => $this->integer(),
            'user_id' => $this->integer(),
            'created_at' => $this->integer(),
        ]);

        $this->addPrimaryKey('pk-bot_user_to_user', 'bot_user_to_user', ['bot_user_id', 'user_id']);

        // creates index for column `bot_user_id`
        $this->createIndex(
            '{{%idx-bot_user_to_user-bot_user_id}}',
            '{{%bot_user_to_user}}',
            'bot_user_id'
        );

        // add foreign key for table `{{%bot_user}}`
        $this->addForeignKey(
            '{{%fk-bot_user_to_user-bot_user_id}}',
            '{{%bot_user_to_user}}',
            'bot_user_id',
            '{{%bot_user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-bot_user_to_user-user_id}}',
            '{{%bot_user_to_user}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-bot_user_to_user-user_id}}',
            '{{%bot_user_to_user}}',
            'user_id',
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
            '{{%fk-bot_user_to_user-bot_user_id}}',
            '{{%bot_user_to_user}}'
        );

        // drops index for column `bot_user_id`
        $this->dropIndex(
            '{{%idx-bot_user_to_user-bot_user_id}}',
            '{{%bot_user_to_user}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-bot_user_to_user-user_id}}',
            '{{%bot_user_to_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-bot_user_to_user-user_id}}',
            '{{%bot_user_to_user}}'
        );

        $this->dropTable('{{%bot_user_to_user}}');
    }
}
