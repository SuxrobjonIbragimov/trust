<?php

use backend\models\menu\MenuItems;
use yii\db\Migration;

class m170823_044950_site_setting extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('settings', [
            'id' => $this->primaryKey(),
            'key' => $this->string(64)->notNull()->unique(),
            'label' => $this->string(64)->notNull(),
            'value' => $this->text()->null(),
            'type' => $this->smallInteger()->notNull()->defaultValue(0),
            'required' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->insert(MenuItems::tableName(), [
//            'id' => 21,
            'menu_id' => 1,
            'parent_id' => 16,
            'label' => 'Settings',
            'url' => '/site/setting',
            'class' => 'fa fa-gears',
            'icon' => '',
            'description' => '',
            'weight' => 10,
            'status' => MenuItems::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $items = [
            ['logo',	'Logo',	'/themes/v1/images/logo/site-logo.png',	1,	1,	time(),	time()],
            ['site_name',	'Site Name',	'Company name sitename.uz | Site info',	0,	1,	time(),	time()],
            ['home_page_footer_text',	'Home Page Footer Text',	'<p><span style=\"font-size:12px;\"><strong>Company name sitename.uz - Insurance Company in Uzbekistan, Tashkent.</strong></span></p>',	3,	1,	time(),	time()],
            ['home_page_meta_keywords',	'Home Page Meta Keywords',	'meta keywords',	0,	1,	time(),	time()],
            ['home_page_meta_description',	'Home Page Meta Description',	'Meta description Company name sitename.uz.',	2,	1,	time(),	time()],
            ['offer_text',	'Offer text',	'<p>&nbsp;</p><p align=\"center\">Публичная оферта </p><div><p align=\"center\">Конец формы</p></div>',	3,	1,	time(),	time()],
            ['logo_png',	'Logo in PDF',	'/themes/v1/images/logo/logo.png',	1,	1,	time(),	time()],
            ['footer_logo',	'Footer logo',	'/themes/v1/images/logo/logo.png',	1,	1,	time(),	time()],
            ['main_phone',	'Main phone',	'(+998) 71-123-45-67',	1,	1,	time(),	time()],
            ['main_email',	'Main email',	'info@sitename.uz',	1,	1,	time(),	time()],
            ['main_telegram',	'Main telegram',	'https://t.me',	1,	1,	time(),	time()],

            ['offer_osgo_ru',	'Public offer osgo ru',	'/themes/v1/files/offer/osgo/pravila_ru.pdf',	1,	1,	time(),	time()],
            ['offer_osgo_uz',	'Public offer osgo uz',	'/themes/v1/files/offer/osgo/pravila_uz.pdf',	1,	1,	time(),	time()],
            ['offer_osgo_en',	'Public offer osgo en',	'/themes/v1/files/offer/osgo/pravila_en.pdf',	1,	1,	time(),	time()],

            ['offer_travel_ru',	'Public offer travel ru',	'/themes/v1/files/offer/travel/pravila.pdf',	1,	1,	time(),	time()],
            ['offer_travel_uz',	'Public offer travel uz',	'/themes/v1/files/offer/travel/pravila.pdf',	1,	1,	time(),	time()],
            ['offer_travel_en',	'Public offer travel en',	'/themes/v1/files/offer/travel/pravila.pdf',	1,	1,	time(),	time()],

        ];

        $this->batchInsert('settings',
            ['key', 'label', 'value', 'type', 'required', 'created_at', 'updated_at' ],
            $items
        );


    }

    public function down()
    {
        $this->delete(MenuItems::tableName(), ['id' => 21]);
        $this->dropTable('settings');
    }
}
