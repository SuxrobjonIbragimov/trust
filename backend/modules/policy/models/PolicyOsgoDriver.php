<?php

namespace backend\modules\policy\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "{{%policy_osgo_driver}}".
 *
 * @property int $id
 * @property int $policy_osgo_id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $birthday
 * @property string $pass_sery
 * @property string $pass_num
 * @property string $pass_issued_by
 * @property string $pass_issue_date
 * @property string $pinfl
 * @property string $license_series
 * @property string $license_number
 * @property string $license_issue_date
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property int $relationship_id
 * @property int $resident_id
 * @property string $gender
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $residentName
 * @property string $fullName
 * @property string $fullPassNumber
 * @property string $fullLicenseNumber
 * @property int $driver_limit
 *
 * @property User $createdBy
 * @property PolicyOsgo $policyOsgo
 * @property User $updatedBy
 */
class PolicyOsgoDriver extends \yii\db\ActiveRecord
{
    const SCENARIO_SITE_STEP_CALC = 'site_step_calc';
    const SCENARIO_SITE_STEP_FORM = 'site_step_form';

    const DEFAULT_RESIDENT = 1;

    const MAX_DRIVERS_LIMIT = 5;

    public $driver_limit;

    public $_full_name;

    public function __construct($config = [])
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();

        if ($session->has('model_osgo_calc')) {
            $session_model = $session->get('model_osgo_calc');
            if (!empty($session_model)) {
                $attrs = json_decode($session_model);
                $attributes = (array)$attrs;
                if (!is_null($attributes['driver_limit_id'])) {
                    $this->driver_limit = $attributes['driver_limit_id'];
                }
            }
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_osgo_driver}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $resident_id_uz = self::RESIDENT_UZB;
        return [
            [['policy_osgo_id', 'relationship_id', 'resident_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['birthday', 'pass_issue_date', 'pass_expiration_date', 'license_issue_date', 'driver_limit', 'resident_id'], 'safe'],
            [['resident_id', 'birthday', 'pass_sery', 'pass_num', /*'first_name', 'last_name', 'middle_name', 'pinfl',*/ 'license_series', 'license_number', 'license_issue_date'], 'required', 'on' => self::SCENARIO_SITE_STEP_FORM, 'message' => Yii::t('policy','Необходимо заполнить')],

            [['relationship_id', ], 'required', 'message' => Yii::t('policy','Необходимо заполнить'),
                'on' => self::SCENARIO_SITE_STEP_FORM, 'when' => function ($model) {
                return ($model->driver_limit == PolicyOsgo::DRIVER_UNLIMITED);
            }, 'whenClient' => "function (attribute, value) {
                        return ($('#policyosgodriver-0-driver_limit').val() == 0);
                    }"
            ],
            [['pinfl', ], 'required', 'on' => self::SCENARIO_SITE_STEP_FORM, 'when' => function ($model) {
                return ($model->resident_id == self::RESIDENT_UZB);
            }, 'whenClient' => "function (attribute, value) {
                        let attr_id = attribute.id;
                        const attr_id_ar = attr_id.split('-');
                        let driver_id = (attr_id_ar[1]) ? attr_id_ar[1] : null;
                        return ($('#policyosgodriver-'+driver_id+'-resident_id').val() == {$resident_id_uz});
                    }"
            ],
            [['first_name', 'last_name', 'middle_name'], 'required', 'on' => self::SCENARIO_SITE_STEP_FORM, 'when' => function ($model) {
                return ($model->resident_id != self::RESIDENT_UZB);
            }, 'whenClient' => "function (attribute, value) {
                        let attr_id = attribute.id;
                        const attr_id_ar = attr_id.split('-');
                        let driver_id = (attr_id_ar[1]) ? attr_id_ar[1] : null;
                        console.log($('#policyosgodriver-'+driver_id+'-resident_id').val());
                        return ($('#policyosgodriver-'+driver_id+'-resident_id').val() != {$resident_id_uz});
                    }"
            ],
            [['resident_id', ], 'default', 'value' => self::RESIDENT_UZB],
            [['first_name', 'last_name', 'middle_name', 'pass_sery', 'pass_num', 'pass_issued_by', 'pinfl', 'license_series', 'license_number', 'phone', 'email', 'address', 'gender'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['policy_osgo_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyOsgo::className(), 'targetAttribute' => ['policy_osgo_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy', 'ID'),
            'policy_osgo_id' => Yii::t('policy', 'Policy Osgo'),
            'first_name' => Yii::t('policy', 'First Name'),
            'last_name' => Yii::t('policy', 'Last Name'),
            'middle_name' => Yii::t('policy', 'Middle Name'),
            'birthday' => Yii::t('policy', 'Birthday'),
            'pass_sery' => Yii::t('policy', 'Passport/ID sery'),
            'pass_num' => Yii::t('policy', 'Passport number'),
            'pass_issued_by' => Yii::t('policy', 'Pass/ID Issued By'),
            'pass_issue_date' => Yii::t('policy', 'Pass/ID Issue Date'),
            'pinfl' => Yii::t('policy', 'Pinfl'),
            'license_series' => Yii::t('policy', 'License series number'),
            'license_number' => Yii::t('policy', 'License Number'),
            'license_issue_date' => Yii::t('policy', 'License Issue Date'),
            'phone' => Yii::t('policy', 'Phone'),
            'email' => Yii::t('policy', 'Email'),
            'address' => Yii::t('policy', 'Address'),
            'relationship_id' => Yii::t('policy', 'Relationship'),
            'resident_id' => Yii::t('policy', 'Resident'),
            'gender' => Yii::t('policy', 'Gender'),
            'created_by' => Yii::t('policy', 'Created By'),
            'updated_by' => Yii::t('policy', 'Updated By'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),

            '_full_name' => Yii::t('policy', 'Фамилия и имя (Латинскими буквами)'),
        ];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->last_name . ' ' . $this->first_name .' ' . $this->middle_name;
    }

    /**
     * @return string
     */
    public function getFullLicenseNumber()
    {
        $full_string = $this->license_series ?: null;
        $full_string .= (!empty($this->license_number)) ? ' '.$this->license_number : null;
        return $full_string;
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
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyOsgo()
    {
        return $this->hasOne(PolicyOsgo::className(), ['id' => 'policy_osgo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    const RELATION_FATHER = 1;
    const RELATION_MOTHER = 2;
    const RELATION_HUSBAND = 3;
    const RELATION_WIFE = 4;
    const RELATION_SUN = 5;
    const RELATION_DAUGHTER = 6;
    const RELATION_BROTHER = 7;
    const RELATION_LITTLE_BROTHER = 8;
    const RELATION_SISTER = 9;
    const RELATION_LITTLE_SISTER = 10;

    /**
     * @return array
     */
    public static function _getRelationList()
    {
        return [
            self::RELATION_FATHER => Yii::t('policy','Отец'),
            self::RELATION_MOTHER => Yii::t('policy','Мать'),
            self::RELATION_HUSBAND => Yii::t('policy','Муж'),
            self::RELATION_WIFE => Yii::t('policy','Жена'),
            self::RELATION_SUN => Yii::t('policy','Сын'),
            self::RELATION_DAUGHTER => Yii::t('policy','Дочь'),
            self::RELATION_BROTHER => Yii::t('policy','Старший брат'),
            self::RELATION_LITTLE_BROTHER => Yii::t('policy','Младший брат'),
            self::RELATION_SISTER => Yii::t('policy','Старшая сестра'),
            self::RELATION_LITTLE_SISTER => Yii::t('policy','Младшая сестра'),
        ];
    }

    /**
     * @param $item
     * @return int|mixed
     */
    public function _getRelationshipName($item = null)
    {
        $arr = self::_getRelationList();
        $item = empty($item) ? $this->relationship_id : $item;
        if (!empty($item) && !empty($arr[$item])) {
            return $arr[$item];
        }
        return $item;
    }

    const RESIDENT_UZB = 1;
    const RESIDENT_FOREIGN = 2;
    const RESIDENT_LGB = 3;
    const RESIDENT_MILITARY = 4;
    const RESIDENT_FOREIGN_RESIDENT = 5;

    /**
     * @return array
     */
    public static function _getResidentList()
    {
        return [
            self::RESIDENT_UZB => Yii::t('policy','Гражданин (УзР фуқароси)'),
            self::RESIDENT_FOREIGN => Yii::t('policy','Иностранец (чет эл фуқароси)'),
            self::RESIDENT_LGB => Yii::t('policy','ЛБГ (фуқаролиги йўқ)'),
            self::RESIDENT_MILITARY => Yii::t('policy','Военный (ҳарбий)'),
            self::RESIDENT_FOREIGN_RESIDENT => Yii::t('policy','Иностранный резидент (чет эл резиденти)'),
        ];
    }

    /**
     * @param $item
     * @return int|mixed
     */
    public function getResidentName($item = null)
    {
        $arr = self::_getResidentList();
        $item = empty($item) ? $this->relationship_id : $item;
        if (!empty($item) && !empty($arr[$item])) {
            return $arr[$item];
        }
        return $item;
    }



    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord && ($this->resident_id == self::RESIDENT_FOREIGN)) {
            $this->pinfl = null;
        }
        return parent::beforeSave($insert);
    }

}
