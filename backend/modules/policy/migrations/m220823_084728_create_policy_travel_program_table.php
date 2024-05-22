<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_program}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel_program}}`
 */
class m220823_084728_create_policy_travel_program_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_program}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(),
            'name_uz' => $this->string(),
            'name_en' => $this->string(),
            'covid' => $this->boolean()->defaultValue(false),
            'parent_id' => $this->integer()->null(),
            'ins_id' => $this->integer()->null(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-policy_travel_program-parent_id}}',
            '{{%policy_travel_program}}',
            'parent_id'
        );

        // add foreign key for table `{{%policy_travel_program}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_program-parent_id}}',
            '{{%policy_travel_program}}',
            'parent_id',
            '{{%policy_travel_program}}',
            'id',
            'CASCADE'
        );

        $this->batchInsert('policy_travel_program',
            ['name_ru', 'name_uz', 'name_en', 'weight', 'status', 'created_at', 'updated_at'],
            [
                ['MINIMUM', 'MINIMUM', 'MINIMUM', 1, 1, time(), time()],
                ['ECANOM', 'ECANOM', 'ECANOM', 2, 1, time(), time()],
                ['OPTIMA', 'OPTIMA', 'OPTIMA', 3, 1, time(), time()],
                ['STANDART', 'STANDART', 'STANDART', 4, 1, time(), time()],
                ['LUKS', 'LUKS', 'LUKS', 5, 1, time(), time()],
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
            '{{%fk-policy_travel_program-parent_id}}',
            '{{%policy_travel_program}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-policy_travel_program-parent_id}}',
            '{{%policy_travel_program}}'
        );

        $this->dropTable('{{%policy_travel_program}}');
    }
}
