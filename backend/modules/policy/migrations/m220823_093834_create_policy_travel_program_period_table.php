<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_program_period}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel_program}}`
 */
class m220823_093834_create_policy_travel_program_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_program_period}}', [
            'id' => $this->primaryKey(),
            'policy_travel_program_id' => $this->integer(),
            'day_min' => $this->integer()->null(),
            'day_max' => $this->integer()->null(),
            'value' => $this->float(),
            'is_fixed' => $this->boolean(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `policy_travel_program_id`
        $this->createIndex(
            '{{%idx-policy_travel_program_period-policy_travel_program_id}}',
            '{{%policy_travel_program_period}}',
            'policy_travel_program_id'
        );

        // add foreign key for table `{{%policy_travel_program}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_program_period-policy_travel_program_id}}',
            '{{%policy_travel_program_period}}',
            'policy_travel_program_id',
            '{{%policy_travel_program}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%policy_travel_program}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel_program_period-policy_travel_program_id}}',
            '{{%policy_travel_program_period}}'
        );

        // drops index for column `policy_travel_program_id`
        $this->dropIndex(
            '{{%idx-policy_travel_program_period-policy_travel_program_id}}',
            '{{%policy_travel_program_period}}'
        );

        $this->dropTable('{{%policy_travel_program_period}}');
    }
}
