<?php
namespace backend\modules\policy\migrations;

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%policy_osgo}}`.
 */
class m240930_110323_add_checking_account_column_to_policy_osgo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('policy_osgo', 'checking_account', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('policy_osgo', 'checking_account');
    }

}
