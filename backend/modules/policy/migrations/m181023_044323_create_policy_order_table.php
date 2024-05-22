<?php

namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_order}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m181023_044323_create_policy_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%policy_order}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->null(),
            'session_id' => $this->string(),
            'revision_id' => $this->integer(),
            'revision_model' => $this->string(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'phone' => $this->string(),
            'total_amount' => $this->double(),
            'payment_type' => $this->string(),
            'payment_status' => $this->integer(),
            'status' => $this->integer(),
            'comment' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        // creates index for column `revision_id`
        $this->createIndex(
            '{{%idx-policy_order-revision_id}}',
            '{{%policy_order}}',
            'revision_id'
        );

        // creates index for column `revision_model`
        $this->createIndex(
            '{{%idx-policy_order-revision_model}}',
            '{{%policy_order}}',
            'revision_model'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-policy_order-user_id}}',
            '{{%policy_order}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-policy_order-user_id}}',
            '{{%policy_order}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-policy_order-user_id}}',
            '{{%policy_order}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-policy_order-user_id}}',
            '{{%policy_order}}'
        );

        $this->dropTable('{{%policy_order}}');
    }
}
