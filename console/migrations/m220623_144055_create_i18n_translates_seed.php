<?php

use yii\db\Migration;

/**
 * Class m220623_144055_create_i18n_translates_seed
 */
class m220623_144055_create_i18n_translates_seed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // INSERT TO language_source
        $this->batchInsert('{{%language_source}}',
            ['category', 'message'],
            [
                ['menu_items', 'О компании',],
                ['menu_items', 'О нас', ],
                ['menu_items', 'История компании', ],
                ['menu_items', 'Устав компании и внутренние положения', ],
                ['menu_items', 'Организационная структура', ],
                ['menu_items', 'Руководство и управление', ],
                ['menu_items', 'Существенные факты', ],
                ['menu_items', 'Аффилированные лица', ],
                ['menu_items', 'Проспекты эмиссии ценных бумаг и решение о выпуске', ],
                ['menu_items', 'Начисленные и выплаченные дивиденды', ],
                ['menu_items', 'Лицензии и сертификаты', ],
                ['menu_items', 'Финансовая отчетность', ],
                ['menu_items', 'Клиенты', ],
                ['menu_items', 'Партнеры', ],
                ['menu_items', 'Вакансии', ],

                ['menu_items', 'Услуги компании', ],
                ['menu_items', 'Физическим лицам', ],
                ['menu_items', 'Юридическим лицам', ],
                ['menu_items', 'Страховой случай', ],
                ['menu_items', 'Проверка полиса', ],

                ['menu_items', 'Пресс-центр', ],
                ['menu_items', 'Новости', ],
                ['menu_items', 'Мероприятия', ],
                ['menu_items', 'Тендеры', ],
                ['menu_items', 'Фото из жизни компании', ],

                ['menu_items', 'Библиотека', ],
                ['menu_items', 'Законы', ],
                ['menu_items', 'Кодексы', ],
                ['menu_items', 'Указы и постановления', ],
                ['menu_items', 'Положения', ],

                ['menu_items', 'Филиалы', ],
                ['menu_items', 'Офисы', ],
                ['menu_items', 'Пункты продаж', ],

                ['menu_items', 'Контакты', ],

                ['menu_items', 'Сведения о коллегиальных и совещательных органах', ],
                ['menu_items', 'Стратегия развития', ],
                ['menu_items', 'Нормативные документы', ],

                ['menu_items', 'Акционерам', ],
                ['menu_items', 'Итоги голосования по принятым на общем собрании акционеров', ],
                ['menu_items', 'Новости для акционеров', ],
                ['menu_items', 'Корпоративное управление', ],
                ['menu_items', 'Информация о приобретении обществом акций', ],


                ['menu_items', 'Жамият предмети ва мақсадлари', ],
                ['menu_items', 'Бизнес План', ],
                ['menu_items', 'Отчёт о проделанной работе', ],

                ['menu_items', 'Аудиторское заключение', ],

                ['menu_items', 'Проверка статуса заявления', ],

                ['frontend', 'Onlayn so’rovnoma', ],
                ['frontend', 'Vote', ],
                ['frontend', 'So’rovda qatnashganingiz uchun rahmat!', ],

                ['frontend', 'Murojaatlar statistikasi', ],


                ['frontend', 'New', ],
                ['frontend', 'In progress', ],
                ['frontend', 'Done', ],
                ['frontend', 'Canceled', ],
            ]
        );

        // INSERT TO language_translate
        $this->batchInsert('{{%language_translate}}',
            ['id', 'language', 'translation'],
            [
                [1, 'ru-RU', 'О компании',],
                [1, 'uz-UZ', 'Kompaniya haqida',],
                [1, 'en-US', 'About company',],

                [2, 'ru-RU', 'О нас', ],
                [2, 'uz-UZ', 'Biz haqimizda', ],
                [2, 'en-US', 'About Us', ],

                [3, 'ru-RU', 'История компании', ],
                [3, 'uz-UZ', 'Kompaniya tarixi', ],
                [3, 'en-US', 'History of the company', ],

                [4, 'ru-RU', 'Устав компании и внутренние положения', ],
                [4, 'uz-UZ', 'Kompaniyaning ustavi va ichki qoidalari', ],
                [4, 'en-US', 'Company charter and internal regulations', ],

                [5, 'ru-RU', 'Организационная структура', ],
                [5, 'uz-UZ', 'Tashkiliy tuzilma', ],
                [5, 'en-US', 'Organizational structure', ],

                [6, 'ru-RU', 'Руководство и управление', ],
                [6, 'uz-UZ', 'Rahbariyat', ],
                [6, 'en-US', 'Leadership and management', ],

                [7, 'ru-RU', 'Существенные факты', ],
                [7, 'uz-UZ', 'Muhim faktlar', ],
                [7, 'en-US', 'Material facts', ],

                [8, 'ru-RU', 'Аффилированные лица', ],
                [8, 'uz-UZ', 'Affillangan shaxslar', ],
                [8, 'en-US', 'Affiliated Person', ],

                [9, 'ru-RU', 'Проспекты эмиссии ценных бумаг и решение о выпуске', ],
                [9, 'uz-UZ', 'Qimmatli qog\'ozlarni chiqarish prospekti va chiqarish to\'g\'risidagi qarorlar', ],
                [9, 'en-US', 'Prospectus for the issue of securities and the decision to issue', ],

                [10, 'ru-RU', 'Начисленные и выплаченные дивиденды', ],
                [10, 'uz-UZ', 'Hisoblangan va to\'langan dividendlar', ],
                [10, 'en-US', 'Accrued and paid dividends', ],

                [11, 'ru-RU', 'Лицензии и сертификаты', ],
                [11, 'uz-UZ', 'Litsenziyalar va sertifikatlar', ],
                [11, 'en-US', 'Licenses and certificates', ],

                [12, 'ru-RU', 'Финансовая отчетность', ],
                [12, 'uz-UZ', 'Moliyaviy hisobotlar', ],
                [12, 'en-US', 'Financial statements', ],

                [13, 'ru-RU', 'Клиенты', ],
                [13, 'uz-UZ', 'Mijozlar', ],
                [13, 'en-US', 'Clients', ],

                [14, 'ru-RU', 'Партнеры', ],
                [14, 'uz-UZ', 'Hamkorlar', ],
                [14, 'en-US', 'Partners', ],

                [15, 'ru-RU', 'Вакансии', ],
                [15, 'uz-UZ', 'Bo\'sh ish o\'rinlari', ],
                [15, 'en-US', 'Vacancy', ],

                [16, 'ru-RU', 'Услуги компании', ],
                [16, 'uz-UZ', 'Kompaniya xizmatlari', ],
                [16, 'en-US', 'Company Services', ],

                [17, 'ru-RU', 'Физическим лицам', ],
                [17, 'uz-UZ', 'Jismoniy shaxslar', ],
                [17, 'en-US', 'To Individuals', ],

                [18, 'ru-RU', 'Юридическим лицам', ],
                [18, 'uz-UZ', 'Korporativ mijozlar', ],
                [18, 'en-US', 'To legal entities', ],

                [19, 'ru-RU', 'Страховой случай', ],
                [19, 'uz-UZ', 'Sug\'urta hodisasi', ],
                [19, 'en-US', 'Insurance case', ],

                [20, 'ru-RU', 'Проверка полиса', ],
                [20, 'uz-UZ', 'Polisni tekshirish', ],
                [20, 'en-US', 'Check policy', ],

                [21, 'ru-RU', 'Пресс-центр', ],
                [21, 'uz-UZ', 'Matbuot markazi', ],
                [21, 'en-US', 'Press center', ],

                [22, 'ru-RU', 'Новости', ],
                [22, 'uz-UZ', 'Yangiliklar', ],
                [22, 'en-US', 'News', ],

                [23, 'ru-RU', 'Мероприятия', ],
                [23, 'uz-UZ', 'Tadbirlar', ],
                [23, 'en-US', 'Events', ],

                [24, 'ru-RU', 'Тендеры', ],
                [24, 'uz-UZ', 'Tenderlar', ],
                [24, 'en-US', 'Tenders', ],

                [25, 'ru-RU', 'Фото из жизни компании', ],
                [25, 'uz-UZ', 'Foto galereya', ],
                [25, 'en-US', 'Photo gallery', ],

                [26, 'ru-RU', 'Библиотека', ],
                [26, 'uz-UZ', 'Kutubxona', ],
                [26, 'en-US', 'Library', ],

                [27, 'ru-RU', 'Законы', ],
                [27, 'uz-UZ', 'Qonunlar', ],
                [27, 'en-US', 'Laws', ],

                [28, 'ru-RU', 'Кодексы', ],
                [28, 'uz-UZ', 'Kodekslar', ],
                [28, 'en-US', 'Кодексы', ],

                [29, 'ru-RU', 'Указы и постановления', ],
                [29, 'uz-UZ', 'Farmonlar va qarorlar', ],
                [29, 'en-US', 'Decrees and resolutions', ],

                [30, 'ru-RU', 'Положения', ],
                [30, 'uz-UZ', 'Qoidalar', ],
                [30, 'en-US', 'Regulations', ],

                [31, 'ru-RU', 'Филиалы', ],
                [31, 'uz-UZ', 'Filiallar', ],
                [31, 'en-US', 'Branches', ],

                [32, 'ru-RU', 'Офисы', ],
                [32, 'uz-UZ', 'Ofislar', ],
                [32, 'en-US', 'Offices', ],

                [33, 'ru-RU', 'Пункты продаж', ],
                [33, 'uz-UZ', 'Savdo nuqtalari', ],
                [33, 'en-US', 'Sales points', ],

                [34, 'ru-RU', 'Контакты', ],
                [34, 'uz-UZ', 'Bog\'lanish', ],
                [34, 'en-US', 'Contacts', ],

                [35, 'ru-RU', 'Сведения о коллегиальных и совещательных органах', ],
                [35, 'uz-UZ', 'Kollegial va maslaxat organlar', ],
                [35, 'en-US', 'Information on collegial and advisory authorities', ],

                [36, 'ru-RU', 'Стратегия развития', ],
                [36, 'uz-UZ', 'Strategik rivojlanish', ],
                [36, 'en-US', 'Strategic development plan', ],

                [37, 'ru-RU', 'Нормативные документы', ],
                [37, 'uz-UZ', 'Normativ hujjatlar', ],
                [37, 'en-US', 'Regulatory documents', ],

                [38, 'ru-RU', 'Акционерам', ],
                [38, 'uz-UZ', 'Aksionerlarga', ],
                [38, 'en-US', 'Shareholders', ],

                [39, 'ru-RU', 'Итоги голосования по принятым на общем собрании акционеров', ],
                [39, 'uz-UZ', 'Aksiyadorlarning umumiy yig`ilishida ovoz berish natijalari', ],
                [39, 'en-US', 'Voting results adopted at the general meeting of shareholders', ],

                [40, 'ru-RU', 'Новости для акционеров', ],
                [40, 'uz-UZ', 'Aksiyadorlar uchun yangiliklar', ],
                [40, 'en-US', 'News for stockholders', ],

                [41, 'ru-RU', 'Корпоративное управление', ],
                [41, 'uz-UZ', 'Korporativ boshqaruv', ],
                [41, 'en-US', 'Corporate governance', ],

                [42, 'ru-RU', 'Информация о приобретении обществом акций', ],
                [42, 'uz-UZ', 'Aksiyalar sotib olinishi to`g`risida ma`lumot', ],
                [42, 'en-US', 'Information on the acquisition of shares', ],


                [43, 'ru-RU', 'Предмет и задачи общества', ],
                [43, 'uz-UZ', 'Jamiyat predmeti va maqsadlari', ],
                [43, 'en-US', 'The subject and tasks of society', ],

                [44, 'ru-RU', 'Бизнес План', ],
                [44, 'uz-UZ', 'Biznes reja', ],
                [44, 'en-US', 'Business plan', ],

                [45, 'ru-RU', 'Отчёт о проделанной работе', ],
                [45, 'uz-UZ', 'Ishning borishi haqida hisobot', ],
                [45, 'en-US', 'Progress report', ],

                [46, 'ru-RU', 'Аудиторское заключение', ],
                [46, 'uz-UZ', 'Auditorlik xulosasi', ],
                [46, 'en-US', 'Audit report', ],

                [47, 'ru-RU', 'Проверка статуса заявления', ],
                [47, 'uz-UZ', 'Murojaat xolatini tekshirish', ],
                [47, 'en-US', 'Checking the status of the application', ],


                [48, 'ru-RU', 'Онлайн-опрос', ],
                [48, 'uz-UZ', 'Onlayn so’rovnoma', ],
                [48, 'en-US', 'Online survey', ],

                [49, 'ru-RU', 'Голосовать', ],
                [49, 'uz-UZ', 'Ovoz berish', ],
                [49, 'en-US', 'Vote', ],

                [50, 'ru-RU', 'Спасибо за участие в опросе!', ],
                [50, 'uz-UZ', 'So’rovda qatnashganingiz uchun rahmat!', ],
                [50, 'en-US', 'Thank you for your participation in the survey!', ],

                [51, 'ru-RU', 'Статистика обращений', ],
                [51, 'uz-UZ', 'Murojaatlar statistikasi', ],
                [51, 'en-US', 'Statistics of appeals', ],

                [52, 'ru-RU', 'Новый', ],
                [52, 'uz-UZ', 'Yangi', ],
                [52, 'en-US', 'New', ],

                [53, 'ru-RU', 'В процессе сейчас', ],
                [53, 'uz-UZ', 'Ko\'rib chiqilmoqda', ],
                [53, 'en-US', 'In progress', ],

                [54, 'ru-RU', 'Завершенный', ],
                [54, 'uz-UZ', 'Bajarildi', ],
                [54, 'en-US', 'Done', ],

                [55, 'ru-RU', 'Отменено', ],
                [55, 'uz-UZ', 'Bekor qilingan', ],
                [55, 'en-US', 'Canceled', ],

            ]
        );

        $sql = file_get_contents(__DIR__ . '/seeder/language_source.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();

        if ($this->db->driverName == 'pgsql') {
            $sqlSequence = "SELECT setval('public.language_source_id_seq', 1685, true);";
            $commandSequence = Yii::$app->db->createCommand($sqlSequence);
            $commandSequence->execute();
        }

        $sql = file_get_contents(__DIR__ . '/seeder/language_translate.sql');
        $command = Yii::$app->db->createCommand($sql);
        $command->execute();


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%language_source}}', ['category' => 'menu_items']);
    }

}
