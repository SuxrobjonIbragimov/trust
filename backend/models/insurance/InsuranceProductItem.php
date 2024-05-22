<?php

namespace backend\models\insurance;

use backend\behaviors\TranslateDatabaseBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%insurance_product_item}}".
 *
 * @property int $id
 * @property int|null $insurance_product_id
 * @property string|null $title
 * @property string|null $type
 * @property string|null $description
 * @property int|null $parent_id
 * @property string|null $image
 * @property string|null $icon
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property InsuranceProduct $insuranceProduct
 * @property InsuranceProductItem $parent
 * @property InsuranceProductItem[] $insuranceProductItems
 */
class InsuranceProductItem extends \yii\db\ActiveRecord
{
    const TYPE_WHAT_INCLUDED = 'what_included';
    const TYPE_WHAT_TO_DO = 'what_to_do';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%insurance_product_item}}';
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
                'translateAttributes' => ['title', 'description', ],
                'tableName' => static::tableName(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['insurance_product_id', 'parent_id', 'weight', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['insurance_product_id', 'parent_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title', 'type'], 'required'],
            [['title', 'type', 'image', 'icon'], 'string', 'max' => 255],
            [['insurance_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceProduct::className(), 'targetAttribute' => ['insurance_product_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceProductItem::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'insurance_product_id' => Yii::t('model', 'Insurance Product'),
            'title' => Yii::t('model', 'Title'),
            'type' => Yii::t('model', 'Type'),
            'description' => Yii::t('model', 'Description'),
            'parent_id' => Yii::t('model', 'Parent ID'),
            'image' => Yii::t('model', 'Image'),
            'icon' => Yii::t('model', 'Icon'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[InsuranceProduct]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceProduct()
    {
        return $this->hasOne(InsuranceProduct::className(), ['id' => 'insurance_product_id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(InsuranceProductItem::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[InsuranceProductItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceProductItems()
    {
        return $this->hasMany(InsuranceProductItem::className(), ['parent_id' => 'id'])->orderBy(['weight' => SORT_ASC]);
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

    /**
     * @param $type
     * @return array|mixed
     */
    public static function _getTypeList($type= null)
    {
        $array = [
            self::TYPE_WHAT_INCLUDED => Yii::t('app','Что входит в страховой случай'),
            self::TYPE_WHAT_TO_DO => Yii::t('app','Что делать при страховом случае?'),
        ];

        return $type === null ? $array : $array[$type];
    }


    /**
     * Status Name
     * @return string
     */
    public function _getTypeName()
    {
        $array = [
            self::TYPE_WHAT_INCLUDED => '<span class="">' . self::_getTypeList(self::TYPE_WHAT_INCLUDED) . '</span>',
            self::TYPE_WHAT_TO_DO => '<span class="">' . self::_getTypeList(self::TYPE_WHAT_TO_DO) . '</span>',
        ];

        return isset($array[$this->type]) ? $array[$this->type] : '';
    }
}
