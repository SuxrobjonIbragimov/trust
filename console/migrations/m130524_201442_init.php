<?php

use common\models\User;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'verification_token' => $this->string()->defaultValue(null),
            'access_token' => $this->string()->defaultValue(null),
            'expired_at' => $this->integer()->null(),
            'email' => $this->string()->null(),
            'phone' => $this->string()->null(),
            'first_name' => $this->string(64)->null(),
            'last_name' => $this->string(128)->null(),
            'middle_name' => $this->string(64)->null(),
            'image' => $this->string()->null(),
            'info' => $this->text()->null(),
            'address' => $this->text()->null(),
            'github' => $this->string(),
            'google' => $this->string(),
            'facebook' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(User::STATUS_ACTIVE),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ], $tableOptions);

        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');

        $this->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => 'DgXQG8ZhOJSVEwFHe0U8UEgf7lwAJLXO',
            'password_hash' => '$2y$13$s2mgyb9SjyJkxT0VbSW4ve.xvcRbdNu9U5Btt.Jpw1snqEOLoHpum',
            'password_reset_token' => null,
            'email' => 'shohdevuz@gmail.com',
            'first_name' => 'Shohrux',
            'last_name' => 'Haqberdiyev',
            'image' => '/uploads/users/default.png',
            'info' => null,
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%user}}', [
            'username' => 'administrator',
            'auth_key' => '9GM-RtTo7n9-9pJIExVRKcBi3W9IweY',
            'password_hash' => '$2y$13$D1G10cc2ZbFX/hZnDKK8nOwrHQyGRKTTaDfJDDgwY1qAKzX7yeIKu',
            'password_reset_token' => null,
            'email' => 'administrator@gmail.com',
            'first_name' => 'Administrator',
            'last_name' => 'Administratorov',
            'image' => '/uploads/users/default.png',
            'info' => null,
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%user}}', [
            'username' => 'moderator',
            'auth_key' => '9GM-RtTo7n9-9pJIExVRKi3W9kIweYY',
            'password_hash' => '$2y$13$lPdluIsO1M0ntfHVa.OwGeMZM0mqvmDx2HRh5/OZ8otEqZkysz8sC',
            'password_reset_token' => null,
            'email' => 'moderator@gmail.com',
            'first_name' => 'Moderator',
            'last_name' => 'Moderator',
            'image' => '/uploads/users/default.png',
            'info' => null,
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert('{{%user}}', [
            'username' => 'translator',
            'auth_key' => '9GM-RtTo7n9-9pJIExVRKi3W9kTweTT',
            'password_hash' => '$2y$13$HpnXIt0jg9RgoVpBikZfJOlnnt792jZ7l8iWLodHyjqmgSHkmC4MG',
            'password_reset_token' => null,
            'email' => 'translator@gmail.com',
            'first_name' => 'Translator',
            'last_name' => 'Translatorov',
            'image' => '/uploads/users/default.png',
            'info' => null,
            'status' => User::STATUS_ACTIVE,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
