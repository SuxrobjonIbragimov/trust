<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%policy_travel_purpose}}".
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int|null $parent_id
 * @property int|null $ins_id
 * @property float|null $rate
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property PolicyTravel[] $policyTravels
 * @property PolicyTravelPurpose $parent
 * @property PolicyTravelPurpose[] $policyTravelPurposes
 */
class PolicyTravelPurpose extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const PURPOSE_TRAVEL = 1;
    const PURPOSE_EDUCATION = 2;
    const PURPOSE_WORK = 3;
    const PURPOSE_SPORT = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_purpose}}';
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
            [['rate'], 'number'],
            [['name_ru', 'name_uz', 'name_en'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelPurpose::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'rate' => Yii::t('policy', 'Rate'),
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
        return $this->hasMany(PolicyTravel::className(), ['purpose_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(PolicyTravelPurpose::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[PolicyTravelPurposes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelPurposes()
    {
        return $this->hasMany(PolicyTravelPurpose::className(), ['parent_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function _getItemsList()
    {
        $name_field = 'name_'._lang();
        return ArrayHelper::map(
            self::find()
                ->where(['status' =>self::STATUS_ACTIVE])
                ->orderBy(['weight' => SORT_ASC, 'id' => SORT_ASC])
                ->all(), 'id' ,"{$name_field}"
        );
    }
}
