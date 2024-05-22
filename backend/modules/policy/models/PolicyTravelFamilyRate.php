<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%policy_travel_family_rate}}".
 *
 * @property int $id
 * @property int|null $member_min
 * @property int|null $member_max
 * @property float|null $rate
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PolicyTravelFamilyRate extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_family_rate}}';
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
            [['member_min', 'member_max', 'weight', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['member_min', 'member_max', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['rate'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy', 'ID'),
            'member_min' => Yii::t('policy', 'Member Min'),
            'member_max' => Yii::t('policy', 'Member Max'),
            'rate' => Yii::t('policy', 'Rate'),
            'weight' => Yii::t('policy', 'Weight'),
            'status' => Yii::t('policy', 'Status'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),
        ];
    }
}
