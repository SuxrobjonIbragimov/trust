<?php

namespace backend\models\sliders;

use backend\behaviors\TranslateDatabaseBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "slider_items".
 *
 * @property integer $id
 * @property integer $slider_id
 * @property string $title
 * @property string $subtitle
 * @property string $image
 * @property string $link
 * @property string $description
 * @property integer $weight
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Sliders $slider
 */
class SliderItems extends ActiveRecord
{
    const LIMIT_SLIDER_ITEMS = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slider_items';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => TranslateDatabaseBehavior::className(),
                'translateAttributes' => ['title', ],
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
            [['slider_id', 'title', 'image'], 'required'],
            [['slider_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title', 'subtitle', 'image', 'link'], 'string', 'max' => 255],
            [['weight'], 'default', 'value' => 0],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['slider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sliders::className(), 'targetAttribute' => ['slider_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'slider_id' => Yii::t('model', 'Slider ID'),
            'title' => Yii::t('model', 'Title'),
            'image' => Yii::t('model', 'Image'),
            'link' => Yii::t('model', 'Link'),
            'description' => Yii::t('model', 'Description'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSlider()
    {
        return $this->hasOne(Sliders::className(), ['id' => 'slider_id']);
    }

    /**
     * Status
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

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
