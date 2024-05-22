<?php

use yii\db\Migration;

class m170808_051348_translate_database extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('translate_database', [
            'key' => $this->string()->notNull(),
            'language' => $this->string(5)->notNull(),
            'translation' => $this->text()->null(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-translate_database', 'translate_database', ['key', 'language']);

    }

    public function down()
    {
        $this->dropTable('translate_database');
    }
}
