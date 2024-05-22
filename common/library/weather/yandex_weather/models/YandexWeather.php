<?php

namespace common\library\weather\yandex_weather\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%weather_yandex}}".
 *
 * @property int $id
 * @property float|null $temp
 * @property float|null $feels_like
 * @property string|null $location_name
 * @property string|null $language
 * @property float|null $lat
 * @property float|null $lon
 * @property string|null $icon
 * @property string|null $icon_swg
 * @property string|null $condition
 * @property float|null $wind_speed
 * @property float|null $wind_gust
 * @property string|null $wind_dir
 * @property int|null $now
 * @property string|null $now_dt
 * @property string|null $created_at
 */
class YandexWeather extends ActiveRecord
{
    /** Transaction expiration time in milliseconds. 43 200 000 ms = 12 hours. */
    const TIMEOUT = 3600000;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                // if you're using datetime instead of UNIX timestamp:
                'value' => _date_current(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => false,
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%weather_yandex}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['temp', 'feels_like', 'lat', 'lon', 'wind_speed', 'wind_gust'], 'number'],
            [['icon_swg'], 'string'],
            [['now'], 'integer'],
            [['now_dt', 'created_at'], 'safe'],
            [['location_name', 'language', 'icon', 'condition', 'wind_dir'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'temp' => Yii::t('model', 'Temp'),
            'feels_like' => Yii::t('model', 'Feels Like'),
            'location_name' => Yii::t('model', 'Location Name'),
            'language' => Yii::t('model', 'Language'),
            'lat' => Yii::t('model', 'Lat'),
            'lon' => Yii::t('model', 'Lon'),
            'icon' => Yii::t('model', 'Icon'),
            'icon_swg' => Yii::t('model', 'Icon Swg'),
            'condition' => Yii::t('model', 'Condition'),
            'wind_speed' => Yii::t('model', 'Wind Speed'),
            'wind_gust' => Yii::t('model', 'Wind Gust'),
            'wind_dir' => Yii::t('model', 'Wind Dir'),
            'now' => Yii::t('model', 'Now'),
            'now_dt' => Yii::t('model', 'Now Dt'),
            'created_at' => Yii::t('model', 'Created At'),
        ];
    }

    /**
     * Determines whether current transaction is expired or not.
     * @return bool true - if current instance of the transaction is expired, false - otherwise.
     */
    public function isExpired()
    {
        // todo: Implement model expiration check
        // for example, if transaction is active and passed TIMEOUT milliseconds after its creation, then it is expired
        return abs(Format::datetime2timestamp($this->created_at) - Format::timestamp(true)) > self::TIMEOUT;
    }

}
