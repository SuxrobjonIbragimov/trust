<?php

use backend\models\menu\MenuItems;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%social_network}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m200923_122927_create_social_network_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%social_network}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'html_item_class' => $this->string(),
            'html_class' => $this->string(),
            'image' => $this->string(),
            'weight' => $this->integer()->defaultValue(0),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'deleted_by' => $this->integer()->null(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'deleted_at' => $this->dateTime(),
        ]);

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-social_network-created_by}}',
            '{{%social_network}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-social_network-created_by}}',
            '{{%social_network}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-social_network-updated_by}}',
            '{{%social_network}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-social_network-updated_by}}',
            '{{%social_network}}',
            'updated_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        // creates index for column `deleted_by`
        $this->createIndex(
            '{{%idx-social_network-deleted_by}}',
            '{{%social_network}}',
            'deleted_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-social_network-deleted_by}}',
            '{{%social_network}}',
            'deleted_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );


        $rows = [
            [
                'name' => 'Facebook',
                'url' => 'https://www.facebook.com/',
                'html_class' => 'bx bxl-facebook-circle bx-md',
                'image' => '/themes/v1/images/svg/social/fb.svg',
                'html_item_class' => 'd-flex align-items-center',
                'status' => 1,
            ],
            [
                'name' => 'Instagram',
                'url' => 'https://www.instagram.com/',
                'html_class' => 'bx bxl-instagram-alt bx-md',
                'image' => '/themes/v1/images/svg/social/insta.svg',
                'html_item_class' => 'd-flex align-items-center',
                'status' => 1,
            ],
            [
                'name' => 'Telegram',
                'url' => 'https://t.me/',
                'html_class' => 'bx bxl-telegram bx-md',
                'image' => '/themes/v1/images/svg/social/telegram.svg',
                'html_item_class' => 'd-flex align-items-center',
                'status' => 1,
            ],
            [
                'name' => 'Twitter',
                'url' => 'https://www.twitter.com/',
                'html_class' => 'fab fa-google-plus',
                'image' => '/themes/v1/images/svg/social/tw.svg',
                'html_item_class' => 'tw',
                'status' => 0,
            ],
            [
                'name' => 'Youtube',
                'url' => 'https://www.youtube.com/',
                'html_class' => 'fab fa-youtube',
                'image' => '',
                'html_item_class' => 'you',
                'status' => 0,
            ],
        ];

        $db = Yii::$app->db;
        $data_model = [];
        $date = _date_current();
        $fields = [];
        $static_fields = [
            'weight',
            'created_at',
            'updated_at',
        ];
        foreach ($rows as $key => $row) {
            foreach ($row as $field => $item) {
                if (is_array($item)) {
                    $data_model[$key][$field] = ($item['ru']);
                } else {
                    $data_model[$key][$field] = !empty($item) ? ($item) : null;
                }
                if (!in_array($field,$fields)) {
                    $fields[] = $field;
                };
            }
            $data_model[$key]['weight'] = ($key+1);
            $data_model[$key]['created_at'] = $date;
            $data_model[$key]['updated_at'] = $date;

        }

        $fields = array_merge($fields,$static_fields);

        $this->batchInsert('{{%social_network}}', $fields, $data_model);


        $this->insert(MenuItems::tableName(), [
//            'id' => 47,
            'menu_id' => 1,
            'parent_id' => 14,
            'label' => 'Social Networks',
            'url' => '/social-network',
            'class' => 'fa fa-facebook',
            'icon' => '',
            'description' => '',
            'weight' => 30,
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-social_network-created_by}}',
            '{{%social_network}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-social_network-created_by}}',
            '{{%social_network}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-social_network-updated_by}}',
            '{{%social_network}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-social_network-updated_by}}',
            '{{%social_network}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-social_network-deleted_by}}',
            '{{%social_network}}'
        );

        // drops index for column `deleted_by`
        $this->dropIndex(
            '{{%idx-social_network-deleted_by}}',
            '{{%social_network}}'
        );

        $this->dropTable('{{%social_network}}');

        $this->delete(MenuItems::tableName(), ['id' => 69]);
    }
}
