<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%policy_travel_program_to_country}}".
 *
 * @property int $id
 * @property int|null $policy_travel_program_id
 * @property int|null $country_id
 * @property int|null $created_at
 *
 * @property HandbookCountry $country
 * @property PolicyTravelProgram $policyTravelProgram
 */
class PolicyTravelProgramToCountry extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_program_to_country}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'timestamp' => [
//                TimestampBehavior::className(),
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', false],
//                    ActiveRecord::EVENT_BEFORE_UPDATE => ['created_at', false],
//                ],
//            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['policy_travel_program_id', 'country_id', 'created_at'], 'default', 'value' => null],
            [['policy_travel_program_id', 'country_id', 'created_at'], 'integer'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => HandbookCountry::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['policy_travel_program_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelProgram::className(), 'targetAttribute' => ['policy_travel_program_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy', 'ID'),
            'policy_travel_program_id' => Yii::t('policy', 'Policy Travel Program ID'),
            'country_id' => Yii::t('policy', 'Country ID'),
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
     * Gets query for [[PolicyTravelProgram]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelProgram()
    {
        return $this->hasOne(PolicyTravelProgram::className(), ['id' => 'policy_travel_program_id']);
    }
}
