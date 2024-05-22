<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_program_to_country}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel_program}}`
 * - `{{%handbook_country}}`
 */
class m220823_100214_create_policy_travel_program_to_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_program_to_country}}', [
            'id' => $this->primaryKey(),
            'policy_travel_program_id' => $this->integer(),
            'country_id' => $this->integer(),
            'created_at' => $this->integer(),
        ]);

        // creates index for column `policy_travel_program_id`
        $this->createIndex(
            '{{%idx-policy_travel_program_to_country-policy_travel_program_id}}',
            '{{%policy_travel_program_to_country}}',
            'policy_travel_program_id'
        );

        // add foreign key for table `{{%policy_travel_program}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_program_to_country-policy_travel_program_id}}',
            '{{%policy_travel_program_to_country}}',
            'policy_travel_program_id',
            '{{%policy_travel_program}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-policy_travel_program_to_country-country_id}}',
            '{{%policy_travel_program_to_country}}',
            'country_id'
        );

        // add foreign key for table `{{%handbook_country}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_program_to_country-country_id}}',
            '{{%policy_travel_program_to_country}}',
            'country_id',
            '{{%handbook_country}}',
            'id',
            'CASCADE'
        );


        $this->batchInsert('policy_travel_program_to_country',
            ['policy_travel_program_id', 'country_id', 'created_at'],
            [
                [1, 35, time()],
                [1, 49, time()],
                [1, 50, time()],
                [1, 51, time()],
                [1, 53, time()],
                [1, 64, time()],
                [1, 140, time()],
                [1, 279, time()],

                [2, 49, time()],
                [2, 51, time()],
                [2, 53, time()],
                [2, 64, time()],
                [2, 279, time()],

                [3, 49, time()],
                [3, 51, time()],
                [3, 53, time()],
                [3, 64, time()],
                [3, 279, time()],

                [4, 35, time()],
                [4, 49, time()],
                [4, 50, time()],
                [4, 53, time()],
                [4, 64, time()],
                [4, 140, time()],
                [4, 279, time()],

                [5, 35, time()],
                [5, 49, time()],
                [5, 50, time()],
                [5, 51, time()],
                [5, 53, time()],
                [5, 64, time()],
                [5, 140, time()],
                [5, 279, time()],

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
            '{{%fk-policy_travel_program_to_country-policy_travel_program_id}}',
            '{{%policy_travel_program_to_country}}'
        );

        // drops index for column `policy_travel_program_id`
        $this->dropIndex(
            '{{%idx-policy_travel_program_to_country-policy_travel_program_id}}',
            '{{%policy_travel_program_to_country}}'
        );

        // drops foreign key for table `{{%handbook_country}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel_program_to_country-country_id}}',
            '{{%policy_travel_program_to_country}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-policy_travel_program_to_country-country_id}}',
            '{{%policy_travel_program_to_country}}'
        );

        $this->dropTable('{{%policy_travel_program_to_country}}');
    }
}
