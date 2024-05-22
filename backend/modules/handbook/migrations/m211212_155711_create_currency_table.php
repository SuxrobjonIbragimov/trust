<?php

namespace backend\modules\handbook\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m211212_155711_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%handbook_currency}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(),
            'rate' => $this->double()->defaultValue(0),
            'date' => $this->date(),
            'date_time' => $this->string(),
            'created_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%handbook_currency}}');
    }
}
