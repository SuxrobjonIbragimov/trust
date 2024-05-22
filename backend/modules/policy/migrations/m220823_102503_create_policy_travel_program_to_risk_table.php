<?php
namespace backend\modules\policy\migrations;

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

        $this->batchInsert('policy_travel_program_to_risk',
            ['policy_travel_program_id', 'policy_travel_risk_id', 'value', 'created_at'],
            [
                [1, 1, 1000, time()],
                [1, 2, 2000, time()],
                [1, 3, 7000, time()],

                [2, 1, 1000, time()],
                [2, 2, 10000, time()],
                [2, 3, 9000, time()],

                [3, 1, 1500, time()],
                [3, 2, 20000, time()],
                [3, 3, 23500, time()],

                [4, 1, 2000, time()],
                [4, 2, 30000, time()],
                [4, 3, 28000, time()],

                [5, 1, 3000, time()],
                [5, 2, 40000, time()],
                [5, 3, 47000, time()],

            ]
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
