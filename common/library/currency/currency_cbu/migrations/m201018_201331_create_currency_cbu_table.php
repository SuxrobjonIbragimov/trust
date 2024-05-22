<?php

namespace common\library\currency\currency_cbu\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency_cbu}}`.
 */
class m201018_201331_create_currency_cbu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency_cbu}}', [
            'id' => $this->primaryKey(),
            'cbu_id' => $this->integer(),
            'code' => $this->string(),
            'ccy' => $this->string(),
            'ccy_nm' => $this->string(),
            'nominal' => $this->integer(),
            'rate' => $this->float(),
            'diff' => $this->float(),
            'date' => $this->string(),
            'weight' => $this->integer(),
            'created_at' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency_cbu}}');
    }
}
