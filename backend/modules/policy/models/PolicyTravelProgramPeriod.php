<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%policy_travel_program_period}}".
 *
 * @property int $id
 * @property int|null $policy_travel_program_id
 * @property int|null $day_min
 * @property int|null $day_max
 * @property float|null $value
 * @property bool|null $is_fixed
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property PolicyTravelProgram $policyTravelProgram
 */
class PolicyTravelProgramPeriod extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_program_period}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['policy_travel_program_id', 'day_min', 'day_max', 'weight', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['policy_travel_program_id', 'day_min', 'day_max', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'number'],
            [['is_fixed'], 'boolean'],
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
            'day_min' => Yii::t('policy', 'Day Min'),
            'day_max' => Yii::t('policy', 'Day Max'),
            'value' => Yii::t('policy', 'Value'),
            'is_fixed' => Yii::t('policy', 'Is Fixed'),
            'weight' => Yii::t('policy', 'Weight'),
            'status' => Yii::t('policy', 'Status'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),
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
}
