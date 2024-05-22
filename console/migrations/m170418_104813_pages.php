<?php

use yii\db\Migration;
use backend\models\page\Pages;
use backend\models\menu\MenuItems;

class m170418_104813_pages extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(Pages::tableName(), [
            'id' => $this->primaryKey(),
            'url' => $this->string(32)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            'image' => $this->string(),
            'body' => $this->text()->null(),
            'meta_title' => $this->string(64),
            'meta_keywords' => $this->string()->null(),
            'meta_description' => $this->text()->null(),
            'weight' => $this->integer()->defaultValue(1),
            'status' => $this->smallInteger()->notNull()->defaultValue(Pages::STATUS_INACTIVE),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->batchInsert(Pages::tableName(),
            ['url', 'name', 'body', 'meta_title', 'status', 'created_at', 'updated_at', ],
            [
                ['about', 'О нас', 'О нас', 'О нас', Pages::STATUS_ACTIVE, time(), time(),],
                ['history_company', 'История компании', 'История компании', 'История компании', Pages::STATUS_ACTIVE, time(), time(),],
                ['company_charter', 'Устав компании', 'Устав компании', 'Устав компании', Pages::STATUS_ACTIVE, time(), time(),],
                ['structure', 'Организационная структура', 'Организационная структура', 'Организационная структура', Pages::STATUS_ACTIVE, time(), time(),], // 4
                ['contact', 'Связаться с нами', '
                    <h4>Оставьте нам сообщение</h4>
                    <p>Не стесняйтесь обращаться к нам. Мы ответим вам в ближайшее время.</p>
                    <div class="address">
                        <p><span>Адрес: </span>Ташкент, Юнусабадский р-н, пр. Шарафа Рашидова, дом x</p>
                        <p><span>Номер: </span><a href="tel:+998781505150">(+998) 71-123-45-67</a></p>
                        <p><span>Почта: </span><a href="mailto: email@sitename.uz">email@sitename.uz</a></p>
                    </div>', 'Связаться с нами', Pages::STATUS_ACTIVE, time(), time(),
                ],
                ['insurance-case', 'Страховой случай', 'Страховой случай', 'Страховой случай', Pages::STATUS_ACTIVE, time(), time(),],
                ['privacy', 'Политика конфиденциальности', 'Политика конфиденциальности', 'Политика конфиденциальности', Pages::STATUS_ACTIVE, time(), time(),],
                ['terms_of_service', 'Условия обслуживания', 'Условия обслуживания', 'Условия обслуживания', Pages::STATUS_ACTIVE, time(), time(),],  // 8
                ['osgo_calc', 'Онлайн оформление полиса ОСГОВТС', 'Онлайн оформление полиса ОСГОВТС', 'Онлайн оформление полиса ОСГОВТС', Pages::STATUS_ACTIVE, time(), time(),],
                ['osgo_anketa', 'Онлайн оформление полиса ОСГОВТС', 'Онлайн оформление полиса ОСГОВТС', 'Онлайн оформление полиса ОСГОВТС', Pages::STATUS_ACTIVE, time(), time(),],
                ['osgo_approve', 'Онлайн оформление полиса', 'Онлайн оформление полиса', 'Онлайн оформление полиса', Pages::STATUS_ACTIVE, time(), time(),],
                ['about_collegiate', 'Сведения о коллегиальных и совещательных органах', 'Сведения о коллегиальных и совещательных органах', 'Сведения о коллегиальных и совещательных органах', Pages::STATUS_ACTIVE, time(), time(),], // 12
                ['check_status_app', 'Проверка статуса заявления', 'Проверка статуса заявления', 'Проверка статуса заявления', Pages::STATUS_ACTIVE, time(), time(),], // 13
                ['subject_goals', 'Жамият предмети ва мақсадлари', 'Жамият предмети ва мақсадлари', 'Жамият предмети ва мақсадлари', Pages::STATUS_ACTIVE, time(), time(),], // 14
            ]
        );

        $this->insert(MenuItems::tableName(), [
            'menu_id' => 1,
            'parent_id' => 14,
            'label' => 'Pages',
            'url' => '/pages/index',
            'class' => 'fa fa-list-alt',
            'icon' => '',
            'description' => '',
            'weight' => -50,
            'status' => MenuItems::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

    }

    public function down()
    {
        $this->delete(MenuItems::tableName(), ['id' => 17]);
        $this->dropTable(Pages::tableName());
    }
}
