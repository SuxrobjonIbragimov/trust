<?php

namespace backend\modules\policy\migrations;
use Yii;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%handbook_oked}}`.
 */
class m240529_121838_create_handbook_oked_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%handbook_okonh}}', [
            'id' => $this->primaryKey(),
            'oked_id' => $this->bigInteger()->null(),
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
        $this->createIndex('{{%idx-handbook_okonh-oked_id}}', '{{%handbook_okonh}}', 'oked_id');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-handbook_okonh-oked_id}}', '{{%handbook_okonh}}', 'oked_id', '{{%handbook_oked}}', 'id', 'CASCADE');

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-handbook_okonh-created_by}}',
            '{{%handbook_okonh}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-handbook_okonh-created_by}}',
            '{{%handbook_okonh}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-handbook_okonh-updated_by}}',
            '{{%handbook_okonh}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-handbook_okonh-updated_by}}',
            '{{%handbook_okonh}}',
            'updated_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $sql = file_get_contents(__DIR__ . '/seeder/handbook_oked.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();

        if ($this->db->driverName == 'pgsql') {
            $sqlSequence = "SELECT setval('public.handbook_oked_id_seq', 683, true);";
            $commandSequence = Yii::$app->db->createCommand($sqlSequence);
            $commandSequence->execute();
        }

        $sql = file_get_contents(__DIR__ . '/seeder/handbook_okonh.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();

        if ($this->db->driverName == 'pgsql') {
            $sqlSequence = "SELECT setval('public.handbook_okonh_id_seq', 1713, true);";
            $commandSequence = Yii::$app->db->createCommand($sqlSequence);
            $commandSequence->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-handbook_okonh-created_by}}',
            '{{%handbook_okonh}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-handbook_okonh-created_by}}',
            '{{%handbook_okonh}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-handbook_okonh-updated_by}}',
            '{{%handbook_okonh}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-handbook_okonh-updated_by}}',
            '{{%handbook_okonh}}'
        );

        $this->dropTable('{{%handbook_okonh}}');
    }
}
