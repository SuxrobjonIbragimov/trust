<?php

use yii\db\Migration;
use yii\rbac\DbManager;
use yii\base\InvalidConfigException;

/**
 * Initializes RBAC tables
 */
class m170320_062434_rbac extends Migration
{

    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }

    /**
     * @return bool
     */
    protected function isMSSQL()
    {
        return $this->db->driverName === 'mssql' || $this->db->driverName === 'sqlsrv' || $this->db->driverName === 'dblib';
    }

    /**
     * @inheritdoc
     */
    public function up()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($authManager->ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->createTable($authManager->itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY (name)',
            'FOREIGN KEY (rule_name) REFERENCES ' . $authManager->ruleTable . ' (name)' .
            ($this->isMSSQL() ? '' : ' ON DELETE SET NULL ON UPDATE CASCADE'),
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', $authManager->itemTable, 'type');

        $this->createTable($authManager->itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY (parent, child)',
            'FOREIGN KEY (parent) REFERENCES ' . $authManager->itemTable . ' (name)' .
            ($this->isMSSQL() ? '' : ' ON DELETE CASCADE ON UPDATE CASCADE'),
            'FOREIGN KEY (child) REFERENCES ' . $authManager->itemTable . ' (name)' .
            ($this->isMSSQL() ? '' : ' ON DELETE CASCADE ON UPDATE CASCADE'),
        ], $tableOptions);

        $this->createTable($authManager->assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY (item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES ' . $authManager->itemTable . ' (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ], $tableOptions);

        if ($this->isMSSQL()) {
            $this->execute("CREATE TRIGGER dbo.trigger_auth_item_child
            ON dbo.{$authManager->itemTable}
            INSTEAD OF DELETE, UPDATE
            AS
            DECLARE @old_name VARCHAR (64) = (SELECT name FROM deleted)
            DECLARE @new_name VARCHAR (64) = (SELECT name FROM inserted)
            BEGIN
            IF COLUMNS_UPDATED() > 0
                BEGIN
                    IF @old_name <> @new_name
                    BEGIN
                        ALTER TABLE {$authManager->itemChildTable} NOCHECK CONSTRAINT FK__auth_item__child;
                        UPDATE {$authManager->itemChildTable} SET child = @new_name WHERE child = @old_name;
                    END
                UPDATE {$authManager->itemTable}
                SET name = (SELECT name FROM inserted),
                type = (SELECT type FROM inserted),
                description = (SELECT description FROM inserted),
                rule_name = (SELECT rule_name FROM inserted),
                data = (SELECT data FROM inserted),
                created_at = (SELECT created_at FROM inserted),
                updated_at = (SELECT updated_at FROM inserted)
                WHERE name IN (SELECT name FROM deleted)
                IF @old_name <> @new_name
                    BEGIN
                        ALTER TABLE {$authManager->itemChildTable} CHECK CONSTRAINT FK__auth_item__child;
                    END
                END
                ELSE
                    BEGIN
                        DELETE FROM dbo.{$authManager->itemChildTable} WHERE parent IN (SELECT name FROM deleted) OR child IN (SELECT name FROM deleted);
                        DELETE FROM dbo.{$authManager->itemTable} WHERE name IN (SELECT name FROM deleted);
                    END
            END;");
        }

        $this->batchInsert($authManager->itemTable,
            ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'],
            [
                ['/*', 2, NULL, NULL, NULL, time(), time()],
                ['/admin/user/change-password', 2, NULL, NULL, NULL, time(), time()],
                ['/admin/user/profile', 2, NULL, NULL, NULL, time(), time()],
                ['/site/index', 2, NULL, NULL, NULL, time(), time()],
                ['/site/error', 2, NULL, NULL, NULL, time(), time()],
                ['/site/logout', 2, NULL, NULL, NULL, time(), time()],
                ['/admin/user/*', 2, NULL, NULL, NULL, time(), time()],
                ['/site/comments/*', 2, NULL, NULL, NULL, time(), time()],
                ['/site/files/*', 2, NULL, NULL, NULL, time(), time()],
                ['/pages/*', 2, NULL, NULL, NULL, time(), time()],
                ['/post/*', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=1', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=2', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=3', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=4', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=5', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=6', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=7', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=8', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=9', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=10', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=11', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=12', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=13', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=14', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=15', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=16', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=17', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=18', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=19', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=20', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=21', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=22', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=23', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=24', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=25', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=26', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=27', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=28', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=29', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=30', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=31', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view?id=32', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view/*', 2, NULL, NULL, NULL, time(), time()],
                ['/post/create-item', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view-item', 2, NULL, NULL, NULL, time(), time()],
                ['/post/update-item', 2, NULL, NULL, NULL, time(), time()],
                ['/post/delete-item', 2, NULL, NULL, NULL, time(), time()],
                ['/post/create-item/*', 2, NULL, NULL, NULL, time(), time()],
                ['/post/view-item/*', 2, NULL, NULL, NULL, time(), time()],
                ['/post/update-item/*', 2, NULL, NULL, NULL, time(), time()],
                ['/post/delete-item/*', 2, NULL, NULL, NULL, time(), time()],
                ['/post/upload-image', 2, NULL, NULL, NULL, time(), time()],
                ['/post/delete-image', 2, NULL, NULL, NULL, time(), time()],
                ['/sliders/*', 2, NULL, NULL, NULL, time(), time()],
                ['/site/*', 2, NULL, NULL, NULL, time(), time()],
                ['/elfinder/*', 2, NULL, NULL, NULL, time(), time()],
                ['/insurance-product/*', 2, NULL, NULL, NULL, time(), time()],

                ['/translatemanager/*', 2, NULL, NULL, NULL, time(), time()],
                ['/translatemanager/language/*', 2, NULL, NULL, NULL, time(), time()],
                ['/translatemanager/language/list', 2, NULL, NULL, NULL, time(), time()],
                ['/translatemanager/language/translate', 2, NULL, NULL, NULL, time(), time()],
                ['/translatemanager/language/translate?language_id=uz-UZ', 2, NULL, NULL, NULL, time(), time()],
                ['/translatemanager/language/translate?language_id=ru-RU', 2, NULL, NULL, NULL, time(), time()],
                ['/translatemanager/language/translate?language_id=en-US', 2, NULL, NULL, NULL, time(), time()],
                ['/translatemanager/language/save', 2, NULL, NULL, NULL, time(), time()],
                ['/translatemanager/language/optimizer', 2, NULL, NULL, NULL, time(), time()],

                ['accessTranslate', 2, 'Permission translate', NULL, NULL, time(), time()],
                ['accessAdministrator', 2, 'Permission Administrator', NULL, NULL, time(), time()],
                ['accessAdmin', 2, 'Permission Admin', NULL, NULL, time(), time()],
                ['accessModerator', 2, 'Permission Moderator', NULL, NULL, time(), time()],
                ['accessDashboard', 2, 'Permission Dashboard', NULL, NULL, time(), time()],

                ['translatemanager', 1, 'Role translatemanager', NULL, NULL, time(), time()],
                ['administrator', 1, 'Role Administrator', NULL, NULL, time(), time()],
                ['admin', 1, 'Role Admin', NULL, NULL, time(), time()],
                ['moderator', 1, 'Role Moderator', NULL, NULL, time(), time()],
            ]
        );

        $this->batchInsert($authManager->itemChildTable,
            ['parent', 'child'],
            [
                ['accessAdministrator', '/*'],

                ['accessAdmin', '/admin/user/*'],
                ['accessAdmin', '/site/comments/*'],
                ['accessAdmin', '/site/files/*'],
                ['accessAdmin', '/post/*'],
                ['accessAdmin', '/pages/*'],
                ['accessAdmin', '/sliders/*'],
                ['accessAdmin', '/elfinder/*'],

                ['accessModerator', '/site/comments/*'],
                ['accessModerator', '/site/files/*'],
                ['accessModerator', '/post/view/*'],
                ['accessModerator', '/post/create-item/*'],
                ['accessModerator', '/post/update-item/*'],
                ['accessModerator', '/post/view-item/*'],
                ['accessModerator', '/post/delete-item/*'],
                ['accessModerator', '/post/view'],
                ['accessModerator', '/post/view?id=1'],
                ['accessModerator', '/post/view?id=2'],
                ['accessModerator', '/post/view?id=3'],
                ['accessModerator', '/post/view?id=4'],
                ['accessModerator', '/post/view?id=5'],
                ['accessModerator', '/post/view?id=6'],
                ['accessModerator', '/post/view?id=7'],
                ['accessModerator', '/post/view?id=8'],
                ['accessModerator', '/post/view?id=9'],
                ['accessModerator', '/post/view?id=10'],
                ['accessModerator', '/post/view?id=11'],
                ['accessModerator', '/post/view?id=12'],
                ['accessModerator', '/post/view?id=13'],
                ['accessModerator', '/post/view?id=14'],
                ['accessModerator', '/post/view?id=15'],
                ['accessModerator', '/post/view?id=16'],
                ['accessModerator', '/post/view?id=17'],
                ['accessModerator', '/post/view?id=18'],
                ['accessModerator', '/post/view?id=19'],
                ['accessModerator', '/post/view?id=20'],
                ['accessModerator', '/post/view?id=21'],
                ['accessModerator', '/post/view?id=22'],
                ['accessModerator', '/post/view?id=23'],
                ['accessModerator', '/post/view?id=24'],
                ['accessModerator', '/post/view?id=25'],
                ['accessModerator', '/post/view?id=26'],
                ['accessModerator', '/post/view?id=27'],
                ['accessModerator', '/post/view?id=28'],
                ['accessModerator', '/post/view?id=29'],
                ['accessModerator', '/post/view?id=30'],
                ['accessModerator', '/post/view?id=31'],
                ['accessModerator', '/post/view?id=32'],
                ['accessModerator', '/post/create-item'],
                ['accessModerator', '/post/update-item'],
                ['accessModerator', '/post/view-item'],
                ['accessModerator', '/post/delete-item'],
                ['accessModerator', '/post/upload-image'],
                ['accessModerator', '/post/delete-image'],
                ['accessModerator', '/pages/*'],
                ['accessModerator', '/sliders/*'],
                ['accessModerator', '/elfinder/*'],
                ['accessModerator', '/insurance-product/*'],

                ['accessDashboard', '/admin/user/change-password'],
                ['accessDashboard', '/admin/user/profile'],
                ['accessDashboard', '/site/index'],
                ['accessDashboard', '/site/error'],
                ['accessDashboard', '/site/logout'],

                ['accessTranslate', '/translatemanager/language/translate'],
                ['accessTranslate', '/translatemanager/language/translate?language_id=uz-UZ'],
                ['accessTranslate', '/translatemanager/language/translate?language_id=ru-RU'],
                ['accessTranslate', '/translatemanager/language/translate?language_id=en-US'],
                ['accessTranslate', '/translatemanager/language/save'],

                ['translatemanager', 'accessDashboard'],
                ['translatemanager', 'accessTranslate'],

                ['accessModerator', 'accessDashboard'],
                ['accessAdmin', 'translatemanager'],
                ['accessAdmin', 'accessDashboard'],
                ['accessAdmin', 'accessModerator'],
                ['administrator', 'accessAdministrator'],
                ['administrator', 'accessDashboard'],
                ['admin', 'accessAdmin'],
                ['moderator', 'accessModerator'],
            ]
        );

        $this->insert($authManager->assignmentTable, [
            'item_name' => 'administrator',
            'user_id' => '1',
            'created_at' => time()
        ]);
        $this->insert($authManager->assignmentTable, [
            'item_name' => 'admin',
            'user_id' => '2',
            'created_at' => time()
        ]);
        $this->insert($authManager->assignmentTable, [
            'item_name' => 'moderator',
            'user_id' => '3',
            'created_at' => time()
        ]);
        $this->insert($authManager->assignmentTable, [
            'item_name' => 'translatemanager',
            'user_id' => '4',
            'created_at' => time()
        ]);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;

        if ($this->isMSSQL()) {
            $this->execute('DROP TRIGGER dbo.trigger_auth_item_child;');
        }

        $this->dropTable($authManager->assignmentTable);
        $this->dropTable($authManager->itemChildTable);
        $this->dropTable($authManager->itemTable);
        $this->dropTable($authManager->ruleTable);
    }
}
