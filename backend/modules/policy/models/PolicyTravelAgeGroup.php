<?php

namespace backend\modules\policy\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%policy_travel_age_group}}".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $age_min
 * @property int|null $age_max
 * @property float|null $rate
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class PolicyTravelAgeGroup extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_age_group}}';
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
            [['age_min', 'age_max', 'status', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['age_min', 'age_max', 'status', 'created_at', 'updated_at'], 'integer'],
            [['rate'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy', 'ID'),
            'name' => Yii::t('policy', 'Name'),
            'age_min' => Yii::t('policy', 'Age Min'),
            'age_max' => Yii::t('policy', 'Age Max'),
            'rate' => Yii::t('policy', 'Rate'),
            'status' => Yii::t('policy', 'Status'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),
        ];
    }
}
