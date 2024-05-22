<?php

namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_osgo}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m221007_112455_create_policy_osgo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_osgo}}', [
            'id' => $this->primaryKey(),
            'start_date' => $this->date(),
            'end_date' => $this->date(),
            'policy_series' => $this->string(),
            'policy_number' => $this->string(),
            'amount_uzs' => $this->double(),
            'amount_usd' => $this->double(),
            'region_id' => $this->integer(),
            'period_id' => $this->integer()->defaultValue(2),
            'driver_limit_id' => $this->integer()->defaultValue(0),
            'discount_id' => $this->integer()->defaultValue(0),
            'citizenship_id' => $this->integer(),
            'owner_orgname' => $this->string(),
            'owner_first_name' => $this->string(),
            'owner_last_name' => $this->string(),
            'owner_middle_name' => $this->string(),
            'owner_birthday' => $this->date(),
            'owner_pinfl' => $this->string(),
            'owner_inn' => $this->string(),
            'owner_pass_sery' => $this->string()->null(),
            'owner_pass_num' => $this->string(),
            'owner_pass_issued_by' => $this->string(),
            'owner_pass_issue_date' => $this->date(),
            'owner_region' => $this->string(),
            'owner_district' => $this->string(),
            'owner_is_driver' => $this->boolean(),
            'owner_is_applicant' => $this->boolean(),
            'owner_is_pensioner' => $this->smallInteger()->null(),
            'owner_fy' => $this->smallInteger()->null(),
            'app_first_name' => $this->string(),
            'app_last_name' => $this->string(),
            'app_middle_name' => $this->string(),
            'app_birthday' => $this->date(),
            'app_pinfl' => $this->string(),
            'app_pass_sery' => $this->string()->null(),
            'app_pass_num' => $this->string(),
            'app_pass_issued_by' => $this->string(),
            'app_pass_issue_date' => $this->date(),
            'app_phone' => $this->string(),
            'app_email' => $this->string(),
            'app_region' => $this->string(),
            'app_district' => $this->string(),
            'app_address' => $this->string(),
            'app_gender' => $this->string(),
            'legal_type' => $this->integer(),
            'vehicle_gov_number' => $this->string(),
            'tech_pass_series' => $this->string(),
            'tech_pass_number' => $this->string(),
            'tech_pass_issue_date' => $this->date(),
            'vehicle_model_name' => $this->string(),
            'vehicle_marka_id' => $this->integer(),
            'vehicle_model_id' => $this->integer(),
            'vehicle_type_id' => $this->integer(),
            'vehicle_issue_year' => $this->integer(),
            'vehicle_body_number' => $this->string(),
            'vehicle_engine_number' => $this->string(),
            'source' => $this->string(),
            'ins_anketa_id' => $this->integer()->null(),
            'ins_policy_id' => $this->integer()->null(),
            'uuid_fond' => $this->string(),
            'ins_log' => $this->text(),
            'status' => $this->integer()->defaultValue(0),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-policy_osgo-created_by}}',
            '{{%policy_osgo}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-policy_osgo-created_by}}',
            '{{%policy_osgo}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-policy_osgo-updated_by}}',
            '{{%policy_osgo}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-policy_osgo-updated_by}}',
            '{{%policy_osgo}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-policy_osgo-created_by}}',
            '{{%policy_osgo}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-policy_osgo-created_by}}',
            '{{%policy_osgo}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-policy_osgo-updated_by}}',
            '{{%policy_osgo}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-policy_osgo-updated_by}}',
            '{{%policy_osgo}}'
        );

        $this->dropTable('{{%policy_osgo}}');
    }
}
