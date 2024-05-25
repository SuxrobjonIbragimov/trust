<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_age_group}}`.
 */
class m220823_104751_create_policy_travel_age_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_age_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'age_min' => $this->integer()->null(),
            'age_max' => $this->integer()->null(),
            'rate' => $this->float(),
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
        $this->dropTable('{{%policy_travel_age_group}}');
    }
}
