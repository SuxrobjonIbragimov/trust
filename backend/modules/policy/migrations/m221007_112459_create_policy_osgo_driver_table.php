<?php

namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_osgo_driver}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_osgo}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m221007_112459_create_policy_osgo_driver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_osgo_driver}}', [
            'id' => $this->primaryKey(),
            'policy_osgo_id' => $this->integer(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'middle_name' => $this->string(),
            'birthday' => $this->date(),
            'pass_sery' => $this->string()->null(),
            'pass_num' => $this->string(),
            'pass_issued_by' => $this->string(),
            'pass_issue_date' => $this->date(),
            'pinfl' => $this->string(),
            'license_series' => $this->string(),
            'license_number' => $this->string(),
            'license_issue_date' => $this->date(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'address' => $this->string(),
            'relationship_id' => $this->integer(),
            'resident_id' => $this->integer(),
            'document_type' => $this->integer()->defaultValue(1),
            'gender' => $this->string(),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `policy_osgo_id`
        $this->createIndex(
            '{{%idx-policy_osgo_driver-policy_osgo_id}}',
            '{{%policy_osgo_driver}}',
            'policy_osgo_id'
        );

        // add foreign key for table `{{%policy_osgo}}`
        $this->addForeignKey(
            '{{%fk-policy_osgo_driver-policy_osgo_id}}',
            '{{%policy_osgo_driver}}',
            'policy_osgo_id',
            '{{%policy_osgo}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-policy_osgo_driver-created_by}}',
            '{{%policy_osgo_driver}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-policy_osgo_driver-created_by}}',
            '{{%policy_osgo_driver}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-policy_osgo_driver-updated_by}}',
            '{{%policy_osgo_driver}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-policy_osgo_driver-updated_by}}',
            '{{%policy_osgo_driver}}',
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
        // drops foreign key for table `{{%policy_osgo}}`
        $this->dropForeignKey(
            '{{%fk-policy_osgo_driver-policy_osgo_id}}',
            '{{%policy_osgo_driver}}'
        );

        // drops index for column `policy_osgo_id`
        $this->dropIndex(
            '{{%idx-policy_osgo_driver-policy_osgo_id}}',
            '{{%policy_osgo_driver}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-policy_osgo_driver-created_by}}',
            '{{%policy_osgo_driver}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-policy_osgo_driver-created_by}}',
            '{{%policy_osgo_driver}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-policy_osgo_driver-updated_by}}',
            '{{%policy_osgo_driver}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-policy_osgo_driver-updated_by}}',
            '{{%policy_osgo_driver}}'
        );

        $this->dropTable('{{%policy_osgo_driver}}');
    }
}
