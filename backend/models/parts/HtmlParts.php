<?php

namespace backend\models\parts;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use backend\behaviors\TranslateDatabaseBehavior;

/**
 * This is the model class for table "html_parts".
 *
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $body
 * @property string $summary
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class HtmlParts extends ActiveRecord
{
    const HOME_ABOUT_US_PART = 'home_about_us_part';
    const FOOTER_LOGO_BOTTOM_TEXT = 'footer_logo_bottom_text';
    const KEY_MAIN_LOCATION = 'main_location';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%html_parts}}';
    }

    /**
     * Status
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => TranslateDatabaseBehavior::className(),
                'translateAttributes' => ['name', 'body'],
                'tableName' => static::tableName(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'name'], 'required'],
            [['body'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['key'], 'string', 'min' => 3, 'max' => 32],
            [['name'], 'string', 'min' => 3, 'max' => 64],
            [['key'], 'unique'],
            [['key'], 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u'],
            [['status'], 'default', 'value' => self::STATUS_INACTIVE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'key' => Yii::t('model', 'Key'),
            'name' => Yii::t('model', 'Name'),
            'body' => Yii::t('model', 'Body'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getStatusArray($status = null)
    {
        $array = [
            self::STATUS_INACTIVE => Yii::t('model', 'Inactive'),
            self::STATUS_ACTIVE => Yii::t('model', 'Active'),
        ];

        return $status === null ? $array : $array[$status];
    }

    /**
     * Status Name
     * @return string
     */
    public function getStatusName()
    {
        $array = [
            self::STATUS_ACTIVE => '<span class="text-bold text-green">' . self::getStatusArray(self::STATUS_ACTIVE) . '</span>',
            self::STATUS_INACTIVE => '<span class="text-bold text-red">' . self::getStatusArray(self::STATUS_INACTIVE) . '</span>',
        ];

        return isset($array[$this->status]) ? $array[$this->status] : '';
    }


    /**
     * @param $keyword
     * @return HtmlParts|false|mixed
     */
    public static function getItemByKey($keyword)
    {
        $cache_db = !empty(Yii::$app->params['cache']['db.htmlParl']) ? Yii::$app->params['cache']['db.htmlParl'] : 0;
        if ($cache_db) {
            $cache = Yii::$app->cache;
            $key = 'htmlParl_'.$keyword.'_'._lang();
            $data = $cache->get($key);

            $dependency = new \yii\caching\FileDependency(['fileName' => 'lang.txt']);
            if ($data === false) {
                $model = self::findOne(['key' => $keyword]);
                $data = !empty($model) ? $model : false;
                $cache->set($key, $model, $cache_db, $dependency);
            }
        } else {
            $model = self::findOne(['key' => $keyword]);
            $data = !empty($model) ? $model : false;
        }
        return $data;
    }
}
