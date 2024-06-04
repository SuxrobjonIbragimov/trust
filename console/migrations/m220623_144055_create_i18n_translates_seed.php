<?php

use yii\db\Migration;

/**
 * Class m220623_144055_create_i18n_translates_seed
 */
class m220623_144055_create_i18n_translates_seed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = file_get_contents(__DIR__ . '/seeder/language_source.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();

        if ($this->db->driverName == 'pgsql') {
            $sqlSequence = "SELECT setval('public.language_source_id_seq', 2191, true);";
            $commandSequence = Yii::$app->db->createCommand($sqlSequence);
            $commandSequence->execute();
        }

        $sql = file_get_contents(__DIR__ . '/seeder/language_translate.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();

        $sql = file_get_contents(__DIR__ . '/seeder/translate_database.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%language_source}}', ['category' => 'menu_items']);
    }

}
