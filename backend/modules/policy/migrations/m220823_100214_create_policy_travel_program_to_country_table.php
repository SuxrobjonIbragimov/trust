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
