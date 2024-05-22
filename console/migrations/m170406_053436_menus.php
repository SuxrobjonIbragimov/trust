<?php

use yii\db\Migration;
use backend\models\menu\Menus;
use backend\models\menu\MenuItems;

class m170406_053436_menus extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(Menus::tableName(), [
            'id' => $this->primaryKey(),
            'key' => $this->string(32)->notNull()->unique(),
            'name' => $this->string(32)->notNull(),
            'description' => $this->text()->null(),
            'status' => $this->smallInteger()->notNull()->defaultValue(Menus::STATUS_INACTIVE),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable(MenuItems::tableName(), [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer()->null(),
            'label' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'class' => $this->string()->null(),
            'icon' => $this->string()->null(),
            'description' => $this->text()->null(),
            'weight' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(MenuItems::STATUS_INACTIVE),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('fk-menu_items-menu_id', MenuItems::tableName(), 'menu_id', Menus::tableName(), 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk-menu_items-parent_id', MenuItems::tableName(), 'parent_id', MenuItems::tableName(), 'id', 'CASCADE', 'NO ACTION');

        $this->batchInsert(Menus::tableName(),
            [ 'key', 'name', 'description', 'status', 'created_at', 'updated_at', ],
            [
                ['admin_menu', 'Admin menu', '', Menus::STATUS_ACTIVE, time(), time(),],
                ['front_header', 'Front header menu', '', Menus::STATUS_ACTIVE, time(), time(),],
                ['front_footer', 'Front footer menu', '', Menus::STATUS_ACTIVE, time(), time(),],
            ]
        );

        $this->batchInsert(MenuItems::tableName(),
            ['menu_id', 'parent_id', 'label', 'url', 'class', 'icon', 'description', 'weight', 'status', 'created_at', 'updated_at'],
            [
                [1, NULL, 'Tools', '#', 'fa fa-tasks', '', '', -1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 1, 'Gii', '/gii', 'fa fa-code', '', '', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 1, 'Debug', '/debug', 'fa fa-code-fork', '', '', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, NULL, 'Administrator', '#', 'fa fa-user-secret', '', '', 0, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 4, 'Users', '/admin/user', 'fa fa-users', '', '', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 4, 'Roles', '/admin/role', 'fa fa-check-square-o', '', '', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 4, 'Permissions', '/admin/permission', 'fa fa-key', '', '', 3, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 4, 'Routes', '/admin/route', 'fa fa-link', '', '', 4, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 4, 'Rules', '/admin/rule', 'fa fa-thumb-tack', '', '', 5, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, NULL, 'Translate manager', '#', 'fa fa-recycle', '', '', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 10, 'Languages', '/translatemanager/language/list', 'fa fa-language', '', '', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 10, 'Optimizer', '/translatemanager/language/optimizer', 'fa fa-spinner', '', '', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 10, 'Scan', '/translatemanager/language/scan', 'fa fa-search', '', '', 3, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, NULL, 'Contents', '#', 'fa fa-codepen', '', '', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 14, 'Files', '/site/files', 'fa fa-hdd-o', '', '', 2, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, NULL, 'Configuration', '#', 'fa fa-gears', '', '', 15, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 16, 'Menus', '/menu/index', 'fa fa-list', '', '', 1, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, NULL, 'Reviews', '#', 'fa fa-comment-o', '', '', 20, MenuItems::STATUS_ACTIVE, time(), time()],
                [1, 18, 'Users', '/user/users', 'fa fa-users', '', '', -1, MenuItems::STATUS_ACTIVE, time(), time()],
            ]
        );
    }

    public function down()
    {
        $this->dropTable(MenuItems::tableName());
        $this->dropTable(Menus::tableName());
    }
}
