<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel_purpose}}`
 * - `{{%policy_travel_program}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m220823_111729_create_policy_travel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel}}', [
            'id' => $this->primaryKey(),
            'start_date' => $this->date(),
            'end_date' => $this->date(),
            'days' => $this->integer(),
            'policy_series' => $this->string(),
            'policy_number' => $this->string(),
            'amount_uzs' => $this->double(),
            'amount_usd' => $this->double(),
            'purpose_id' => $this->integer(),
            'program_id' => $this->integer(),
            'abroad_group' => $this->integer(),
            'abroad_type_id' => $this->integer()->null(),
            'multi_days_id' => $this->integer()->null(),
            'is_family' => $this->boolean(),
            'app_name' => $this->string(),
            'app_surname' => $this->string(),
            'app_birthday' => $this->date(),
            'app_pinfl' => $this->string(),
            'app_pass_sery' => $this->string()->null(),
            'app_pass_num' => $this->string(),
            'app_phone' => $this->string(),
            'app_email' => $this->string(),
            'app_address' => $this->string(),
            'source' => $this->string(),
            'bot_user_id' => $this->integer()->null(),
            'uuid_ins' => $this->string(),
            'ins_log' => $this->text(),
            'ins_anketa_id' => $this->integer()->null(),
            'ins_policy_id' => $this->integer()->null(),
            'status' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);


        // creates index for column `bot_user_id`
        $this->createIndex('{{%idx-policy_travel-bot_user_id}}', '{{%policy_travel}}', 'bot_user_id');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-policy_travel-bot_user_id}}', '{{%policy_travel}}', 'bot_user_id', '{{%bot_user}}', 'id', 'SET NULL');

        // creates index for column `purpose_id`
        $this->createIndex(
            '{{%idx-policy_travel-purpose_id}}',
            '{{%policy_travel}}',
            'purpose_id'
        );

        // add foreign key for table `{{%policy_travel_purpose}}`
        $this->addForeignKey(
            '{{%fk-policy_travel-purpose_id}}',
            '{{%policy_travel}}',
            'purpose_id',
            '{{%policy_travel_purpose}}',
            'id',
            'CASCADE'
        );

        // creates index for column `program_id`
        $this->createIndex(
            '{{%idx-policy_travel-program_id}}',
            '{{%policy_travel}}',
            'program_id'
        );

        // add foreign key for table `{{%policy_travel_program}}`
        $this->addForeignKey(
            '{{%fk-policy_travel-program_id}}',
            '{{%policy_travel}}',
            'program_id',
            '{{%policy_travel_program}}',
            'id',
            'CASCADE'
        );

        // creates index for column `abroad_type_id`
        $this->createIndex('{{%idx-policy_travel-abroad_type_id}}', '{{%policy_travel}}', 'abroad_type_id');

        // add foreign key for table `{{%policy_travel_abroad_type}}`
        $this->addForeignKey('{{%fk-policy_travel-abroad_type_id}}', '{{%policy_travel}}', 'abroad_type_id', '{{%policy_travel_abroad_type}}', 'id', 'CASCADE');

        // creates index for column `multi_days_id`
        $this->createIndex('{{%idx-policy_travel-multi_days_id}}','{{%policy_travel}}', 'multi_days_id');

        // add foreign key for table `{{%policy_travel_multi_days}}`
        $this->addForeignKey('{{%fk-policy_travel-multi_days_id}}', '{{%policy_travel}}', 'multi_days_id', '{{%policy_travel_multi_days}}', 'id', 'CASCADE');

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-policy_travel-created_by}}',
            '{{%policy_travel}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-policy_travel-created_by}}',
            '{{%policy_travel}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-policy_travel-updated_by}}',
            '{{%policy_travel}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-policy_travel-updated_by}}',
            '{{%policy_travel}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%policy_travel_purpose}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel-purpose_id}}',
            '{{%policy_travel}}'
        );

        // drops index for column `purpose_id`
        $this->dropIndex(
            '{{%idx-policy_travel-purpose_id}}',
            '{{%policy_travel}}'
        );

        // drops foreign key for table `{{%policy_travel_program}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel-program_id}}',
            '{{%policy_travel}}'
        );

        // drops index for column `program_id`
        $this->dropIndex(
            '{{%idx-policy_travel-program_id}}',
            '{{%policy_travel}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel-created_by}}',
            '{{%policy_travel}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-policy_travel-created_by}}',
            '{{%policy_travel}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel-updated_by}}',
            '{{%policy_travel}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-policy_travel-updated_by}}',
            '{{%policy_travel}}'
        );

        // drops foreign key for table `{{%policy_travel_abroad_type}}`
        $this->dropForeignKey('{{%fk-policy_travel-abroad_type_id}}', '{{%policy_travel}}');
        // drops index for column `abroad_type_id`
        $this->dropIndex('{{%idx-policy_travel-abroad_type_id}}', '{{%policy_travel}}' );

        // drops foreign key for table `{{%policy_travel_multi_days}}`
        $this->dropForeignKey('{{%fk-policy_travel-multi_days_id}}', '{{%policy_travel}}');
        // drops index for column `multi_days_id`
        $this->dropIndex('{{%idx-policy_travel-multi_days_id}}', '{{%policy_travel}}' );

        // drops foreign key for table `{{%bot_user}}`
        $this->dropForeignKey('{{%fk-policy_travel-bot_user_id}}', '{{%policy_travel}}');
        // drops index for column `bot_user_id`
        $this->dropIndex('{{%idx-policy_travel-bot_user_id}}', '{{%policy_travel}}' );
        $this->dropColumn('{{%policy_travel}}', 'bot_user_id');

        $this->dropTable('{{%policy_travel}}');
    }
}
