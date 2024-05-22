<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%policy_travel_multi_days}}".
 *
 * @property int $id
 * @property string|null $name_ru
 * @property string|null $name_uz
 * @property string|null $name_en
 * @property int|null $ins_id
 * @property int|null $days
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property PolicyTravel[] $policyTravels
 */
class PolicyTravelMultiDays extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_multi_days}}';
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
            [['ins_id', 'days', 'weight', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['ins_id', 'days', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name_ru', 'name_uz', 'name_en'], 'string', 'max' => 255],
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
            'ins_id' => Yii::t('policy', 'Ins ID'),
            'days' => Yii::t('policy', 'Days'),
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
        return $this->hasMany(PolicyTravel::className(), ['multi_days_id' => 'id']);
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
