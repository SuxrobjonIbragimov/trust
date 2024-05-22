<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%policy_travel_program}}".
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int|null $parent_id
 * @property int|null $ins_id
 * @property int|null $covid
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property int|null $totalRiskAmount
 *
 * @property PolicyTravel[] $policyTravels
 * @property PolicyTravelProgram $parent
 * @property PolicyTravelProgram[] $policyTravelPrograms
 * @property PolicyTravelProgramPeriod[] $policyTravelProgramPeriods
 * @property PolicyTravelProgramToCountry[] $policyTravelProgramToCountries
 * @property PolicyTravelProgramToRisk[] $policyTravelProgramToRisks
 */
class PolicyTravelProgram extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const PROGRAM_MINIMUM = 1;
    const PROGRAM_ECANOM = 2;
    const PROGRAM_OPTIMA = 3;
    const PROGRAM_STANDARD = 4;
    const PROGRAM_LUKS = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_program}}';
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
            [['parent_id', 'ins_id', 'weight', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['parent_id', 'ins_id', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_uz', 'name_en'], 'string', 'max' => 255],
            [['covid', ], 'safe'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelProgram::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy', 'ID'),
            'name_ru' => Yii::t('policy', 'Name Ru'),
            'name_uz' => Yii::t('policy', 'Name Uz'),
            'name_en' => Yii::t('policy', 'Name En'),
            'covid' => Yii::t('policy', 'Pokrivaet Covid?'),
            'parent_id' => Yii::t('policy', 'Parent ID'),
            'ins_id' => Yii::t('policy', 'Ins ID'),
            'weight' => Yii::t('policy', 'Weight'),
            'status' => Yii::t('policy', 'Status'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[PolicyTravels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravels()
    {
        return $this->hasMany(PolicyTravel::className(), ['program_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(PolicyTravelProgram::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[PolicyTravelPrograms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelPrograms()
    {
        return $this->hasMany(PolicyTravelProgram::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[PolicyTravelProgramPeriods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelProgramPeriods()
    {
        return $this->hasMany(PolicyTravelProgramPeriod::className(), ['policy_travel_program_id' => 'id']);
    }

    /**
     * Gets query for [[PolicyTravelProgramToCountries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelProgramToCountries()
    {
        return $this->hasMany(PolicyTravelProgramToCountry::className(), ['policy_travel_program_id' => 'id']);
    }

    /**
     * Gets query for [[PolicyTravelProgramToRisks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelProgramToRisks()
    {
        return $this->hasMany(PolicyTravelProgramToRisk::className(), ['policy_travel_program_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function _getItemsList($ids = null)
    {
        $name_field = 'name_'._lang();
        if (!empty($ids)) {
            $query = self::find()
                ->where(['id' => $ids])
                ->andWhere(['status' => self::STATUS_ACTIVE])
                ->orderBy(['weight' => SORT_ASC, 'id' => SORT_ASC])
                ->all();
        } else {
            $query = self::find()
                ->andWhere(['status' => self::STATUS_ACTIVE])
                ->orderBy(['weight' => SORT_ASC, 'id' => SORT_ASC])
                ->all();
        }

        return ArrayHelper::map(
            $query, 'id' ,"{$name_field}"
        );
    }

    public function getTotalRiskAmount()
    {
        $null = new Expression('NULL');
        return $this->getPolicyTravelProgramToRisks()
            ->innerJoin('policy_travel_risk', 'policy_travel_program_to_risk.policy_travel_risk_id = policy_travel_risk.id')
            ->andWhere(['is', 'policy_travel_risk.parent_id', $null])
            ->sum('policy_travel_program_to_risk.value');
    }
}
