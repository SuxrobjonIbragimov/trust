<?php
namespace common\library\payment\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_transactions}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%orders}}`
 * - `{{%user}}`
 */
class m200624_152832_create_payment_transactions_table extends Migration
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
        $this->createTable('{{%payment_transactions}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->null(),
            'currency' => $this->string(3)->defaultValue('UZS'),
            'total' => $this->decimal(12,2)->defaultValue(0),
            'amount' => $this->decimal(12,2)->defaultValue(0),
            'action' => $this->integer()->defaultValue(0),
            'service_transaction_id' => $this->string(),
            'merchant_trans_id' => $this->string(),
            'merchant_prepare_id' => $this->integer(),
            'merchant_confirm_id' => $this->integer(),
            'service_id' => $this->integer(),
            'click_paydoc_id' => $this->integer(),
            'sign_timestamp' => $this->string(),
            'sign_time' => $this->string(),
            'sign_string' => $this->text(),
            'create_time' => $this->string(),
            'perform_time' => $this->string(),
            'cancel_time' => $this->string(),
            'error' => $this->integer(),
            'error_note' => $this->text(),
            'user_id' => $this->integer()->null(),
            'note' => $this->text(),
            'auto_capture' => $this->tinyInteger(2)->defaultValue(1),
            'type' => $this->string(50)->defaultValue('click'),
            'status' => $this->string(50),
            'status_note' => $this->string(),
            'reason' => $this->string(),
            'receivers' => $this->text(),
            'payment_methods' => $this->string(),
            'request' => $this->text(),
            'response' => $this->text(),
            'created' => $this->integer(),
            'modified' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ],$tableOptions);

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-payment_transactions-order_id}}',
            '{{%payment_transactions}}',
            'order_id'
        );

        // add foreign key for table `{{%policy_order}}`
        $this->addForeignKey(
            '{{%fk-payment_transactions-order_id}}',
            '{{%payment_transactions}}',
            'order_id',
            '{{%policy_order}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-payment_transactions-user_id}}',
            '{{%payment_transactions}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-payment_transactions-user_id}}',
            '{{%payment_transactions}}',
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

        // drops foreign key for table `{{%policy_order}}`
        $this->dropForeignKey(
            '{{%fk-payment_transactions-order_id}}',
            '{{%payment_transactions}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-payment_transactions-order_id}}',
            '{{%payment_transactions}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-payment_transactions-user_id}}',
            '{{%payment_transactions}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-payment_transactions-user_id}}',
            '{{%payment_transactions}}'
        );

        $this->dropTable('{{%payment_transactions}}');
    }
}
