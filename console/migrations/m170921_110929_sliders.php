<?php

use backend\models\sliders\SliderItems;
use yii\db\Migration;
use backend\models\sliders\Sliders;
use backend\models\menu\MenuItems;

class m170921_110929_sliders extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('sliders', [
            'id' => $this->primaryKey(),
            'key' => $this->string(64)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('slider_items', [
            'id' => $this->primaryKey(),
            'slider_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'subtitle' => $this->string()->notNull(),
            'image' => $this->string()->notNull(),
            'link' => $this->string()->null(),
            'description' => $this->text()->null(),
            'weight' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk-slider_items-slider_id', 'slider_items', 'slider_id', 'sliders', 'id', 'CASCADE', 'NO ACTION');

        $this->insert(MenuItems::tableName(), [
            'menu_id' => 1,
            'parent_id' => 14,
            'label' => 'Sliders',
            'url' => '/sliders/index',
            'class' => 'fa fa-sliders',
            'icon' => '',
            'description' => '',
            'weight' => -49,
            'status' => MenuItems::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $this->batchInsert('sliders',
            ['key', 'name',  'status', 'created_at', 'updated_at', ],
            [
                ['home_main_slider', 'Home Main Slider (550x550)', Sliders::STATUS_ACTIVE, time(), time(),],
            ]
        );

        $this->batchInsert('slider_items',
            ['slider_id', 'title', 'subtitle', 'description', 'link', 'image', 'weight', 'status', 'created_at', 'updated_at'],
            [
                [1, 'Онлайн оформление полиса ОСГОВТС', 'Sug’urta to’lovlari', '', '/policy/osgo/calculate', '/themes/v1/images/headerBg2.jpg', 0, SliderItems::STATUS_ACTIVE, time(), time()],
                [1, 'Онлайн оформление полиса ОСГОВТС 2', 'Sug’urta to’lovlari 2', '', '/policy/osgo/calculate', '/themes/v1/images/headerBg2.jpg', 0, SliderItems::STATUS_ACTIVE, time(), time()],
                [1, 'Онлайн оформление полиса ОСГОВТС 3', 'Sug’urta to’lovlari 3', '', '/policy/osgo/calculate', '/themes/v1/images/headerBg2.jpg', 0, SliderItems::STATUS_ACTIVE, time(), time()],
            ]
        );
    }

    public function down()
    {
        $this->delete(MenuItems::tableName(), ['id' => 37]);
        $this->dropTable('slider_items');
        $this->dropTable('sliders');
    }
}
