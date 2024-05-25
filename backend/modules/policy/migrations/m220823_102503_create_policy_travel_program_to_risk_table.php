<?php
namespace backend\modules\policy\migrations;

use Yii;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_program_to_risk}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel_program}}`
 * - `{{%policy_travel_risk}}`
 */
class m220823_102503_create_policy_travel_program_to_risk_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_program_to_risk}}', [
            'id' => $this->primaryKey(),
            'policy_travel_program_id' => $this->integer(),
            'policy_travel_risk_id' => $this->integer(),
            'value' => $this->float(),
            'weight' => $this->integer()->null(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
        ]);

        // creates index for column `policy_travel_program_id`
        $this->createIndex(
            '{{%idx-policy_travel_program_to_risk-policy_travel_program_id}}',
            '{{%policy_travel_program_to_risk}}',
            'policy_travel_program_id'
        );

        // add foreign key for table `{{%policy_travel_program}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_program_to_risk-policy_travel_program_id}}',
            '{{%policy_travel_program_to_risk}}',
            'policy_travel_program_id',
            '{{%policy_travel_program}}',
            'id',
            'CASCADE'
        );

        // creates index for column `policy_travel_risk_id`
        $this->createIndex(
            '{{%idx-policy_travel_program_to_risk-policy_travel_risk_id}}',
            '{{%policy_travel_program_to_risk}}',
            'policy_travel_risk_id'
        );

        // add foreign key for table `{{%policy_travel_risk}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_program_to_risk-policy_travel_risk_id}}',
            '{{%policy_travel_program_to_risk}}',
            'policy_travel_risk_id',
            '{{%policy_travel_risk}}',
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
            '{{%fk-policy_travel_program_to_risk-policy_travel_program_id}}',
            '{{%policy_travel_program_to_risk}}'
        );

        // drops index for column `policy_travel_program_id`
        $this->dropIndex(
            '{{%idx-policy_travel_program_to_risk-policy_travel_program_id}}',
            '{{%policy_travel_program_to_risk}}'
        );

        // drops foreign key for table `{{%policy_travel_risk}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel_program_to_risk-policy_travel_risk_id}}',
            '{{%policy_travel_program_to_risk}}'
        );

        // drops index for column `policy_travel_risk_id`
        $this->dropIndex(
            '{{%idx-policy_travel_program_to_risk-policy_travel_risk_id}}',
            '{{%policy_travel_program_to_risk}}'
        );

        $this->dropTable('{{%policy_travel_program_to_risk}}');
    }
}
