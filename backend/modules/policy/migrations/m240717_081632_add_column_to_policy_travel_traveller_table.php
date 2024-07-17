<?php

namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%policy_travel_traveller}}`.
 */
class m240717_081632_add_column_to_policy_travel_traveller_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('policy_travel_traveller','is_parent',$this->boolean()->defaultValue('f'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('policy_travel_traveller','is_parent');
    }
}
