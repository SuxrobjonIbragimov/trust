<?php

namespace backend\modules\telegram\migrations;

use backend\models\menu\MenuItems;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%bot_user}}`.
 */
class m210809_095730_create_bot_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bot_user}}', [
            'id' => $this->primaryKey(),
            't_id' => $this->string(),
            'is_bot' => $this->boolean(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            't_username' => $this->string(),
            'phone' => $this->string(),
            'language_code' => $this->string(),
            'callback_data' => $this->string(),
            'current_product' => $this->string()->null(),
            'current_step_type' => $this->string()->null(),
            'current_step_val' => $this->text()->null(),
            'message_id_l' => $this->integer(),
            'message_id_d' => $this->integer(),
            'message_id_e' => $this->integer(),
            'is_premium' => $this->string(),
            'source' => $this->string(),
            'ins_agent_id' => $this->bigInteger()->defaultValue(1),
            'info' => $this->string(),
            'is_admin' => $this->boolean()->defaultValue(false),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `source`
        $this->createIndex('{{%idx-bot_user-source}}', '{{%bot_user}}', 'source');

        // creates index for column `ins_agent_id`
        $this->createIndex(
            '{{%idx-bot_user-ins_agent_id}}',
            '{{%bot_user}}',
            'ins_agent_id'
        );

        $this->insert(MenuItems::tableName(), [
            'menu_id' => 1,
            'parent_id' => 18,
            'label' => 'Telegram users',
            'url' => '/telegram/bot-user/index',
            'class' => 'fab fa-telegram',
            'icon' => '',
            'description' => '',
            'weight' => 100,
            'status' => MenuItems::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `ins_agent_id`
        $this->dropIndex(
            '{{%idx-bot_user-ins_agent_id}}',
            '{{%bot_user}}'
        );
        // drops index for column `source`
        $this->dropIndex('{{%idx-bot_user-source}}', '{{%bot_user}}' );

        $this->dropTable('{{%bot_user}}');
    }
}
