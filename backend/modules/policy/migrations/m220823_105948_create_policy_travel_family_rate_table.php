<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_family_rate}}`.
 */
class m220823_105948_create_policy_travel_family_rate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_family_rate}}', [
            'id' => $this->primaryKey(),
            'member_min' => $this->integer()->null(),
            'member_max' => $this->integer()->null(),
            'rate' => $this->float(),
            'weight' => $this->integer()->null(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%policy_travel_family_rate}}');
    }
}
