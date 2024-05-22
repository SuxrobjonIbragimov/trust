<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_risk}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel_risk}}`
 */
class m220823_101930_create_policy_travel_risk_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_risk}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(),
            'name_uz' => $this->string(),
            'name_en' => $this->string(),
            'parent_id' => $this->integer()->null(),
            'key' => $this->string(),
            'ins_id' => $this->integer()->null(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-policy_travel_risk-parent_id}}',
            '{{%policy_travel_risk}}',
            'parent_id'
        );

        // add foreign key for table `{{%policy_travel_risk}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_risk-parent_id}}',
            '{{%policy_travel_risk}}',
            'parent_id',
            '{{%policy_travel_risk}}',
            'id',
            'CASCADE'
        );

        $this->batchInsert('policy_travel_risk',
            ['name_ru', 'name_uz', 'name_en', 'weight', 'status', 'created_at', 'updated_at'],
            [
                ['Несчастный случай', 'Baxtsiz hodisa', 'Accident insurance', 1, 1, time(), time()],
                ['Медицинские услуги', 'Tibbiy xizmatlar', 'Medical services', 2, 1, time(), time()],
                ['Медико-транспортные услуги', 'Tibbiy transport xizmatlari', 'Medical transportation', 3, 1, time(), time()],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%policy_travel_risk}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel_risk-parent_id}}',
            '{{%policy_travel_risk}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-policy_travel_risk-parent_id}}',
            '{{%policy_travel_risk}}'
        );

        $this->dropTable('{{%policy_travel_risk}}');
    }
}
