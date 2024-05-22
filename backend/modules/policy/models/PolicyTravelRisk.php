<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%policy_travel_risk}}".
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property string|null $key
 * @property int|null $parent_id
 * @property int|null $ins_id
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property PolicyTravelProgramToRisk[] $policyTravelProgramToRisks
 * @property PolicyTravelRisk $parent
 * @property PolicyTravelRisk[] $policyTravelRisks
 */
class PolicyTravelRisk extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_risk}}';
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
            [['name_ru', 'name_uz', 'name_en', 'key'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelRisk::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'parent_id' => Yii::t('policy', 'Parent ID'),
            'ins_id' => Yii::t('policy', 'Ins ID'),
            'key' => Yii::t('policy', 'Key'),
            'weight' => Yii::t('policy', 'Weight'),
            'status' => Yii::t('policy', 'Status'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[PolicyTravelProgramToRisks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelProgramToRisks()
    {
        return $this->hasMany(PolicyTravelProgramToRisk::className(), ['policy_travel_risk_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(PolicyTravelRisk::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[PolicyTravelRisks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelRisks()
    {
        return $this->hasMany(PolicyTravelRisk::className(), ['parent_id' => 'id']);
    }
}
