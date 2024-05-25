<?php
namespace backend\modules\policy\migrations;

use Yii;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%policy_travel_purpose}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%policy_travel_purpose}}`
 */
class m220823_083000_create_policy_travel_purpose_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%policy_travel_purpose}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(),
            'name_uz' => $this->string(),
            'name_en' => $this->string(),
            'parent_id' => $this->integer()->null(),
            'ins_id' => $this->integer()->null(),
            'rate' => $this->float()->null(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-policy_travel_purpose-parent_id}}',
            '{{%policy_travel_purpose}}',
            'parent_id'
        );

        // add foreign key for table `{{%policy_travel_purpose}}`
        $this->addForeignKey(
            '{{%fk-policy_travel_purpose-parent_id}}',
            '{{%policy_travel_purpose}}',
            'parent_id',
            '{{%policy_travel_purpose}}',
            'id',
            'CASCADE'
        );


//        $sql = file_get_contents(__DIR__ . '/seeder/policy_travel_purpose.sql');
//        $command = Yii::$app->db->createCommand($sql);
//        $command->execute();
//
//        if ($this->db->driverName == 'pgsql') {
//            $sqlSequence = "SELECT setval('public.policy_travel_purpose_id_seq', 5, true);";
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
        // drops foreign key for table `{{%policy_travel_purpose}}`
        $this->dropForeignKey(
            '{{%fk-policy_travel_purpose-parent_id}}',
            '{{%policy_travel_purpose}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-policy_travel_purpose-parent_id}}',
            '{{%policy_travel_purpose}}'
        );

        $this->dropTable('{{%policy_travel_purpose}}');
    }
}
