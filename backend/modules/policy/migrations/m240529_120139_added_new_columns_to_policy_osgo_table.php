<?php

namespace backend\modules\policy\migrations;
use yii\db\Migration;

/**
 * Handles adding columns to table `{{%policy_osgo}}`.
 */
class m240529_120139_added_new_columns_to_policy_osgo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%handbook_oked}}', [
            'id' => $this->primaryKey(),
            'ins_id' => $this->string()->null(),
            'name_uz' => $this->string()->null(),
            'name_ru' => $this->string()->null(),
            'name_en' => $this->string()->null(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_by' => $this->bigInteger()->null(),
            'updated_by' => $this->bigInteger()->null(),
            'created_at' => $this->dateTime()->null(),
            'updated_at' => $this->dateTime()->null(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-handbook_oked-created_by}}',
            '{{%handbook_oked}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-handbook_oked-created_by}}',
            '{{%handbook_oked}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-handbook_oked-updated_by}}',
            '{{%handbook_oked}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-handbook_oked-updated_by}}',
            '{{%handbook_oked}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );
        $this->addColumn('{{%policy_osgo}}', 'owner_oked', $this->string()->null());
        $this->addColumn('{{%policy_osgo}}', 'owner_mfo', $this->string()->null());
        $this->addColumn('{{%policy_osgo}}', 'owner_settlement_account', $this->string()->null());
        $this->addColumn('{{%policy_osgo}}', 'org_okonx', $this->string()->null());
        $this->addColumn('{{%policy_osgo}}', 'org_okonx_coef', $this->decimal(10,3)->null());
        $this->addColumn('{{%policy_osgo}}', 'org_annual_salary', $this->decimal(20,3)->null());
        $this->addColumn('{{%policy_osgo}}', 'appl_oked', $this->text()->null());
        $this->addColumn('{{%policy_osgo}}', 'appl_orgname', $this->text()->null());
        $this->addColumn('{{%policy_osgo}}', 'appl_inn', $this->text()->null());
        $this->addColumn('{{%policy_osgo}}', 'vehicle_seats_count', $this->integer()->null()->defaultValue(0));
        $this->addColumn('{{%policy_osgo}}', 'contract_number', $this->string()->null());
        $this->addColumn('{{%policy_osgo}}', 'contract_date', $this->date());
        $this->addColumn('{{%policy_osgo}}', 'period', $this->integer()->null());
        $this->addColumn('{{%policy_osgo}}', 'is_document', $this->integer()->null());
        $this->addColumn('{{%policy_osgo}}', 'deleted_at', $this->dateTime());
        $this->addColumn('{{%policy_osgo}}', 'deleted_by', $this->bigInteger()->null());

        // creates index for column `updated_by`
        $this->createIndex('{{%idx-policy_osgo-deleted_by}}', '{{%policy_osgo}}', 'deleted_by');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-policy_osgo-deleted_by}}', '{{%policy_osgo}}', 'deleted_by', '{{%user}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-policy_osgo-deleted_by}}',
            '{{%policy_osgo}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-policy_osgo-deleted_by}}',
            '{{%policy_osgo}}'
        );

        $this->dropColumn('{{%policy_osgo}}', 'deleted_at');
        $this->dropColumn('{{%policy_osgo}}', 'deleted_by');
        $this->dropColumn('{{%policy_osgo}}', 'owner_oked');
        $this->dropColumn('{{%policy_osgo}}', 'owner_mfo');
        $this->dropColumn('{{%policy_osgo}}', 'owner_settlement_account');
        $this->dropColumn('{{%policy_osgo}}', 'appl_oked');
        $this->dropColumn('{{%policy_osgo}}', 'appl_orgname');
        $this->dropColumn('{{%policy_osgo}}', 'appl_inn');
        $this->dropColumn('{{%policy_osgo}}', 'vehicle_seats_count');
        $this->dropColumn('{{%policy_osgo}}', 'contract_number');
        $this->dropColumn('{{%policy_osgo}}', 'contract_date');
        $this->dropColumn('{{%policy_osgo}}', 'period');
        $this->dropColumn('{{%policy_osgo}}', 'is_document');

        $this->dropColumn('{{%policy_osgo}}', 'org_okonx');
        $this->dropColumn('{{%policy_osgo}}', 'org_okonx_coef');
        $this->dropColumn('{{%policy_osgo}}', 'org_annual_salary');

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-handbook_oked-created_by}}',
            '{{%handbook_oked}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-handbook_oked-created_by}}',
            '{{%handbook_oked}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-handbook_oked-updated_by}}',
            '{{%handbook_oked}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-handbook_oked-updated_by}}',
            '{{%handbook_oked}}'
        );

        $this->dropTable('{{%handbook_oked}}');
    }
}
