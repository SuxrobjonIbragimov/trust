<?php
namespace backend\modules\policy\migrations;

use Yii;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%handbook_country}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%handbook_country}}`
 */
class m220823_080803_create_handbook_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%handbook_country}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(),
            'name_uz' => $this->string(),
            'name_en' => $this->string(),
            'parent_id' => $this->integer()->null(),
            'ins_id' => $this->integer()->null(),
            'code' => $this->string()->null(),
            'flag' => $this->string()->null(),
            'is_shengen' => $this->boolean(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-handbook_country-parent_id}}',
            '{{%handbook_country}}',
            'parent_id'
        );

        // add foreign key for table `{{%handbook_country}}`
        $this->addForeignKey(
            '{{%fk-handbook_country-parent_id}}',
            '{{%handbook_country}}',
            'parent_id',
            '{{%handbook_country}}',
            'id',
            'CASCADE'
        );

//        $sql = file_get_contents(__DIR__ . '/seeder/handbook_country.sql');
//        $command = Yii::$app->db->createCommand($sql);
//        $command->execute();
//
//        if ($this->db->driverName == 'pgsql') {
//            $sqlSequence = "SELECT setval('public.handbook_country_id_seq', 281, true);";
//            $commandSequence = Yii::$app->db->createCommand($sqlSequence);
//            $commandSequence->execute();
//
//        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%handbook_country}}`
        $this->dropForeignKey(
            '{{%fk-handbook_country-parent_id}}',
            '{{%handbook_country}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-handbook_country-parent_id}}',
            '{{%handbook_country}}'
        );

        $this->dropTable('{{%handbook_country}}');
    }
}
