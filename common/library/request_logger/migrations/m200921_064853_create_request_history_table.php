<?php

namespace common\library\request_logger\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%request_history}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%orders}}`
 * - `{{%user}}`
 */
class m200921_064853_create_request_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%request_history}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'method' => $this->string(),
            'params' => $this->text(),
            'request' => $this->text(),
            'response' => $this->text(),
            'created_at' => $this->dateTime(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-request_history-user_id}}',
            '{{%request_history}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-request_history-user_id}}',
            '{{%request_history}}',
            'user_id',
            '{{%user}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-request_history-user_id}}',
            '{{%request_history}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-request_history-user_id}}',
            '{{%request_history}}'
        );

        $this->dropTable('{{%request_history}}');
    }
}
