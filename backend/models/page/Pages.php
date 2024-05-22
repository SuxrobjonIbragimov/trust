<?php

namespace backend\models\page;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use backend\behaviors\MetaTitleBehavior;
use backend\behaviors\TranslateDatabaseBehavior;

/**
 * This is the model class for table "pages".
 *
 * @property integer $id
 * @property string $url
 * @property string $name
 * @property string $image
 * @property string $body
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Pages extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
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
            MetaTitleBehavior::className(),
            [
                'class' => TranslateDatabaseBehavior::className(),
                'translateAttributes' => ['name', 'body', 'meta_title', 'meta_keywords', 'meta_description'],
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
            [['url', 'name'], 'required'],
            [['body', 'meta_description', 'image'], 'string'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'meta_title'], 'string', 'min' => 3, 'max' => 64],
            [['meta_keywords'], 'string', 'max' => 255],
            [['url'], 'string', 'min' => 3, 'max' => 32],
            [['url'], 'unique'],
            [['url'], 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'url' => Yii::t('model', 'Url'),
            'name' => Yii::t('model', 'Name'),
            'image' => Yii::t('model', 'Image'),
            'body' => Yii::t('model', 'Body'),
            'meta_title' => Yii::t('model', 'Meta Title'),
            'meta_keywords' => Yii::t('model', 'Meta Keywords'),
            'meta_description' => Yii::t('model', 'Meta Description'),
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
}
