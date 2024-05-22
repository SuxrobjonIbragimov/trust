<?php

use backend\models\menu\MenuItems;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%source_counter}}`.
 */
class m221022_090708_create_source_counter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%source_counter}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'code' => $this->string(),
            'lang' => $this->string(),
            'redirect_url' => $this->string(),
            'count' => $this->integer()->defaultValue(0),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->insert(MenuItems::tableName(), [
            'menu_id' => 1,
            'parent_id' => 18,
            'label' => 'Source Counter',
            'url' => '/source-counter',
            'class' => 'fa fa-external-link',
            'icon' => '',
            'description' => '',
            'weight' => 100,
            'status' => MenuItems::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%source_counter}}');
    }
}
