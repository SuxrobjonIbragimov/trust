<?php

use backend\models\menu\MenuItems;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%insert_menu_seed}}`.
 */
class m220605_135353_create_insert_menu_seed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        // INSERT TO FRONT MENU
        $this->batchInsert(MenuItems::tableName(),
            ['menu_id', 'parent_id', 'label', 'url', 'weight', 'status', 'created_at', 'updated_at'],
            [
                [2, NULL, 'О компании', '/page/about', -100, MenuItems::STATUS_ACTIVE, time(), time()], //73
                [2, 73, 'О нас', '/page/about', -100, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'История компании', '/page/view/history_company', -93, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Устав компании и внутренние положения', '/post/category/company_charter', -86, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Организационная структура', '/page/view/structure', -81, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Сведения о коллегиальных и совещательных органах', '/page/view/about_collegiate', -75, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Руководство и управление', '/post/category/head', -69, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Стратегия развития', '/post/category/strategy', -61, MenuItems::STATUS_ACTIVE, time(), time()], //60
                [2, 73, 'Лицензии и сертификаты', '/post/category/license', 6, MenuItems::STATUS_ACTIVE, time(), time()], //65
                [2, 73, 'Нормативные документы', '/post/category/regulations', 6, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Клиенты', '/post/category/clients', 8, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Партнеры', '/post/category/partners', 9, MenuItems::STATUS_ACTIVE, time(), time()], //70
                [2, 73, 'Вакансии', '/post/category/vacancy', 10, MenuItems::STATUS_ACTIVE, time(), time()], //71

                [2, 73, 'Жамият предмети ва мақсадлари', '/page/view/subject_goals', -77, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Бизнес План', '/post/category/business_plan', -59, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 73, 'Отчёт о проделанной работе', '/post/category/progress_report', -58, MenuItems::STATUS_ACTIVE, time(), time()],


                [2, NULL, 'Акционерам', '/product', -90, MenuItems::STATUS_ACTIVE, time(), time()], //89
                [2, 89, 'Существенные факты', '/post/category/material_facts', -100, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 89, 'Аффилированные лица', '/post/category/affiliate_list', -95, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 89, 'Проспекты эмиссии ценных бумаг и решение о выпуске', '/post/category/valuable_papers', -85, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 89, 'Начисленные и выплаченные дивиденды','/post/category/paid_dividends', -78, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 89, 'Финансовая отчетность', '/post/category/finance', -71, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 89, 'Итоги голосования по принятым на общем собрании акционеров', '/post/category/results_voting', -64, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 89, 'Новости для акционеров', '/post/category/news_shareholders', -59, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 89, 'Корпоративное управление', '/post/category/corporate_governance', -51, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 89, 'Информация о приобретении обществом акций', '/post/category/information_acquisition', -40, MenuItems::STATUS_ACTIVE, time(), time()],

                [2, 89, 'Аудиторское заключение', '/post/category/audit_report', -62, MenuItems::STATUS_ACTIVE, time(), time()],


                [2, NULL, 'Услуги компании', '/product', 1, MenuItems::STATUS_ACTIVE, time(), time()], //100
                [2, 100, 'Физическим лицам', '/product?entity=0', 0, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 100, 'Юридическим лицам', '/product?entity=1', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 100, 'Страховой случай', '/product/insurance-case', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 100, 'Проверка полиса', '/policy/check', 3, MenuItems::STATUS_ACTIVE, time(), time()],

                [2, 100, 'Проверка статуса заявления', '/page/check-status', 30, MenuItems::STATUS_ACTIVE, time(), time()],


                [2, NULL, 'Пресс-центр', '/post/category/news', 2, MenuItems::STATUS_ACTIVE, time(), time()], //106
                [2, 106, 'Новости', '/post/category/news', 0, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 106, 'Мероприятия', '/post/category/events', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 106, 'Тендеры', '/post/category/tenders', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 106, 'Фото из жизни компании', '/post/category/gallery', 2, MenuItems::STATUS_ACTIVE, time(), time()], //Фотогалерея

                [2, NULL, 'Библиотека', '/post/category/laws', 3, MenuItems::STATUS_ACTIVE, time(), time()], //111
                [2, 111, 'Законы', '/post/category/laws', 0, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 111, 'Кодексы', '/post/category/codex', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 111, 'Указы и постановления', '/post/category/decree', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [2, 111, 'Положения', '/post/category/position', 1, MenuItems::STATUS_ACTIVE, time(), time()],

                [2, NULL, 'Филиалы', '/post/category/branches', 15, MenuItems::STATUS_ACTIVE, time(), time()], //116
                [2, 116, 'Офисы', '/page/branches', 0, MenuItems::STATUS_INACTIVE, time(), time()],
                [2, 116, 'Пункты продаж', '/page/branches', 1, MenuItems::STATUS_INACTIVE, time(), time()],

            ]
        );

        // INSERT TO ADMIN MENU
        $this->batchInsert(MenuItems::tableName(),
            ['menu_id', 'parent_id', 'label', 'url', 'class', 'weight', 'status', 'created_at', 'updated_at'],
            [
                [1, 10, 'Translate ru', '/translatemanager/language/translate?language_id=ru-RU', 'fa fa-language', 10, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 10, 'Translate uz', '/translatemanager/language/translate?language_id=uz-UZ', 'fa fa-language', 11, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 10, 'Translate en', '/translatemanager/language/translate?language_id=en-US', 'fa fa-language', 12, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 14, 'Insurance products', '/insurance-product/index', 'fa fa-list-alt', 16, MenuItems::STATUS_ACTIVE, time(), time()],
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(MenuItems::tableName(), ['id' => [73,69,74,79,84,87,92]]);
    }
}
