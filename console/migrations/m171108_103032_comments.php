<?php

use backend\models\menu\MenuItems;
use yii\db\Migration;

/**
 * Class m171108_103032_comments
 */
class m171108_103032_comments extends Migration
{
    public function up()
    {
        $tableOptions = ($this->db->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

        $this->createTable('comments', [
            'id' => $this->primaryKey(),
            'model' => $this->string()->null(),
            'model_id' => $this->integer()->null(),
            'author_id' => $this->integer()->null(),
            'parent_id' => $this->integer()->null(),
            'rating' => $this->float()->null(),
            'text' => $this->text()->notNull(),
            'likes' => $this->integer()->null()->defaultValue(0),
            'unlikes' => $this->integer()->null()->defaultValue(0),
            'sessions' => $this->text()->null(),
            'author_name' => $this->string()->null(),
            'author_position' => $this->string()->null(),
            'author_image' => $this->string()->null(),
            'weight' => $this->integer()->null()->defaultValue(0),
            'status' => $this->smallInteger()->null()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-comments', 'comments', ['model', 'model_id', 'author_id', 'parent_id']);

        $row_data = [
            ['Эстер Ховард', 'Директор в Spotify', '/themes/v1/images/user.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', 4, 0, 1, time(), time()],
            ['Роберт Фокс', 'Директор в Spotify', '/themes/v1/images/user.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', 5, 1, 1, time(), time()],
            ['Кэмерон Уильямсон', 'Директор в Spotify', '/themes/v1/images/user.jpg', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', 4, 2, 1, time(), time()],
        ];
        $this->batchInsert('comments',
            ['author_name', 'author_position', 'author_image', 'text', 'rating', 'weight', 'status', 'created_at', 'updated_at'],
            $row_data
        );

        $this->batchInsert(MenuItems::tableName(),
            ['menu_id', 'parent_id', 'label', 'url', 'class', 'icon', 'description', 'weight', 'status', 'created_at', 'updated_at'],
            [
                [1, 18, 'Comments', '/site/comments', 'fa fa-comments-o', '', '', 1, MenuItems::STATUS_ACTIVE, time(), time()],
            ]
        );

    }

    public function down()
    {
        $this->dropTable('comments');
    }
}
