<?php

use backend\models\menu\MenuItems;
use yii\db\Migration;

class m171025_052340_posts extends Migration
{
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable('post_categories', [
            'id' => $this->primaryKey(),
            'slug' => $this->string()->notNull()->unique(),
            'key' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull(),
            'icon' => $this->string()->null(),
            'image' => $this->string()->null(),
            'description' => $this->text()->null(),
            'meta_title' => $this->string()->null(),
            'meta_keywords' => $this->string()->null(),
            'meta_description' => $this->text()->null(),
            'status' => $this->smallInteger()->null()->defaultValue(0),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'deleted_by' => $this->integer()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
            'deleted_at' => $this->integer()->null(),
        ], $tableOptions);

        // creates index for column `created_by`
        $this->createIndex('{{%idx-post_categories-created_by}}', '{{%post_categories}}', 'created_by');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-post_categories-created_by}}', '{{%post_categories}}', 'created_by', '{{%user}}', 'id', 'SET NULL');
        // creates index for column `updated_by`
        $this->createIndex('{{%idx-post_categories-updated_by}}', '{{%post_categories}}', 'updated_by');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-post_categories-updated_by}}', '{{%post_categories}}', 'updated_by', '{{%user}}', 'id', 'SET NULL');
        // creates index for column `deleted_by`
        $this->createIndex('{{%idx-post_categories-deleted_by}}', '{{%post_categories}}', 'deleted_by');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-post_categories-deleted_by}}', '{{%post_categories}}', 'deleted_by', '{{%user}}', 'id', 'SET NULL');


        $this->createTable('posts', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'slug' => $this->string()->notNull()->unique(),
            'title' => $this->string()->notNull(),
            'summary' => $this->text()->null(),
            'body' => $this->text()->null(),
            'icon' => $this->string()->null(),
            'image' => $this->string()->null(),
            'svg_code' => $this->text()->null(),
            'file' => $this->string()->null(),
            'url' => $this->string()->null(),
            'source_link' => $this->string()->null(),
            'published_date' => $this->date()->null(),
            'work_position' => $this->string()->null(),
            'work_phone' => $this->string()->null(),
            'work_email' => $this->string()->null(),
            'work_telegram' => $this->string()->null(),
            'work_days' => $this->string()->null(),
            'work_time' => $this->string()->null(),
            'latitude' => $this->float()->null(),
            'longitude' => $this->float()->null(),
            'address' => $this->string()->null(),
            'type' => $this->string()->null(),
            'meta_title' => $this->string()->null(),
            'meta_keywords' => $this->string()->null(),
            'meta_description' => $this->text()->null(),
            'number_pointer' => $this->integer()->null()->defaultValue(0),
            'views' => $this->integer()->null()->defaultValue(0),
            'weight' => $this->integer()->null()->defaultValue(0),
            'status' => $this->smallInteger()->null()->defaultValue(0),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'deleted_by' => $this->integer()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
            'deleted_at' => $this->integer()->null(),
        ], $tableOptions);

        // creates index for column `created_by`
        $this->createIndex('{{%idx-posts-created_by}}', '{{%posts}}', 'created_by');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-posts-created_by}}', '{{%posts}}', 'created_by', '{{%user}}', 'id', 'SET NULL');
        // creates index for column `updated_by`
        $this->createIndex('{{%idx-posts-updated_by}}', '{{%posts}}', 'updated_by');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-posts-updated_by}}', '{{%posts}}', 'updated_by', '{{%user}}', 'id', 'SET NULL');
        // creates index for column `deleted_by`
        $this->createIndex('{{%idx-posts-deleted_by}}', '{{%posts}}', 'deleted_by');
        // add foreign key for table `{{%user}}`
        $this->addForeignKey('{{%fk-posts-deleted_by}}', '{{%posts}}', 'deleted_by', '{{%user}}', 'id', 'SET NULL');


        $this->createTable('post_file', [
            'post_id' => $this->integer()->notNull(),
            'generate_name' => $this->string(64)->unique()->notNull(),
            'name' => $this->string()->notNull(),
            'path' => $this->string()->notNull(),
            'position' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->addForeignKey('fk-posts-category_id', 'posts', 'category_id', 'post_categories', 'id', 'CASCADE', 'NO ACTION');

        $this->addPrimaryKey('pk-post_file', 'post_file', ['post_id', 'generate_name']);
        $this->createIndex('idx-post_file-post_id', 'post_file', 'post_id');
        $this->createIndex('idx-post_file-generate_name', 'post_file', 'generate_name');
        $this->addForeignKey('fk-post_file-post_id', 'post_file', 'post_id', 'posts', 'id', 'CASCADE', 'RESTRICT');

        // post categories seed
        $row_data = [
                ['company_charter', 'company_charter', 'Устав компании и внутренние положения', 'Устав компании и внутренние положения', 1, time(), time()],
                ['head', 'head', 'Руководство и управление', 'Руководство и управление', 1, time(), time()],
                ['material_facts', 'material_facts', 'Существенные факты', 'Существенные факты', 1, time(), time()],
                ['license', 'license', 'Лицензии и сертификаты', 'Лицензии и сертификаты', 1, time(), time()],
                ['finance', 'finance', 'Финансовая отчетность', 'Финансовая отчетность', 1, time(), time()],
                ['clients', 'clients', 'Клиенты', 'Клиенты', 1, time(), time()],
                ['partners', 'partners', 'Партнеры', 'Партнеры', 1, time(), time()],
                ['vacancy', 'vacancy', 'Вакансии', 'Вакансии', 1, time(), time()],
                ['news', 'news', 'Новости', 'Новости', 1, time(), time()],
                ['events', 'events', 'Мероприятия', 'Мероприятия', 1, time(), time()],
                ['gallery', 'gallery', 'Фото из жизни компании', 'Фото из жизни компании', 1, time(), time()], //Фотогалерея
                ['laws', 'laws', 'Законы', 'Законы', 1, time(), time()],
                ['codex', 'codex', 'Кодексы', 'Кодексы', 1, time(), time()],
                ['decree', 'decree', 'Указы и постановления', 'Указы и постановления', 1, time(), time()],
                ['position', 'position', 'Положения', 'Положения', 1, time(), time()], // 15

                ['why_choose_us', 'why_choose_us', 'Почему выбирают нас?', 'Почему выбирают нас?', 1, time(), time()],
                ['companies_served', 'companies_served', 'Обслуживаемые компании', 'Обслуживаемые компании', 1, time(), time()], // 17

                ['branches', 'branches', 'Наши офисы', 'Наши офисы', 1, time(), time()], // 18
                ['tenders', 'tenders', 'Тендеры', 'Тендеры', 1, time(), time()],
                ['affiliate_list', 'affiliate_list', 'Аффилированные лица', 'Аффилированные лица', 1, time(), time()], // 20
                ['valuable_papers', 'valuable_papers', 'Проспекты эмиссии ценных бумаг и решение о выпуске', 'Проспекты эмиссии ценных бумаг и решение о выпуске', 1, time(), time()],
                ['paid_dividends', 'paid_dividends', 'Начисленные и выплаченные дивиденды', 'Начисленные и выплаченные дивиденды', 1, time(), time()],
                ['faq', 'faq', 'Часто задаваемые вопросы', 'Часто задаваемые вопросы', -1, time(), time()],

                ['strategy', 'strategy', 'Стратегия развития', 'Стратегия развития', 1, time(), time()],   // ID 24
                ['regulations', 'regulations', 'Нормативные документы', 'Нормативные документы', 1, time(), time()],   // ID 25
                ['results_voting', 'results_voting', 'Итоги голосования по принятым на общем собрании акционеров', 'Итоги голосования по принятым на общем собрании акционеров', 1, time(), time()],   // ID 26
                ['news_shareholders', 'news_shareholders', 'Новости для акционеров', 'Новости для акционеров', 1, time(), time()],   // ID 27
                ['corporate_governance', 'corporate_governance', 'Корпоративное управление', 'Корпоративное управление', 1, time(), time()],   // ID 28
                ['information_acquisition', 'information_acquisition', 'Информация о приобретении обществом акций', 'Информация о приобретении обществом акций', 1, time(), time()],   // ID 29

                ['business_plan', 'business_plan', 'Бизнес План', 'Бизнес План', 1, time(), time()],   // ID 30
                ['progress_report', 'progress_report', 'Отчёт о проделанной работе', 'Отчёт о проделанной работе', 1, time(), time()],   // ID 31
                ['audit_report', 'audit_report', 'Аудиторское заключение', 'Аудиторское заключение', 1, time(), time()],   // ID 32
                ['online_voting', 'online_voting', 'Xizmat ko\'rsatish sifatini qanday baholaysiz?', 'Xizmat ko\'rsatish sifatini qanday baholaysiz?', 1, time(), time()],   // ID 33
                ['advantage', 'advantage', 'Наши преимущества', 'Наши преимущества', 1, time(), time()],                // ID 34

        ];

        $this->batchInsert('post_categories',
            ['slug', 'key', 'name', 'meta_title', 'status', 'created_at', 'updated_at'],
            $row_data
        );


        // gallery seed
        $row_data = [
            [11, 'gallery_1', 'Gallery-1', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '/themes/v1/images/gallery/1.jpg', 0, 1, time(), time() ],
            [11, 'gallery_2', 'Gallery-2', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '/themes/v1/images/gallery/2.jpg', 0, 1, time(), time() ],
            [11, 'gallery_3', 'Gallery-3', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '/themes/v1/images/gallery/3.jpg', 0, 1, time(), time() ],
        ];
        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'summary', 'image', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );

        // gallery items seed
        $row_data = [
            [1, 'gallery-001', 'gallery-001', '/themes/v1/images/gallery/6.jpg', 0],
            [1, 'gallery-002', 'gallery-002', '/themes/v1/images/gallery/2.jpg', 1],
            [1, 'gallery-003', 'gallery-003', '/themes/v1/images/gallery/3.jpg', 2],
            [1, 'gallery-004', 'gallery-004', '/themes/v1/images/gallery/4.jpg', 3],
            [1, 'gallery-005', 'gallery-005', '/themes/v1/images/gallery/5.jpg', 4],
            [1, 'gallery-006', 'gallery-006', '/themes/v1/images/gallery/1.jpg', 5],

            [2, 'gallery-01', 'gallery-01', '/themes/v1/images/gallery/2.jpg', 0],
            [2, 'gallery-02', 'gallery-02', '/themes/v1/images/gallery/6.jpg', 1],
            [2, 'gallery-03', 'gallery-03', '/themes/v1/images/gallery/3.jpg', 2],
            [2, 'gallery-04', 'gallery-04', '/themes/v1/images/gallery/4.jpg', 3],
            [2, 'gallery-05', 'gallery-05', '/themes/v1/images/gallery/5.jpg', 4],
            [2, 'gallery-06', 'gallery-06', '/themes/v1/images/gallery/6.jpg', 5],

            [3, 'gallery-1', 'gallery-1', '/themes/v1/images/gallery/4.jpg', 0],
            [3, 'gallery-2', 'gallery-2', '/themes/v1/images/gallery/3.jpg', 1],
            [3, 'gallery-3', 'gallery-3', '/themes/v1/images/gallery/2.jpg', 2],
            [3, 'gallery-4', 'gallery-4', '/themes/v1/images/gallery/4.jpg', 3],
            [3, 'gallery-5', 'gallery-5', '/themes/v1/images/gallery/1.jpg', 4],
            [3, 'gallery-6', 'gallery-6', '/themes/v1/images/gallery/6.jpg', 5],
        ];
        $this->batchInsert('post_file',
            ['post_id', 'generate_name', 'name', 'path', 'position'],
            $row_data
        );

        // Voting seed
        $row_data = [
            [33, 'vote_1', '5 (a`lo)', '5 (a`lo)', NULL, NULL, 0, 1, time(), time() ], // 248
            [33, 'vote_2', '4 (yaxshi)', '4 (yaxshi)', NULL, NULL, 5, 1, time(), time() ], //249
            [33, 'vote_3', '3 (qoniqarli)', '3 (qoniqarli)', NULL, NULL, 15, 1, time(), time() ],
            [33, 'vote_4', '2 (qoniqarsiz)', '2 (qoniqarsiz)', NULL, NULL, 25, 1, time(), time() ],
            [33, 'vote_5', '1 (yomon)', '1 (yomon)', NULL, NULL, 35, 1, time(), time() ],
        ];

        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'meta_title', 'summary', 'image', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );


        // head seed
        $row_data = [
            [2, 'head-3', 'Фамилия Имя Отчество', 'Заместитель генерального директора по финансам', 'Четверг c 9.30 до 11.30 часов', 'Осуществляет анализ финансового состояния детяльности компании и филиалов в регионах, управляет крупными корпоративными продажами, сопровождает деятельность организационной структуры компании.', '/themes/v1/img/default-avatar.jpg', 3, 1, time(), time() ],
            [2, 'head-2', 'Фамилия Имя Отчество', 'Заместитель генерального директора по страхованию', 'Вторник c 14.30 до 16.30 часов', 'Руководит корпоративными продажами, сопровождает деятельность организационной структуры компании, координирует сотрудничество с крупными клиентами, принимает участие в утрвеждении регламентирующих и распорядительных документах, которые напрямую связаны с выполнением обязательств страховой компании.', '/themes/v1/img/default-avatar.jpg', 2, 1, time(), time() ],
            [2, 'head-1', 'Фамилия Имя Отчество', 'Генеральный директор', 'Понедельник с 9.30 до 11.30 часов', 'Курирует и сопровождает деятельность организационной структуры компании, участвует в согласовании внутренних распорядительных и регламентирующих документов, связанных с осуществлением страховой деятельности.', '/themes/v1/img/default-avatar.jpg', 0, 1, time(), time() ],
        ];

        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'work_position', 'work_days', 'summary', 'image', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );

        // company_charter,material_facts, finance-report seed
        $row_data = [
            [1, 'company_charter', 'Устав АО', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [3, 'material_facts-1', 'Cущественный факт № 25 от 01.03.2022', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [3, 'material_facts-2', 'Существенный Факты № 8 от 23.09.2022', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [3, 'material_facts-3', 'Существенный Факты № 6', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [3, 'material_facts-4', 'Существенный Факты № 22', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [3, 'material_facts-5', 'Существенный Факты № 36', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],

            [5, 'finance-1', 'Аудиторское заключение независимого аудитора, 2019 г.', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [5, 'finance-2', 'Бухгалтерский баланс (Форма №1), 2019 г.', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [5, 'finance-3', 'Отчет о финансовых результатах (Форма №2), 2019 г.', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [5, 'finance-4', 'Заключение независимых аудиторов и финансовая отчетность за 2020 год', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [5, 'finance-5', 'Бухгалтерский баланс (Форма №1), 2020 г.', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [5, 'finance-6', 'Отчет о финансовых результатах (Форма №2), 2020 г.', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [5, 'finance-7', 'Заключение независимых аудиторов и финансовая отчетность за 2021 год', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [5, 'finance-8', 'Бухгалтерский баланс (Форма №1), 2021 г.', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],
            [5, 'finance-9', 'Отчет о финансовых результатах (Форма №2), 2021 г.', '/themes/v1/files/example_file.pdf', 0, 1, time(), time() ],

            [12, 'laws-1', 'Закон РУз О страховой деятельности от 05.04.2002г (с изменениями)', '/themes/v1/files/lib/law-1-ru.doc', 0, 1, time(), time() ],
            [12, 'laws-2', 'Закон РУз Об обязательном страховании гражданской ответственности работодателя № 210 от 16.04.2009', '/themes/v1/files/lib/law-2-ru.doc', 1, 1, time(), time() ],
            [12, 'laws-3', 'Закон РУз Об обязательном страховании гражданской ответственности владельцев транспортных средств № 155 от 21.04.2008г (с изменениями)', '/themes/v1/files/lib/law-3-ru.doc', 2, 1, time(), time() ],

            [13, 'codex-1', 'Гражданский кодекс РУз (Глава 52 "Страхование")', '/themes/v1/files/lib/codex-1-ru.doc', 0, 1, time(), time() ],

            [14, 'decree-1', 'Постановление Президента РУз "О мерах по реформированию и обеспечению ускоренного развития страхового рынка Республики Узбекистан" от 02.08.2019 г., № 4412', 'https://lex.uz/docs/4459812', 0, 1, time(), time() ],
            [14, 'decree-2', 'Постановление Президента РУз "О мерах по реформированию и обеспечению ускоренного развития страхового рынка Республики Узбекистан" от 02.08.2019 г., № 4412', 'https://lex.uz/docs/4459812', 1, 1, time(), time() ],
            [14, 'decree-3', 'Постановление Президента РУз "О мерах по реформированию и обеспечению ускоренного развития страхового рынка Республики Узбекистан" от 02.08.2019 г., № 4412', 'https://lex.uz/docs/4459812', 2, 1, time(), time() ],
            [14, 'decree-4', 'Постановление Президента РУз "О мерах по реформированию и обеспечению ускоренного развития страхового рынка Республики Узбекистан" от 02.08.2019 г., № 4412', 'https://lex.uz/docs/4459812', 3, 1, time(), time() ],

            [15, 'position-1', 'Положение "О страховых агентах"', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [20, 'affiliate_list-1', 'Аффилированные лица example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [20, 'affiliate_list-2', 'Аффилированные лица example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [21, 'valuable_papers-1', 'Проспекты эмиссии ценных бумаг и решение о выпуске example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [21, 'valuable_papers-2', 'Проспекты эмиссии ценных бумаг и решение о выпуске example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [22, 'paid_dividends-1', 'Начисленные и выплаченные дивиденды example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [22, 'paid_dividends-2', 'Начисленные и выплаченные дивиденды example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [24, 'strategy-1', 'Стратегия развития example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [24, 'strategy-2', 'Стратегия развития example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [25, 'regulations-1', 'Нормативные документы example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [25, 'regulations-2', 'Нормативные документы example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [26, 'results_voting-1', 'Итоги голосования по принятым на общем собрании акционеров example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [26, 'results_voting-2', 'Итоги голосования по принятым на общем собрании акционеров example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [27, 'news_shareholders-1', 'Новости для акционеров example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [27, 'news_shareholders-2', 'Новости для акционеров example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [28, 'corporate_governance-1', 'Корпоративное управление example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [28, 'corporate_governance-2', 'Корпоративное управление example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [29, 'information_acquisition-1', 'Информация о приобретении обществом акций example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [29, 'information_acquisition-2', 'Информация о приобретении обществом акций example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [30, 'business-plan-1', 'Бизнес план example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [30, 'business-plan-2', 'Бизнес план example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [31, 'progress_report-1', 'Отчёт о проделанной работе example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [31, 'progress_report-2', 'Отчёт о проделанной работе example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

            [32, 'audit_report-1', 'Аудиторское заключение example-1', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],
            [32, 'audit_report-2', 'Аудиторское заключение example-2', '/themes/v1/example_file.pdf', 0, 1, time(), time() ],

        ];

        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'file', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );


        // license,certificate seed
        $row_data = [
            [4, 'license', 'Наши лицензии', '/themes/v1/images/docs/license.png', 0, 1, time(), time() ],
            [4, 'certificate_1', 'Certificate', '/themes/v1/images/docs/certificate.png', 1, 1, time(), time() ],
        ];
        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'image', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );

        // TODO BRANCHES set example
        // branches seed
        $row_data = [
            [18, 'branch_1', 'Андижанская область', 'город Андижан  ул А.Фитрата, дом X', '(00) 123-45-67, (11) 123-45-67', null, null, 0, 1, time(), time() ],
            [18, 'branch_2', 'Бухарская область', 'город Бухара, ул М.Икбала, дом X/1 ', '(00) 123-45-67', null, null, 1, 1, time(), time() ],
            [18, 'branch_3', 'Джизакская область', 'город Джиззак, ул А.Навои, махалля А.Навои, дом X', '(00) 123-45-67, (11) 123-45-67', null, null, 2, 1, time(), time() ],
            [18, 'branch_4', 'Ферганская область', 'город Фергана, ул Ал Фаргоний, дом X', '(00) 123-45-67, (11) 123-45-67', null, null, 3, 1, time(), time() ],
            [18, 'branch_5', 'Наманганская область', 'город Наманган,ул  А.Навои, дом X', '(00) 123-45-67, (11) 123-45-67', null, null, 4, 1, time(), time() ],
            [18, 'branch_6', 'Навоийская область', 'город Навои, улица.Галаба шох, дом  X  А', '(00) 123-45-67, (11) 123-45-67', null, null, 5, 1, time(), time() ],
        ];

        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'address', 'work_phone', 'latitude', 'longitude', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );


        // Vacancy seed
        $row_data = [
            [8, 'job_vacancy_1', 'Job vacancy 1', 'Job vacancy 1', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', '/themes/v1/images/job-vacancy-background.jpg', 0, 1, time(), time() ],
            [8, 'job_vacancy_2', 'Job vacancy 2', 'Job vacancy 2', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', '/themes/v1/images/job-vacancy-background.jpg', 0, 1, time(), time() ],
            [8, 'job_vacancy_3', 'Job vacancy 3', 'Job vacancy 3', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', '/themes/v1/images/job-vacancy-background.jpg', 0, 1, time(), time() ],
        ];

        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'meta_title', 'summary', 'image', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );


        // news, why-choose-us, companies-served seed
        $row_data = [
            [9, 'news-1', 'Организация работает в сфере общего страхования на рынке Узбекистана с 1', '', '/themes/v1/images/news1.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 1',0, 1, time(), time()],
            [9, 'news-2', 'Организация работает в сфере общего страхования на рынке Узбекистана с 2', '', '/themes/v1/images/news2.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 2',1, 1, time(), time()],
            [9, 'news-3', 'Организация работает в сфере общего страхования на рынке Узбекистана с 3', '', '/themes/v1/images/news3.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 3',2, 1, time(), time()],
            [9, 'news-4', 'Организация работает в сфере общего страхования на рынке Узбекистана с 4', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 4',3, 1, time(), time()],
            [9, 'news-5', 'Организация работает в сфере общего страхования на рынке Узбекистана с 5', '', '/themes/v1/images/news5.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 5',4, 1, time(), time()],
            [9, 'news-6', 'Организация работает в сфере общего страхования на рынке Узбекистана с 6', '', '/themes/v1/images/news6.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 6',5, 1, time(), time()],
            [9, 'news-7', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [9, 'news-8', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [9, 'news-9', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [9, 'news-01', 'Организация работает в сфере общего страхования на рынке Узбекистана с 1', '', '/themes/v1/images/news1.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 1',0, 1, time(), time()],
            [9, 'news-02', 'Организация работает в сфере общего страхования на рынке Узбекистана с 2', '', '/themes/v1/images/news2.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 2',1, 1, time(), time()],
            [9, 'news-03', 'Организация работает в сфере общего страхования на рынке Узбекистана с 3', '', '/themes/v1/images/news3.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 3',2, 1, time(), time()],
            [9, 'news-04', 'Организация работает в сфере общего страхования на рынке Узбекистана с 4', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 4',3, 1, time(), time()],
            [9, 'news-05', 'Организация работает в сфере общего страхования на рынке Узбекистана с 5', '', '/themes/v1/images/news5.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 5',4, 1, time(), time()],
            [9, 'news-06', 'Организация работает в сфере общего страхования на рынке Узбекистана с 6', '', '/themes/v1/images/news6.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 6',5, 1, time(), time()],
            [9, 'news-07', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [9, 'news-08', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news1.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [9, 'news-09', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [9, 'news-001', 'Организация работает в сфере общего страхования на рынке Узбекистана с 1', '', '/themes/v1/images/news1.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 1',0, 1, time(), time()],
            [9, 'news-002', 'Организация работает в сфере общего страхования на рынке Узбекистана с 2', '', '/themes/v1/images/news2.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 2',1, 1, time(), time()],
            [9, 'news-003', 'Организация работает в сфере общего страхования на рынке Узбекистана с 3', '', '/themes/v1/images/news3.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 3',2, 1, time(), time()],
            [9, 'news-004', 'Организация работает в сфере общего страхования на рынке Узбекистана с 4', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 4',3, 1, time(), time()],
            [9, 'news-005', 'Организация работает в сфере общего страхования на рынке Узбекистана с 5', '', '/themes/v1/images/news5.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 5',4, 1, time(), time()],
            [9, 'news-006', 'Организация работает в сфере общего страхования на рынке Узбекистана с 6', '', '/themes/v1/images/news6.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 6',5, 1, time(), time()],
            [9, 'news-007', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [9, 'news-008', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news3.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [9, 'news-009', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news2.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],

            [10, 'events-1', 'Организация работает в сфере общего страхования на рынке Узбекистана с 1', '', '/themes/v1/images/news1.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 1',0, 1, time(), time()],
            [10, 'events-2', 'Организация работает в сфере общего страхования на рынке Узбекистана с 2', '', '/themes/v1/images/news2.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 2',1, 1, time(), time()],
            [10, 'events-3', 'Организация работает в сфере общего страхования на рынке Узбекистана с 3', '', '/themes/v1/images/news3.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 3',2, 1, time(), time()],
            [10, 'events-4', 'Организация работает в сфере общего страхования на рынке Узбекистана с 4', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 4',3, 1, time(), time()],
            [10, 'events-5', 'Организация работает в сфере общего страхования на рынке Узбекистана с 5', '', '/themes/v1/images/news5.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 5',4, 1, time(), time()],
            [10, 'events-6', 'Организация работает в сфере общего страхования на рынке Узбекистана с 6', '', '/themes/v1/images/news6.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 6',5, 1, time(), time()],
            [10, 'events-7', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7', '', '/themes/v1/images/news4.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [10, 'events-8', 'Организация работает в сфере общего страхования на рынке Узбекистана с 8', '', '/themes/v1/images/news1.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],
            [10, 'events-9', 'Организация работает в сфере общего страхования на рынке Узбекистана с 9', '', '/themes/v1/images/news3.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Организация работает в сфере общего страхования на рынке Узбекистана с 7',6, 1, time(), time()],

            [16, 'why-choose-us-1', 'Быстрое обслуживание', 'bxs-like', '/themes/v1/images/svg/why-choose-us/fast_service.svg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Быстрое обслуживание',0, 1, time(), time()],
            [16, 'why-choose-us-2', 'Официально сертифицирован', 'bxs-check-shield', '/themes/v1/images/svg/why-choose-us/official.svg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Официально сертифицирован',1, 1, time(), time()],
            [16, 'why-choose-us-3', 'Своевременное обслуживание', 'bxs-time-five', '/themes/v1/images/svg/why-choose-us/modern.svg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Своевременное обслуживание',2, 1, time(), time()],
            [16, 'why-choose-us-4', 'Профессиональная команда', 'bxs-group', '/themes/v1/images/svg/why-choose-us/team.svg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint.', 'Профессиональная команда',3, 1, time(), time()],

            [17, 'companies-served-1', 'Строительство и недвижимость', '', '/themes/v1/images/build-slide.jpg', 'Nulla Lorem mollit cupidatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incididunt aliquip deserunt reprehenderit elit laborum. Aliqua id fugiat nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim. Elit aute irure tempor cupidatat incididunt sint deserunt ut voluptate aute id deserunt nisi.', 'Строительство и недвижимость',0, 1, time(), time()],
            [17, 'companies-served-2', 'Страхование безопасности', '', '/themes/v1/images/sale-life.jpg', 'Nulla Lorem mollit cupidatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incididunt aliquip deserunt reprehenderit elit laborum. Aliqua id fugiat nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim. Elit aute irure tempor cupidatat incididunt sint deserunt ut voluptate aute id deserunt nisi.', 'Страхование безопасности',1, 1, time(), time()],
            [17, 'companies-served-3', 'Оперативное обслуживание', '', '/themes/v1/images/build-slide.jpg', 'Nulla Lorem mollit cupidatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incididunt aliquip deserunt reprehenderit elit laborum. Aliqua id fugiat nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim. Elit aute irure tempor cupidatat incididunt sint deserunt ut voluptate aute id deserunt nisi.', 'Оперативное обслуживание',2, 1, time(), time()],

            [19, 'tenders-1', 'Tenders 1', '', '/themes/v1/images/tender.jpg', 'Nulla Lorem mollit cupidatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incididunt aliquip deserunt reprehenderit elit laborum. Aliqua id fugiat nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim. Elit aute irure tempor cupidatat incididunt sint deserunt ut voluptate aute id deserunt nisi.', 'Tender 1', 0, 1, time(), time()],
            [19, 'tenders-2', 'Tenders 2', '', '/themes/v1/images/tender.jpg', 'Nulla Lorem mollit cupidatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incididunt aliquip deserunt reprehenderit elit laborum. Aliqua id fugiat nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim. Elit aute irure tempor cupidatat incididunt sint deserunt ut voluptate aute id deserunt nisi.', 'Tender 2', 0, 1, time(), time()],
            [19, 'tenders-3', 'Tenders 3', '', '/themes/v1/images/tender.jpg', 'Nulla Lorem mollit cupidatat irure. Laborum magna nulla duis ullamco cillum dolor. Voluptate exercitation incididunt aliquip deserunt reprehenderit elit laborum. Aliqua id fugiat nostrud irure ex duis ea quis id quis ad et. Sunt qui esse pariatur duis deserunt mollit dolore cillum minim tempor enim. Elit aute irure tempor cupidatat incididunt sint deserunt ut voluptate aute id deserunt nisi.', 'Tender 3', 0, 1, time(), time()],
        ];

        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'icon', 'image', 'summary', 'meta_title', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );
        $row_data = [
            [34, 'our-advantage-1', 'Sug’urta bozoridagi yillar', '1', '', '/themes/v1/assets/img/landing/digital-agency/services/01.png', 'Sed morbi nulla pulvinar lectus tempor vel euismod accumsan.', 'Creative Solutions', 0, 1, time(), time()],
            [34, 'our-advantage-2', 'Minnatdor mijozlar', '10', '', '/themes/v1/assets/img/landing/digital-agency/services/02.png', 'Sit facilisis dolor arcu, fermentum vestibulum arcu elementum imperdiet.', 'Award Winning', 0, 1, time(), time()],
            [34, 'our-advantage-3', 'Malakali xodimlar', '90', '', '/themes/v1/assets/img/landing/digital-agency/services/03.png', 'Nam venenatis urna aenean quis feugiat et senectus turpis.', 'Team of Professionals', 0, 1, time(), time()],
            [34, 'our-advantage-4', 'Sug’urta xizmatlari', '80', '', '/themes/v1/assets/img/landing/digital-agency/services/04.png', 'Nam venenatis urna aenean quis feugiat et senectus turpis.', 'Team of Professionals', 0, 1, time(), time()],
            [34, 'our-advantage-5', 'Sug’urta bozoridagi yillar', '1', '', '/themes/v1/assets/img/landing/digital-agency/services/05.png', 'Nam venenatis urna aenean quis feugiat et senectus turpis.', 'Team of Professionals', 0, 0, time(), time()],
            [34, 'our-advantage-6', 'Minnatdor mijozlar', '1', '', '/themes/v1/assets/img/landing/digital-agency/services/05.png', 'Nam venenatis urna aenean quis feugiat et senectus turpis.', 'Team of Professionals', 0, 0, time(), time()],
            [34, 'our-advantage-7', 'Malakali xodimlar', '1', '', '/themes/v1/assets/img/landing/digital-agency/services/05.png', 'Nam venenatis urna aenean quis feugiat et senectus turpis.', 'Team of Professionals', 0, 0, time(), time()],
            [34, 'our-advantage-8', 'Sug’urta xizmatlari', '1', '', '/themes/v1/assets/img/landing/digital-agency/services/05.png', 'Nam venenatis urna aenean quis feugiat et senectus turpis.', 'Team of Professionals', 0, 0, time(), time()],
        ];
        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'number_pointer', 'icon', 'image', 'summary', 'meta_title', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );

        // partners/clients seed
        $row_data = [
            [6, 'client-1', 'Client 1', '/themes/v1/images/svg/sec-partner/coinbase.svg', 0, 1, time(), time() ],
            [6, 'client-2', 'Client 2', '/themes/v1/images/svg/sec-partner/dropbox.svg', 0, 1, time(), time() ],
            [6, 'client-3', 'Client 3', '/themes/v1/images/svg/sec-partner/slack.svg', 0, 1, time(), time() ],
            [6, 'client-4', 'Client 4', '/themes/v1/images/svg/sec-partner/Spotify.svg', 0, 1, time(), time() ],
            [6, 'client-5', 'Client 5', '/themes/v1/images/svg/sec-partner/webflow.svg', 0, 1, time(), time() ],
            [6, 'client-6', 'Client 6', '/themes/v1/images/svg/sec-partner/Zoom.svg', 0, 1, time(), time() ],
            [6, 'client-7', 'Client 7', '/themes/v1/images/svg/sec-partner/webflow.svg', 0, 1, time(), time() ],
            [6, 'client-8', 'Client 8', '/themes/v1/images/svg/sec-partner/Zoom.svg', 0, 1, time(), time() ],
            [6, 'client-9', 'Client 9', '/themes/v1/images/svg/sec-partner/Spotify.svg', 0, 1, time(), time() ],
            [6, 'client-10', 'Client 10', '/themes/v1/images/svg/sec-partner/webflow.svg', 0, 1, time(), time() ],
            [6, 'client-11', 'Client 11', '/themes/v1/images/svg/sec-partner/coinbase.svg', 0, 1, time(), time() ],
            [6, 'client-12', 'Client 12', '/themes/v1/images/svg/sec-partner/dropbox.svg', 0, 1, time(), time() ],

            [7, 'partner-1', 'Partner 1', '/themes/v1/images/svg/sec-partner/coinbase.svg', 0, 1, time(), time() ],
            [7, 'partner-2', 'Partner 2', '/themes/v1/images/svg/sec-partner/dropbox.svg', 0, 1, time(), time() ],
            [7, 'partner-3', 'Partner 3', '/themes/v1/images/svg/sec-partner/slack.svg', 0, 1, time(), time() ],
            [7, 'partner-4', 'Partner 4', '/themes/v1/images/svg/sec-partner/Spotify.svg', 0, 1, time(), time() ],
            [7, 'partner-5', 'Partner 5', '/themes/v1/images/svg/sec-partner/webflow.svg', 0, 1, time(), time() ],
            [7, 'partner-6', 'Partner 6', '/themes/v1/images/svg/sec-partner/Zoom.svg', 0, 1, time(), time() ],
        ];

        $this->batchInsert('posts',
            ['category_id', 'slug', 'title', 'image', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );


        // INSERT TO ADMIN MENU
        $this->batchInsert(MenuItems::tableName(),
            ['menu_id', 'parent_id', 'label', 'url', 'class', 'weight', 'status', 'created_at', 'updated_at'],
            [
                [1, 14, 'Post Categories', '/post',  'fa fa-tasks', -100, MenuItems::STATUS_ACTIVE, time(), time()],

                [1, 14, 'О компании', '#',  'fa fa-circle-o', -99, MenuItems::STATUS_ACTIVE, time(), time()], //25
                [1, 25, 'О нас', '/pages/view?id=1', 'fa fa-circle-o', -51, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'История компании', '/pages/view?id=2', 'fa fa-circle-o', -47, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Устав компании и внутренние положения', '/post/view?id=1',  'fa fa-circle-o', -44, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Организационная структура', '/pages/view?id=4', 'fa fa-circle-o', -40, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Сведения о коллегиальных и совещательных органах', '/pages/view?id=12', 'fa fa-circle-o', -37, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Руководство и управление', '/post/view?id=2', 'fa fa-circle-o', -33, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Стратегия развития', '/post/view?id=24', 'fa fa-circle-o', -29, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Лицензии и сертификаты', '/post/view?id=4', 'fa fa-circle-o', -24, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Нормативные документы', '/post/view?id=25', 'fa fa-circle-o', -19, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Клиенты', '/post/view?id=6', 'fa fa-circle-o', -14, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Партнеры', '/post/view?id=7', 'fa fa-circle-o', -13, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Вакансии', '/post/view?id=8', 'fa fa-circle-o', -11, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Почему выбирают нас?', '/post/view?id=16', 'fa fa-circle-o', 50, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 25, 'Обслуживаемые компании', '/post/view?id=17', 'fa fa-circle-o', 60, MenuItems::STATUS_INACTIVE, time(), time()],
                [1, 25, 'Политика конфиденциальности', '/pages/view?id=7', 'fa fa-circle-o', 70, MenuItems::STATUS_INACTIVE, time(), time()],
                [1, 25, 'Условия обслуживания', '/pages/view?id=8', 'fa fa-circle-o', 80, MenuItems::STATUS_INACTIVE, time(), time()],

                [1, 25, 'Жамият предмети ва мақсадлари', '/pages/view?id=14', 'fa fa-circle-o', -59, MenuItems::STATUS_INACTIVE, time(), time()],
                [1, 25, 'Бизнес План', '/post/view?id=30', 'fa fa-circle-o', -59, MenuItems::STATUS_INACTIVE, time(), time()],
                [1, 25, 'Отчёт о проделанной работе', '/post/view?id=31', 'fa fa-circle-o', -58, MenuItems::STATUS_INACTIVE, time(), time()],


                [1, 14, 'Акционерам', '#', 'fa fa-circle-o', -79, MenuItems::STATUS_ACTIVE, time(), time()], //45
                [1, 45, 'Существенные факты', '/post/view?id=3', 'fa fa-circle-o', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 45, 'Аффилированные лица', '/post/view?id=20', 'fa fa-circle-o', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 45, 'Проспекты эмиссии ценных бумаг и решение о выпуске', '/post/view?id=21', 'fa fa-circle-o', -15, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 45, 'Начисленные и выплаченные дивиденды', '/post/view?id=22', 'fa fa-circle-o', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 45, 'Финансовая отчетность', '/post/view?id=5', 'fa fa-circle-o', 4, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 45, 'Итоги голосования по принятым на общем собрании акционеров', '/post/view?id=26', 'fa fa-circle-o', 4, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 45, 'Новости для акционеров', '/post/view?id=27', 'fa fa-circle-o', 4, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 45, 'Корпоративное управление', '/post/view?id=28', 'fa fa-circle-o', 4, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 45, 'Информация о приобретении обществом акций', '/post/view?id=29', 'fa fa-circle-o', 4, MenuItems::STATUS_ACTIVE, time(), time()],

                [1, 45, 'Аудиторское заключение', '/post/view?id=32', 'fa fa-circle-o', 80, MenuItems::STATUS_INACTIVE, time(), time()],

                [1, 14, 'Пресс-центр', '#', 'fa fa-circle-o', -59, MenuItems::STATUS_ACTIVE, time(), time()], //56
                [1, 56, 'Новости', '/post/view?id=9', 'fa fa-circle-o', -1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 56, 'Мероприятия', '/post/view?id=10', 'fa fa-circle-o', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 56, 'Тендеры', '/post/view?id=19', 'fa fa-circle-o', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 56, 'Фотогалерея', '/post/view?id=11', 'fa fa-circle-o', 2, MenuItems::STATUS_ACTIVE, time(), time()], //Фотогалерея

                [1, 14, 'Библиотека', '#', 'fa fa-circle-o', -56, MenuItems::STATUS_ACTIVE, time(), time()], //61
                [1, 61, 'Законы', '/post/view?id=12', 'fa fa-circle-o', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 61, 'Кодексы', '/post/view?id=13', 'fa fa-circle-o', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 61, 'Указы и постановления', '/post/view?id=14', 'fa fa-circle-o', 3, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 61, 'Положения', '/post/view?id=15', 'fa fa-circle-o', 4, MenuItems::STATUS_ACTIVE, time(), time()],

                [1, 14, 'Наши офисы', '/post/view?id=18', 'fa fa-circle-o', -54, MenuItems::STATUS_ACTIVE, time(), time()], //66

            ]
        );

    }

    public function down()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey('{{%fk-post_categories-created_by}}', '{{%post_categories}}');
        // drops index for column `created_by`
        $this->dropIndex('{{%idx-post_categories-created_by}}', '{{%post_categories}}' );
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey('{{%fk-post_categories-updated_by}}', '{{%post_categories}}');
        // drops index for column `updated_by`
        $this->dropIndex('{{%idx-post_categories-updated_by}}', '{{%post_categories}}');
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey('{{%fk-post_categories-deleted_by}}','{{%post_categories}}');
        // drops index for column `deleted_by`
        $this->dropIndex('{{%idx-post_categories-deleted_by}}', '{{%post_categories}}');

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey('{{%fk-posts-created_by}}', '{{%posts}}');
        // drops index for column `created_by`
        $this->dropIndex('{{%idx-posts-created_by}}', '{{%posts}}' );
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey('{{%fk-posts-updated_by}}', '{{%posts}}');
        // drops index for column `updated_by`
        $this->dropIndex('{{%idx-posts-updated_by}}', '{{%posts}}');
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey('{{%fk-posts-deleted_by}}','{{%posts}}');
        // drops index for column `deleted_by`
        $this->dropIndex('{{%idx-posts-deleted_by}}', '{{%posts}}');

        $this->dropTable('posts');
        $this->dropTable('post_categories');
    }
}
