<?php

namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_abroad_type}}`.
 */
class m220822_115632_create_policy_travel_abroad_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_abroad_type}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(),
            'name_uz' => $this->string(),
            'name_en' => $this->string(),
            'ins_id' => $this->integer()->null(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%policy_travel_abroad_type}}`
        $this->dropForeignKey('{{%fk-policy_travel-abroad_type_id}}', '{{%policy_travel}}');
        // drops index for column `abroad_type_id`
        $this->dropIndex('{{%idx-policy_travel-abroad_type_id}}', '{{%policy_travel}}' );

        $this->dropTable('{{%policy_travel_abroad_type}}');

        $this->dropColumn('{{%policy_travel}}', 'abroad_type_id');
        $this->dropColumn('{{%policy_travel}}', 'uuid_ins');
        $this->dropColumn('{{%policy_travel}}', 'key');
    }
}
