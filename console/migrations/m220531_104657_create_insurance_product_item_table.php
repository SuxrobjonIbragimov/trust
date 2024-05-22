<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%insurance_product_item}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%insurance_product}}`
 * - `{{%insurance_product_item}}`
 */
class m220531_104657_create_insurance_product_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%insurance_product_item}}', [
            'id' => $this->primaryKey(),
            'insurance_product_id' => $this->integer()->null(),
            'title' => $this->string(),
            'type' => $this->string(),
            'description' => $this->text(),
            'parent_id' => $this->integer()->null(),
            'image' => $this->string(),
            'icon' => $this->string(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `insurance_product_id`
        $this->createIndex(
            '{{%idx-insurance_product_item-insurance_product_id}}',
            '{{%insurance_product_item}}',
            'insurance_product_id'
        );

        // add foreign key for table `{{%insurance_product}}`
        $this->addForeignKey(
            '{{%fk-insurance_product_item-insurance_product_id}}',
            '{{%insurance_product_item}}',
            'insurance_product_id',
            '{{%insurance_product}}',
            'id',
            'CASCADE'
        );

        // creates index for column `parent_id`
        $this->createIndex(
            '{{%idx-insurance_product_item-parent_id}}',
            '{{%insurance_product_item}}',
            'parent_id'
        );

        // add foreign key for table `{{%insurance_product_item}}`
        $this->addForeignKey(
            '{{%fk-insurance_product_item-parent_id}}',
            '{{%insurance_product_item}}',
            'parent_id',
            '{{%insurance_product_item}}',
            'id',
            'CASCADE'
        );

        $row_data = [
            [1, 'ДТП, угон или полная гибель ТС', 'what_included', null,  0, 1, time(), time()],
            [1, 'Стихийные бедствия', 'what_included', null,  1, 1, time(), time()],
            [1, 'Удар предметом', 'what_included', null,  2, 1, time(), time()],
            [1, 'Пожар, взрыв', 'what_included', null,  3, 1, time(), time()],
            [1, 'ДСАГО', 'what_included', null,  4, 1, time(), time()],

            [1, 'Немедленно оповестите нас по короткому номеру 1234', 'what_to_do', null,  0, 1, time(), time()],
            [1, 'Заявите о событии в компетентные органы (УБДД, ОПО, ОВД и т.п.) в порядке, установленном законодательством РУз', 'what_to_do', null,  1, 1, time(), time()],
            [1, 'Не производить никаких перемещений поврежденного ТС', 'what_to_do', null,  2, 1, time(), time()],
            [1, 'Предъявите нам поврежденное Застрахованное ТС к осмотру до начала проведения восстановительных работ', 'what_to_do', null,  3, 1, time(), time()],
            [1, 'Направьте нам заявление о выплате страхового возмещения, к которому должны быть приложены следующие документы', 'what_to_do', null,  4, 1, time(), time()],

            [1, 'страховой полис или его копия', 'what_to_do', 10,  0, 1, time(), time()],
            [1, 'материалы (постановление, обвинительное заключение, судебное решение или другие)', 'what_to_do', 10,  1, 1, time(), time()],
            [1, 'копию свидетельства о регистрации застрахованного ТС (технический паспорт)', 'what_to_do', 10,  2, 1, time(), time()],
            [1, 'копии паспорта или иного документа, удостоверяющего личность водителя', 'what_to_do', 10,  3, 1, time(), time()],
            [1, 'отчет на оценку материального ущерба', 'what_to_do', 10,  4, 1, time(), time()],

            [2, 'При пожаре или взрыве', 'what_included', null,  0, 1, time(), time()],
            [2, 'При ударе молнии и всех видов стихийных бедствий', 'what_included', null,  1, 1, time(), time()],
            [2, 'При аварии и воздействия жидкости', 'what_included', null,  2, 1, time(), time()],
            [2, 'При причинении вреда третьими лицами', 'what_included', null,  3, 1, time(), time()],

            [2, 'Позвоните на короткий номер компании 1234 и следуйте указаниям наших операторов.', 'what_to_do', null,  0, 1, time(), time()],
            [2, 'При пожаре вызовите сотрудников противопожарной службы.', 'what_to_do', null,  1, 1, time(), time()],
            [2, 'При затоплении соседей вызовите уполномоченные органы для устранения случившегося события.', 'what_to_do', null,  2, 1, time(), time()],
            [2, 'Примите меры по предотвращению или уменьшению ущерба', 'what_to_do', null,  3, 1, time(), time()],

            [3, 'Телесные повреждения', 'what_included', null,  0, 1, time(), time()],
            [3, 'Нападение злоумышленников или животных', 'what_included', null,  1, 1, time(), time()],
            [3, 'Потеря трудоспособности или временная нетрудоспособность', 'what_included', null,  2, 1, time(), time()],
            [3, 'Установление инвалидности I или II группы', 'what_included', null,  3, 1, time(), time()],
            [3, 'Смерть в результате несчастного случая', 'what_included', null,  4, 1, time(), time()],

            [3, 'Позвоните на короткий номер компании 1234.', 'what_to_do', null,  0, 1, time(), time()],
            [3, 'Сообщите свои данные операторам.', 'what_to_do', null,  1, 1, time(), time()],
            [3, 'Заявите о страховом случае или заполните онлайн на сайте.', 'what_to_do', null,  2, 1, time(), time()],
            [3, 'Предоставьте все необходимые документы.', 'what_to_do', null,  3, 1, time(), time()],

            [4, 'Травматические повреждения, полученные во время участия в спортивных соревнованиях', 'what_included', null,  0, 1, time(), time()],
            [4, 'Инвалидность', 'what_included', null,  1, 1, time(), time()],
            [4, 'Смерть', 'what_included', null,  2, 1, time(), time()],

            [4, 'Позвоните на короткий номер компании 1234.', 'what_to_do', null,  0, 1, time(), time()],
            [4, 'Сообщите свои данные операторам.', 'what_to_do', null,  1, 1, time(), time()],
            [4, 'Заявите о страховом случае или заполните онлайн на сайте.', 'what_to_do', null,  2, 1, time(), time()],
            [4, 'Предоставьте все необходимые документы.', 'what_to_do', null,  3, 1, time(), time()],
        ];

        $this->batchInsert('insurance_product_item',
            ['insurance_product_id', 'title', 'type', 'parent_id', 'weight', 'status', 'created_at', 'updated_at'],
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
            '{{%fk-insurance_product_item-insurance_product_id}}',
            '{{%insurance_product_item}}'
        );

        // drops index for column `insurance_product_id`
        $this->dropIndex(
            '{{%idx-insurance_product_item-insurance_product_id}}',
            '{{%insurance_product_item}}'
        );

        // drops foreign key for table `{{%insurance_product_item}}`
        $this->dropForeignKey(
            '{{%fk-insurance_product_item-parent_id}}',
            '{{%insurance_product_item}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            '{{%idx-insurance_product_item-parent_id}}',
            '{{%insurance_product_item}}'
        );

        $this->dropTable('{{%insurance_product_item}}');
    }
}
