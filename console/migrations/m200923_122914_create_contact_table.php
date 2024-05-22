<?php

use backend\models\menu\MenuItems;
use yii\db\Migration;

/**
 * Handles the creation of table `{{%contact}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 * - `{{%user}}`
 */
class m200923_122914_create_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contact}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string()->null(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'policy_series' => $this->string(),
            'policy_number' => $this->string(),
            'policy_issue_date' => $this->date(),
            'subject' => $this->text()->null(),
            'message' => $this->text()->null(),
            'user_id' => $this->integer()->null(),
            'type' => $this->integer()->defaultValue(0),
            'weight' => $this->integer()->defaultValue(1),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
            'deleted_by' => $this->integer()->null(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'deleted_at' => $this->dateTime(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-contact-user_id}}',
            '{{%contact}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-contact-user_id}}',
            '{{%contact}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-contact-created_by}}',
            '{{%contact}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-contact-created_by}}',
            '{{%contact}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        // creates index for column `updated_by`
        $this->createIndex(
            '{{%idx-contact-updated_by}}',
            '{{%contact}}',
            'updated_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-contact-updated_by}}',
            '{{%contact}}',
            'updated_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        // creates index for column `deleted_by`
        $this->createIndex(
            '{{%idx-contact-deleted_by}}',
            '{{%contact}}',
            'deleted_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-contact-deleted_by}}',
            '{{%contact}}',
            'deleted_by',
            '{{%user}}',
            'id',
            'SET NULL'
        );

        $this->batchInsert(MenuItems::tableName(),
            ['menu_id', 'parent_id', 'label', 'url', 'class', 'icon', 'description', 'weight', 'status', 'created_at', 'updated_at'],
            [
                [1, 18, 'Feedbacks', '/site/feedback', 'fa fa-envelope-o', '', '', -50, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 18, 'Страховой случай', '/site/claims', 'fa fa-codepen', '', '', -20, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 18, 'Contacts', '/site/contacts', 'fa fa-envelope-o', '', '', -10, MenuItems::STATUS_ACTIVE, time(), time()],
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-contact-user_id}}',
            '{{%contact}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-contact-user_id}}',
            '{{%contact}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-contact-created_by}}',
            '{{%contact}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-contact-created_by}}',
            '{{%contact}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-contact-updated_by}}',
            '{{%contact}}'
        );

        // drops index for column `updated_by`
        $this->dropIndex(
            '{{%idx-contact-updated_by}}',
            '{{%contact}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-contact-deleted_by}}',
            '{{%contact}}'
        );

        // drops index for column `deleted_by`
        $this->dropIndex(
            '{{%idx-contact-deleted_by}}',
            '{{%contact}}'
        );

        $this->dropTable('{{%contact}}');
    }
}
