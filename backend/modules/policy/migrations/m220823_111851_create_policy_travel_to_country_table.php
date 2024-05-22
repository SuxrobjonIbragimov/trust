<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_to_country}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel}}`
 * - `{{%handbook_country}}`
 */
class m220823_111851_create_policy_travel_to_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_to_country}}', [
            'id' => $this->primaryKey(),
            'policy_travel_id' => $this->integer(),
            'country_id' => $this->integer(),
            'weight' => $this->integer()->null(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
        ]);

        // creates index for column `policy_travel_id`
        $this->createIndex(
            '{{%idx-policy_travel_to_country-policy_travel_id}}',
            '{{%policy_travel_to_country}}',
            'policy_travel_id'
        );

        // add foreign key for table `{{%policy_travel}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_to_country-policy_travel_id}}',
            '{{%policy_travel_to_country}}',
            'policy_travel_id',
            '{{%policy_travel}}',
            'id',
            'CASCADE'
        );

        // creates index for column `country_id`
        $this->createIndex(
            '{{%idx-policy_travel_to_country-country_id}}',
            '{{%policy_travel_to_country}}',
            'country_id'
        );

        // add foreign key for table `{{%handbook_country}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_to_country-country_id}}',
            '{{%policy_travel_to_country}}',
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
        // drops foreign key for table `{{%policy_travel}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel_to_country-policy_travel_id}}',
            '{{%policy_travel_to_country}}'
        );

        // drops index for column `policy_travel_id`
        $this->dropIndex(
            '{{%idx-policy_travel_to_country-policy_travel_id}}',
            '{{%policy_travel_to_country}}'
        );

        // drops foreign key for table `{{%handbook_country}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel_to_country-country_id}}',
            '{{%policy_travel_to_country}}'
        );

        // drops index for column `country_id`
        $this->dropIndex(
            '{{%idx-policy_travel_to_country-country_id}}',
            '{{%policy_travel_to_country}}'
        );

        $this->dropTable('{{%policy_travel_to_country}}');
    }
}
