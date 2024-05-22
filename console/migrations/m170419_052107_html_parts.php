<?php

use yii\db\Migration;
use backend\models\menu\MenuItems;
use backend\models\parts\HtmlParts;

class m170419_052107_html_parts extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(HtmlParts::tableName(), [
            'id' => $this->primaryKey(),
            'key' => $this->string(32)->null()->unique(),
            'name' => $this->string(64)->null(),
            'summary' => $this->text()->null(),
            'body' => $this->text()->null(),
            'status' => $this->smallInteger()->null()->defaultValue(HtmlParts::STATUS_INACTIVE),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ], $tableOptions);

        $this->insert(MenuItems::tableName(), [
            'menu_id' => 1,
            'parent_id' => 16,
            'label' => 'Blocks',
            'url' => '/html-parts/index',
            'class' => 'fa fa-cubes',
            'icon' => '',
            'description' => '',
            'weight' => 4,
            'status' => MenuItems::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $row_data = [
            [
                'home_about_us_part',
                'Home page about us block',
                '
                    <p
                            style="">
                        Страховая организация «Company name» работает в сфере общего страхования на рынке
                        Узбекистана с 5
                        августа 2019 года.
                    </p>


                    <p style="">
                        Организация была создана в мае
                        2019 года, а основным и единственным учредителем является АКБ
                        «Кишлок
                        курилиш банк». В настоящее время уставный капитал СО «Company name» составляет 35,0
                        млрд
                        сумов.</p>


                    <p >
                        С 25 февраля 2022 года в соответствии с лицензией СФ 00051, выданной Агентством по развитию
                        страхового
                        рынка при Министерстве финансов Республики Узбекистан, дополнительно расширила свою
                        деятельность
                        в сфере
                        добровольного и обязательного страхования.
                    </p>


                    <p >
                        В соответствии со статьей 39 Закона Республики Узбекистан «О страховой деятельности»,
                        вступившего в силу
                        в новой редакции 25 февраля 2022 года, 11 февраля 2022 года наша Страховая Организация была
                        реорганизована в акционерное общество.
                    </p>


                    <p >
                        В марте 2022 года Рейтинговое агентство «Ахбор-Рейтинг» присвоило нашей Страховой
                        организации
                        рейтинг
                        финансовой устойчивости «uzA+» - «ОЧЕНЬ ВЫСОКИЙ».
                    </p>


                    <p >
                        «Company name» ставит перед собой амбициозные цели, сосредотачивая все свои усилия на
                        достижении
                        высоких результатов в сфере общего страхования. Организация поставила своей основной задачей
                        повышение
                        социальной защиты, благосостояния и интересов своих клиентов за счет высокого уровня
                        обслуживания.
                    </p>',
                '<p >
                        «Company name» ставит перед собой амбициозные цели, сосредотачивая все свои усилия на
                        достижении
                        высоких результатов в сфере общего страхования. Организация поставила своей основной задачей
                        повышение
                        социальной защиты, благосостояния и интересов своих клиентов за счет высокого уровня
                        обслуживания.
                    </p>',
                1,
            ],
            [
                'footer_logo_bottom_text',
                'Footer logo bottom text',
                'На протяжении последних десятилетий нашим девизом было «Там, где ваша безопасность превыше всего».',
                '',
                1,
            ],
            [
                'main_location',
                'Main location',
                '<a href="https://yandex.uz/maps/10335/tashkent/house/YkAYdAJjSU0DQFprfX9zcXRjZg==/?ll=69.253084%2C41.320802&z=17.15"
                   class="footer-info--link d-flex align-items-start">
                    <div class="footer-info--icon">
                        <svg class="footer-info--svg" width="14" height="16" viewbox="0 0 14 16"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M7 0C8.72391 0 10.3772 0.693765 11.5962 1.92867C12.8152 3.16358 13.5 4.83848 13.5 6.58491C13.5 9.36745 11.57 12.3999 7.76 15.7146C7.54815 15.899 7.27804 16.0002 6.99875 16C6.71947 15.9998 6.44953 15.898 6.238 15.7133L5.986 15.4917C2.34467 12.2635 0.5 9.30532 0.5 6.58491C0.5 4.83848 1.18482 3.16358 2.40381 1.92867C3.62279 0.693765 5.27609 0 7 0ZM7 4.05225C6.33696 4.05225 5.70107 4.31908 5.23223 4.79405C4.76339 5.26901 4.5 5.9132 4.5 6.58491C4.5 7.25661 4.76339 7.9008 5.23223 8.37576C5.70107 8.85073 6.33696 9.11756 7 9.11756C7.66304 9.11756 8.29893 8.85073 8.76777 8.37576C9.23661 7.9008 9.5 7.25661 9.5 6.58491C9.5 5.9132 9.23661 5.26901 8.76777 4.79405C8.29893 4.31908 7.66304 4.05225 7 4.05225Z"
                                     fill-opacity="1" />
                        </svg>
                    </div>
                    Республика Узбекистан, г.Ташкент <br> ул.Усмана Насыра, дом 53 Б
                </a>',
                '',
                1,
            ],
        ];

        $this->batchInsert('html_parts',
            ['key', 'name', 'body','summary', 'status',],
            $row_data
        );
    }

    public function down()
    {
        $this->delete(MenuItems::tableName(), ['id' => 20]);
        $this->dropTable(HtmlParts::tableName());
    }
}
