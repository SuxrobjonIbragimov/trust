<?php

namespace common\library\weather\yandex_weather\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%weather_yandex}}`.
 */
class m201017_081927_create_weather_yandex_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%weather_yandex}}', [
            'id' => $this->primaryKey(),
            'temp' => $this->float(),
            'feels_like' => $this->float(),
            'location_name' => $this->string(),
            'language' => $this->string(),
            'lat' => $this->decimal(10,8),
            'lon' => $this->decimal(11,8),
            'icon' => $this->string(),
            'icon_swg' => $this->text(),
            'condition' => $this->string(),
            'wind_speed' => $this->float(),
            'wind_gust' => $this->float(),
            'wind_dir' => $this->string(),
            'now' => $this->integer(),
            'now_dt' => $this->dateTime(),
            'created_at' => $this->dateTime(),
        ]);

        // creates index for column `group_id`
        $this->createIndex(
            '{{%idx-weather_yandex-language}}',
            '{{%weather_yandex}}',
            'language'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%weather_yandex}}');
    }
}
