<?php

namespace backend\modules\handbook\migrations; // add this namespace in console/config main controllerMap['migrate']

use yii\db\Migration;

/**
 * Handles the creation of table `{{%handbook_legal_type}}`.
 */
class m220527_024546_create_handbook_legal_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%handbook_legal_type}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(),
            'name_uz' => $this->string(),
            'name_en' => $this->string(),
            'description' => $this->text(),
            'image' => $this->string(),
            'icon' => $this->string(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $row_data = [
            ['Физическое лицо', 'Jismoniy shahs', 'Individual',  0, 1, time(), time()],
            ['Юридическое лицо', 'Yuridik shahs', 'Entity', 1, 1, time(), time()],
        ];

        $this->batchInsert('handbook_legal_type',
            ['name_ru', 'name_uz', 'name_en', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%handbook_legal_type}}');
    }
}
