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

        $this->batchInsert('policy_travel_program_period',
            ['policy_travel_program_id', 'day_min', 'day_max', 'value', 'is_fixed', 'status', 'created_at', 'updated_at'],
            [
                [1, 1, 8, 35000, true, 1, time(), time()],
                [2, 1, 8, 4396, false, 1, time(), time()],
                [3, 1, 8, 4945, false, 1, time(), time()],
                [4, 1, 8, 7714, false, 1, time(), time()],
                [5, 1, 8, 16780, false, 1, time(), time()],

                [1, 9, 15, 35000, true, 1, time(), time()],
                [2, 9, 15, 4176, false, 1, time(), time()],
                [3, 9, 15, 4747, false, 1, time(), time()],
                [4, 9, 15, 7220, false, 1, time(), time()],
                [5, 9, 15, 16231, false, 1, time(), time()],

                [1, 16, 21, 50000, true, 1, time(), time()],
                [2, 16, 21, 3956, false, 1, time(), time()],
                [3, 16, 21, 4549, false, 1, time(), time()],
                [4, 16, 21, 6824, false, 1, time(), time()],
                [5, 16, 21, 15681, false, 1, time(), time()],

                [1, 22, 30, 50000, true, 1, time(), time()],
                [2, 22, 30, 3626, false, 1, time(), time()],
                [3, 22, 30, 4552, false, 1, time(), time()],
                [4, 22, 30, 6627, false, 1, time(), time()],
                [5, 22, 30, 14583, false, 1, time(), time()],

                [1, 31, 44, 90000, true, 1, time(), time()],
                [2, 31, 44, 3517, false, 1, time(), time()],
                [3, 31, 44, 4153, false, 1, time(), time()],
                [4, 31, 44, 5934, false, 1, time(), time()],
                [5, 31, 44, 14363, false, 1, time(), time()],

                [1, 45, 60, 90000, true, 1, time(), time()],
                [2, 45, 60, 3077, false, 1, time(), time()],
                [3, 45, 60, 3956, false, 1, time(), time()],
                [4, 45, 60, 5836, false, 1, time(), time()],
                [5, 45, 60, 14033, false, 1, time(), time()],

                [1, 61, 92, 2400, false, 1, time(), time()],
                [2, 61, 92, 2751, false, 1, time(), time()],
                [3, 61, 92, 3563, false, 1, time(), time()],
                [4, 61, 92, 4625, false, 1, time(), time()],
                [5, 61, 92, 12979, false, 1, time(), time()],

                [1, 93, 183, 1900, false, 1, time(), time()],
                [2, 93, 183, 2963, false, 1, time(), time()],
                [3, 93, 183, 3751, false, 1, time(), time()],
                [4, 93, 183, 5082, false, 1, time(), time()],
                [5, 93, 183, 13402, false, 1, time(), time()],

                [1, 184, 365, 1500, false, 1, time(), time()],
                [2, 184, 365, 2540, false, 1, time(), time()],
                [3, 184, 365, 3376, false, 1, time(), time()],
                [4, 184, 365, 4180, false, 1, time(), time()],
                [5, 184, 365, 12873, false, 1, time(), time()],
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
