<?php

namespace backend\modules\handbook\models;

use backend\models\insurance\InsuranceProduct;
use backend\models\insurance\InsuranceProductToLegalType;
use Yii;

/**
 * This is the model class for table "{{%handbook_legal_type}}".
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property string|null $description
 * @property string|null $image
 * @property string|null $icon
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property string|null $nameLocale
 *
 * @property InsuranceProductToLegalType[] $insuranceProductToLegalTypes
 * @property InsuranceProduct[] $products
 */
class HandbookLegalType extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;

    const LIMIT_ACTIVE_PRODUCTS = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%handbook_legal_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['weight', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_uz', 'name_en', 'image', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'name_ru' => Yii::t('model', 'Name Ru'),
            'name_uz' => Yii::t('model', 'Name Uz'),
            'name_en' => Yii::t('model', 'Name En'),
            'description' => Yii::t('model', 'Description'),
            'image' => Yii::t('model', 'Image'),
            'icon' => Yii::t('model', 'Icon'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * @return mixed|string|null
     */
    public function getNameLocale()
    {
        $name_field = 'name_'._lang();
        return !empty($this->$name_field) ? $this->$name_field : $this->name_ru;
    }

    /**
     * Gets query for [[InsuranceProductToLegalTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInsuranceProductToLegalTypes()
    {
        return $this->hasMany(InsuranceProductToLegalType::className(), ['legal_type_id' => 'id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(InsuranceProduct::className(), ['id' => 'product_id'])->viaTable('{{%insurance_product_to_legal_type}}', ['legal_type_id' => 'id']);
    }

    public static function _getItemsList()
    {
        $name_field = 'name_'._lang();
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->orderBy([
                'weight' => SORT_ASC,
                $name_field => SORT_ASC,
            ])
            ->all();
    }
}
