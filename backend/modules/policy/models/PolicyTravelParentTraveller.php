<?php

namespace backend\modules\policy\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%policy_travel_traveller}}".
 *
 * @property int $id
 * @property int $policy_travel_id
 * @property string $first_name
 * @property string $surname
 * @property string $birthday
 * @property string $pass_sery
 * @property string $pass_num
 * @property string $pinfl
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property int $_traveller_parent_birthday
 * @property int $isFamily
 *
 * @property User $createdBy
 * @property PolicyTravel $policyTravel
 * @property User $updatedBy
 */
class PolicyTravelParentTraveller extends ActiveRecord
{
    const SCENARIO_SITE_STEP_CALC = 'site_step_calc';
    const SCENARIO_SITE_STEP_FORM = 'site_step_form';

    public $isFamily;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel_traveller}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $maxBirthdayDateParent = ($this->isNewRecord) ? date('d.m.Y',strtotime("-18 year", time())) : date('d.m.Y', strtotime($this->birthday));
        return [
            [['birthday', 'first_name', 'surname', 'pass_sery', 'pass_num',], 'required', 'on' => self::SCENARIO_SITE_STEP_CALC, 'message' => Yii::t('policy', 'Необходимо заполнить')],
            [['birthday', ], 'date', 'format' => 'dd.MM.yyyy', 'max' => $maxBirthdayDateParent, 'tooBig' => Yii::t('policy', 'The traveller must be 18 years ago')],
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
            'first_name' => Yii::t('policy', 'First Name'),
            'surname' => Yii::t('policy', 'Surname'),
            'birthday' => Yii::t('policy', 'Birthday'),
            'pass_sery' => Yii::t('policy', 'Pass Sery'),
            'pass_num' => Yii::t('policy', 'Pass Num'),
            'pinfl' => Yii::t('policy', 'Pinfl'),
            'phone' => Yii::t('policy', 'Phone'),
            'email' => Yii::t('policy', 'Email'),
            'address' => Yii::t('policy', 'Address'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),
        ];
    }

}
