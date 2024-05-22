<?php

namespace backend\modules\policy\models;

use Yii;

/**
 * This is the model class for table "{{%policy_travel_to_country}}".
 *
 * @property int $id
 * @property int|null $policy_travel_id
 * @property int|null $country_id
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 *
 * @property HandbookCountry $country
 * @property PolicyTravel $policyTravel
 */
class PolicyTravelToCountry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_to_country}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['policy_travel_id', 'country_id', 'weight', 'status', 'created_at'], 'default', 'value' => null],
            [['policy_travel_id', 'country_id', 'weight', 'status', 'created_at'], 'integer'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => HandbookCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['policy_travel_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravel::className(), 'targetAttribute' => ['policy_travel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy', 'ID'),
            'policy_travel_id' => Yii::t('policy', 'Policy Travel ID'),
            'country_id' => Yii::t('policy', 'Country ID'),
            'weight' => Yii::t('policy', 'Weight'),
            'status' => Yii::t('policy', 'Status'),
            'created_at' => Yii::t('policy', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(HandbookCountry::className(), ['id' => 'country_id']);
    }

    /**
     * Gets query for [[PolicyTravel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravel()
    {
        return $this->hasOne(PolicyTravel::className(), ['id' => 'policy_travel_id']);
    }
}
