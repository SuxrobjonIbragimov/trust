<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_traveller}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel}}`
 */
class m220823_111823_create_policy_travel_traveller_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_traveller}}', [
            'id' => $this->primaryKey(),
            'policy_travel_id' => $this->integer(),
            'first_name' => $this->string(),
            'surname' => $this->string(),
            'birthday' => $this->date(),
            'pass_sery' => $this->string()->null(),
            'pass_num' => $this->string(),
            'pinfl' => $this->string(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'address' => $this->string(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `policy_travel_id`
        $this->createIndex(
            '{{%idx-policy_travel_traveller-policy_travel_id}}',
            '{{%policy_travel_traveller}}',
            'policy_travel_id'
        );

        // add foreign key for table `{{%policy_travel}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_traveller-policy_travel_id}}',
            '{{%policy_travel_traveller}}',
            'policy_travel_id',
            '{{%policy_travel}}',
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
            '{{%fk-policy_travel_traveller-policy_travel_id}}',
            '{{%policy_travel_traveller}}'
        );

        // drops index for column `policy_travel_id`
        $this->dropIndex(
            '{{%idx-policy_travel_traveller-policy_travel_id}}',
            '{{%policy_travel_traveller}}'
        );

        $this->dropTable('{{%policy_travel_traveller}}');
    }
}
