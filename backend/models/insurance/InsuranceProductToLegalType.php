<?php

namespace backend\models\insurance;

use backend\modules\handbook\models\HandbookLegalType;
use Yii;

/**
 * This is the model class for table "{{%insurance_product_to_legal_type}}".
 *
 * @property int $product_id
 * @property int $legal_type_id
 *
 * @property HandbookLegalType $legalType
 * @property InsuranceProduct $product
 */
class InsuranceProductToLegalType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%insurance_product_to_legal_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'legal_type_id'], 'required'],
            [['product_id', 'legal_type_id'], 'default', 'value' => null],
            [['product_id', 'legal_type_id'], 'integer'],
            [['product_id', 'legal_type_id'], 'unique', 'targetAttribute' => ['product_id', 'legal_type_id']],
            [['legal_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => HandbookLegalType::className(), 'targetAttribute' => ['legal_type_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => InsuranceProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('model', 'Product ID'),
            'legal_type_id' => Yii::t('model', 'Legal Type ID'),
        ];
    }

    /**
     * Gets query for [[LegalType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLegalType()
    {
        return $this->hasOne(HandbookLegalType::className(), ['id' => 'legal_type_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(InsuranceProduct::className(), ['id' => 'product_id']);
    }
}
