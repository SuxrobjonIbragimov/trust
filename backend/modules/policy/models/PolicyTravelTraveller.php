<?php

namespace backend\modules\policy\models;

use Yii;

/**
 * This is the model class for table "{{%policy_travel_traveller}}".
 *
 * @property int $id
 * @property int|null $policy_travel_id
 * @property string|null $first_name
 * @property string|null $surname
 * @property string|null $birthday
 * @property string|null $pass_sery
 * @property string|null $pass_num
 * @property string|null $pinfl
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property string $fullName
 * @property string $fullPassNumber
 *
 * @property int $_traveller_parent_birthday
 * @property int $isFamily
 *
 * @property PolicyTravel $policyTravel
 */
class PolicyTravelTraveller extends \yii\db\ActiveRecord
{
    const SCENARIO_SITE_STEP_CALC = 'site_step_calc';
    const SCENARIO_SITE_STEP_FORM = 'site_step_form';

    public $_traveller_parent_birthday;
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
        return [
            [['policy_travel_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['policy_travel_id', 'created_at', 'updated_at'], 'integer'],
            [['birthday', 'first_name', 'surname', 'pass_sery', 'pass_num',], 'required', 'on' => self::SCENARIO_SITE_STEP_CALC, 'message' => Yii::t('policy', 'Необходимо заполнить')],
            [['birthday'], 'safe'],
            [['first_name', 'surname', 'pass_sery', 'pass_num', 'pinfl', 'phone', 'email', 'address'], 'string', 'max' => 255],
            [['policy_travel_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravel::className(), 'targetAttribute' => ['policy_travel_id' => 'id']],
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

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->surname . ' ' . $this->first_name;
    }

    /**
     * @return string
     */
    public function getFullPassNumber()
    {
        $full_string = $this->pass_sery ?: null;
        $full_string .= (!empty($this->pass_num)) ? ' '.$this->pass_num : null;
        return $full_string;
    }

    /**
     * Gets query for [[PolicyTravel]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravel()
    {
        return $this->hasOne(PolicyTravel::className(), ['id' => 'policy_travel_id']);
    }
}
