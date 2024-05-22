<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%policy_travel_program_to_risk}}".
 *
 * @property int $id
 * @property int|null $policy_travel_program_id
 * @property int|null $policy_travel_risk_id
 * @property float|null $value
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 *
 * @property PolicyTravelProgram $policyTravelProgram
 * @property PolicyTravelRisk $policyTravelRisk
 */
class PolicyTravelProgramToRisk extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

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
    public static function tableName()
    {
        return '{{%policy_travel_program_to_risk}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['policy_travel_program_id', 'policy_travel_risk_id', 'weight', 'status', 'created_at'], 'default', 'value' => null],
            [['policy_travel_program_id', 'policy_travel_risk_id', 'weight', 'status', 'created_at'], 'integer'],
            [['value'], 'number'],
            [['policy_travel_program_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelProgram::className(), 'targetAttribute' => ['policy_travel_program_id' => 'id']],
            [['policy_travel_risk_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelRisk::className(), 'targetAttribute' => ['policy_travel_risk_id' => 'id']],
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
            'policy_travel_risk_id' => Yii::t('policy', 'Policy Travel Risk ID'),
            'value' => Yii::t('policy', 'Value'),
            'weight' => Yii::t('policy', 'Weight'),
            'status' => Yii::t('policy', 'Status'),
            'created_at' => Yii::t('policy', 'Created At'),
        ];
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

    /**
     * Gets query for [[PolicyTravelRisk]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelRisk()
    {
        return $this->hasOne(PolicyTravelRisk::className(), ['id' => 'policy_travel_risk_id']);
    }
}
