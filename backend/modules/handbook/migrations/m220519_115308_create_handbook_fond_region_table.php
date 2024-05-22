<?php

namespace backend\modules\handbook\migrations;

use Yii;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%handbook_fond_region}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%handbook_fond_region}}`
 */
class m220519_115308_create_handbook_fond_region_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%handbook_fond_region}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(),
            'name_en' => $this->string(),
            'name_uz' => $this->string(),
            'parent_id' => $this->integer()->null(),
            'ins_id' => $this->integer()->null(),
            'territory_id' => $this->integer()->null(),
            'car_number_prefixes' => $this->string()->null(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-handbook_fond_region-parent_id}}',
            '{{%handbook_fond_region}}',
            'parent_id'
        );

        // add foreign key for table `{{%handbook_fond_region}}`
        $this->addForeignKey(
            '{{%fk-handbook_fond_region-parent_id}}',
            '{{%handbook_fond_region}}',
            'parent_id',
            '{{%handbook_fond_region}}',
            'id',
            'CASCADE'
        );

        $sql = file_get_contents(__DIR__ . '/seeder/handbook_fond_region.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();

        if ($this->db->driverName == 'pgsql') {
            $count = 265;
            $sqlSequence = "SELECT setval('public.handbook_fond_region_id_seq', {$count}, true);";
            $commandSequence = Yii::$app->db->createCommand($sqlSequence);
            $commandSequence->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%handbook_fond_region}}`
        $this->dropForeignKey(
            '{{%fk-handbook_fond_region-parent_id}}',
            '{{%handbook_fond_region}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-handbook_fond_region-parent_id}}',
            '{{%handbook_fond_region}}'
        );

        $this->dropTable('{{%handbook_fond_region}}');
    }
}
