<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%insurance_product}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%insurance_product}}`
 */
class m220527_025658_create_insurance_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%insurance_product}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'subtitle' => $this->string(),
            'slug' => $this->string(),
            'summary' => $this->text(),
            'description' => $this->text(),
            'parent_id' => $this->integer()->null(),
            'image' => $this->string(),
            'icon' => $this->string(),
            'calc_link' => $this->string(),
            'is_main' => $this->smallInteger()->null(),
            'meta_title' => $this->string(),
            'meta_keywords' => $this->string(),
            'meta_description' => $this->text(),
            'views' => $this->integer()->defaultValue(0),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-insurance_product-parent_id}}',
            '{{%insurance_product}}',
            'parent_id'
        );

        // add foreign key for table `{{%insurance_product}}`
        $this->addForeignKey(
            '{{%fk-insurance_product-parent_id}}',
            '{{%insurance_product}}',
            'parent_id',
            '{{%insurance_product}}',
            'id',
            'SET NULL'
        );

        $this->createTable('insurance_product_to_legal_type', [
            'product_id' => $this->integer()->notNull(),
            'legal_type_id' => $this->integer()->notNull(),
        ], $tableOptions);


        $this->addPrimaryKey('pk-insurance_product_to_legal_type', 'insurance_product_to_legal_type', ['product_id', 'legal_type_id']);
        $this->createIndex('idx-insurance_product_to_legal_type-product_id', 'insurance_product_to_legal_type', 'product_id');
        $this->createIndex('idx-insurance_product_to_legal_type-legal_type_id', 'insurance_product_to_legal_type', 'legal_type_id');
        $this->addForeignKey('fk-insurance_product_to_legal_type-product_id', 'insurance_product_to_legal_type', 'product_id', 'insurance_product', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk-insurance_product_to_legal_type-legal_type_id', 'insurance_product_to_legal_type', 'legal_type_id', 'handbook_legal_type', 'id', 'CASCADE', 'RESTRICT');


        $row_data = [
            ['Страхование для бизнеса', 'strahovanie_dlya_biznesa', 1, 'Комплексная программа страхования для всесторонней защиты автомобиля', '<p>Защита Вашего автомобиля от непредсказуемых ситуаций не только на дороге, но и рядом с домом.</p>          <p>При этом в рамках программы страхования будут также включены риски повреждения автомобиля на стоянке, отскочившим предметом, наезда на препятствия, противоправных действий третьих лиц, стихийных бедствий и многое другое.</p>', '/themes/v1/images/insuranceType1.jpg', 'Каско', null,  0, 1, time(), time()],
            ['Страхование имущества', 'strahovanie_imushestva', 1, 'Комплексная программа страхования для всесторонней защиты автомобиля', '<p>Защита Вашего автомобиля от непредсказуемых ситуаций не только на дороге, но и рядом с домом.</p>          <p>При этом в рамках программы страхования будут также включены риски повреждения автомобиля на стоянке, отскочившим предметом, наезда на препятствия, противоправных действий третьих лиц, стихийных бедствий и многое другое.</p>', '/themes/v1/images/insuranceType2.jpg', 'Имущество', null, 1, 1, time(), time()],
            ['Страхование транспортных средств', 'strahovanie_transportnyh_sredstv', 1, 'Комплексная программа страхования для всесторонней защиты автомобиля', '<p>Защита Вашего автомобиля от непредсказуемых ситуаций не только на дороге, но и рядом с домом.</p>          <p>При этом в рамках программы страхования будут также включены риски повреждения автомобиля на стоянке, отскочившим предметом, наезда на препятствия, противоправных действий третьих лиц, стихийных бедствий и многое другое.</p>', '/themes/v1/images/insuranceType3.jpg', 'Имущество', '/policy/osgo/calculate', 1, 1, time(), time()],
            ['Добровольное медицинское страхование', 'dobrovolьnoe_medicinskoe_strahovanie', 1, 'Комплексная программа страхования для всесторонней защиты автомобиля', '<p>Защита Вашего автомобиля от непредсказуемых ситуаций не только на дороге, но и рядом с домом.</p>          <p>При этом в рамках программы страхования будут также включены риски повреждения автомобиля на стоянке, отскочившим предметом, наезда на препятствия, противоправных действий третьих лиц, стихийных бедствий и многое другое.</p>', '/themes/v1/images/insuranceType4.jpg', 'Имущество', null, 1, 1, time(), time()],
            ['Несчастных случаев', 'sport', 0, 'Комплексная программа страхования для всесторонней защиты автомобиля', '<p>Защита Вашего автомобиля от непредсказуемых ситуаций не только на дороге, но и рядом с домом.</p>          <p>При этом в рамках программы страхования будут также включены риски повреждения автомобиля на стоянке, отскочившим предметом, наезда на препятствия, противоправных действий третьих лиц, стихийных бедствий и многое другое.</p>', '/themes/v1/img/logos/icon_accidents.png', 'Несчастных случаев', null, 2, 1, time(), time()],
            ['Страхование жизни', 'health', 0, 'Комплексная программа страхования для всесторонней защиты автомобиля', '<p>Защита Вашего автомобиля от непредсказуемых ситуаций не только на дороге, но и рядом с домом.</p>          <p>При этом в рамках программы страхования будут также включены риски повреждения автомобиля на стоянке, отскочившим предметом, наезда на препятствия, противоправных действий третьих лиц, стихийных бедствий и многое другое.</p>', '/themes/v1/img/logos/icon_life_insurance.png', 'Страхование жизни', null, 3, 1, time(), time()],
            ['Страхование грузов', 'cargo', 0, 'Страхование грузов', '<p>Страхование грузов</p>          <p>При этом в рамках программы страхования будут также включены риски повреждения автомобиля на стоянке, отскочившим предметом, наезда на препятствия, противоправных действий третьих лиц, стихийных бедствий и многое другое.</p>', '/themes/v1/img/logos/icon_cargo insurance.png', 'Страхование грузов',null, 5, 1, time(), time()],
            ['Страхование при строительстве', 'cargo', 0, 'Страхование при строительстве', '<p>Страхование при строительстве</p>          <p>При этом в рамках программы страхования будут также включены риски повреждения автомобиля на стоянке, отскочившим предметом, наезда на препятствия, противоправных действий третьих лиц, стихийных бедствий и многое другое.</p>', '/themes/v1/img/logos/icon_construction_insurance.png', 'Страхование при строительстве', null, 5, 1, time(), time()],
        ];

        $this->batchInsert('insurance_product',
            ['title', 'slug', 'is_main', 'summary', 'description', 'image', 'meta_title', 'calc_link', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );

        $row_data = [
            [1,1],
            [1,2],
            [2,1],
            [2,2],
            [3,1],
            [3,2],
            [4,1],
            [4,2],
            [5,1],
            [5,2],
            [6,1],
            [6,2],
            [7,1],
            [7,2],
            [8,1],
            [8,2],
        ];
        $this->batchInsert('insurance_product_to_legal_type',
            ['product_id', 'legal_type_id'],
            $row_data
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%insurance_product}}`
        $this->dropForeignKey(
            '{{%fk-insurance_product-parent_id}}',
            '{{%insurance_product}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-insurance_product-parent_id}}',
            '{{%insurance_product}}'
        );

        $this->dropTable('{{%insurance_product}}');
    }
}
