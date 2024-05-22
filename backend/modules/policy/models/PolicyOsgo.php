<?php

namespace backend\modules\policy\models;

use backend\modules\handbook\models\HandbookFondRegion;
use common\components\behaviors\AuthorBehavior;
use common\library\payment\models\PaymentTransaction;
use common\models\Settings;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "{{%policy_osgo}}".
 *
 * @property int $id
 * @property string $start_date
 * @property string $end_date
 * @property string $policy_series
 * @property string $policy_number
 * @property double $amount_uzs
 * @property double $amount_usd
 * @property int $region_id
 * @property int $period_id
 * @property int $driver_limit_id
 * @property int $discount_id
 * @property int $citizenship_id
 * @property string $owner_orgname
 * @property string $owner_first_name
 * @property string $owner_last_name
 * @property string $owner_middle_name
 * @property string $owner_birthday
 * @property string $owner_pinfl
 * @property string $owner_inn
 * @property string $owner_pass_sery
 * @property string $owner_pass_num
 * @property string $owner_pass_issued_by
 * @property string $owner_pass_issue_date
 * @property string $owner_region
 * @property string $owner_district
 * @property string $owner_is_driver
 * @property int $owner_is_applicant
 * @property int $owner_is_pensioner
 * @property int $owner_fy
 * @property string $app_first_name
 * @property string $app_last_name
 * @property string $app_middle_name
 * @property string $app_birthday
 * @property string $app_pinfl
 * @property string $app_pass_sery
 * @property string $app_pass_num
 * @property string $app_pass_issued_by
 * @property string $app_pass_issue_date
 * @property string $app_phone
 * @property string $app_email
 * @property string $app_region
 * @property string $app_district
 * @property string $app_address
 * @property string $app_gender
 * @property string $app_inn
 * @property string $app_orgname
 * @property int $legal_type
 * @property string $vehicle_gov_number
 * @property string $tech_pass_series
 * @property string $tech_pass_number
 * @property string $tech_pass_issue_date
 * @property string $vehicle_model_name
 * @property int $vehicle_marka_id
 * @property int $vehicle_model_id
 * @property int $vehicle_type_id
 * @property int $vehicle_issue_year
 * @property string $vehicle_body_number
 * @property string $vehicle_engine_number
 * @property string $source
 * @property int $ins_anketa_id
 * @property int $ins_policy_id
 * @property string $uuid_fond
 * @property string $ins_log
 * @property int $status
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $fullPolicyNumber
 * @property string $ownerFullName
 * @property string $ownerFullPassNumber
 * @property string $appFullName
 * @property string $appFullPassNumber
 * @property string $fullTechPassNumber
 * @property string $bot_group_chat_id
 *
 * @property int $_use_territory_id
 * @property int $_policy_price_uzs
 * @property int $_policy_price_usd
 * @property int $_ins_amount
 * @property int $offer
 * @property int $minDriverLimit
 *
 * @property PolicyOrder $policyOrder
 * @property User $createdBy
 * @property User $updatedBy
 * @property PolicyOsgoDriver[] $policyOsgoDrivers
 */
class PolicyOsgo extends \yii\db\ActiveRecord
{
    const SCENARIO_SITE_STEP_CALC = 'site_step_calc';
    const SCENARIO_SITE_STEP_FORM = 'site_step_form';

    const ENABLE_CALCULATE_INS = false;
    const CALC_TYPE_SECOND = true;

    const INSURANCE_SUM = 40000000; // UZS
    const INSURANCE_SUM_COVERAGE_HEALTH = 65; // %
    const INSURANCE_SUM_COVERAGE_PROPERTY = 35; // %

    const LEGAL_TYPE_FIZ = 0;
    const LEGAL_TYPE_YUR = 1;

    const MIN_DRIVER_LIMIT = 0;
    const MIN_DRIVER_LIMIT_1 = 1;
    const MAX_DRIVER_LIMIT = 5;

    const DEFAULT_OWNER_IS_PENSIONER = 1; // DEFAULT OWNER_IS_APPLICANT
    const DEFAULT_OWNER_IS_APPLICANT = 1; // DEFAULT OWNER_IS_APPLICANT

    const DEFAULT_VEHICLE_TYPE = 1; // DEFAULT VEHICLE TYPE - Passenger cars
    const DEFAULT_USE_TERRITORY_REGION = 1; // TASHKENT
    const DEFAULT_PERIOD = 2; // 1 YEAR
    const DEFAULT_DRIVER_LIMIT = 0; // UNLIMITED
    const DEFAULT_DISCOUNT = 0; // UNLIMITED

    const VEHICLE_TYPE_CAR = 1; // Легковые автомобили  0.1
    const VEHICLE_TYPE_TRACK = 6; // Грузовые автомобили  0.12
    const VEHICLE_TYPE_BUS = 9; // Автобусы и микроавтобусы  0.12
    const VEHICLE_TYPE_TRAMS = 15; // Трамваи, мотоциклы и мотороллеры, тракторы, самоходные дорожно-строительные и иные машины  0.04

    const USE_TERRITORY_REGION_CITY = 1; // Регион регистрации транспортного средства - г.Ташкент и Ташкентская область
    const USE_TERRITORY_REGION_DISTRICT = 3; // Регион регистрации транспортного средства - Другие регионы

    const DRIVER_LIMITED = 1; // Количество водителей - Ограничено до 5 человек
    const DRIVER_UNLIMITED = 0; // Количество водителей - Не ограничено

    const PERIOD_6_MONTH = 1; // Период страхования - 6 месяцев
    const PERIOD_12_MONTH = 2; // Период страхования - 1 год

    const START_DAY_MIN = 0;
    const START_DAY_MAX = 60;

    const DISCOUNT_TYPE_NO = 1; // БЕЗ ЛЬГОТ
    const DISCOUNT_TYPE_PENSIONER = 2; // ПЕНСИОНЕР
    const DISCOUNT_TYPE_DISABLED_PERSON = 3; // ИНВАЛИД
    const DISCOUNT_TYPE_SECOND_WAR = 4; // УЧАСТНИК ВОЙНЫ 1941-1945 ЛИБО ПРИРАВНЕННЫМИ К НИМ ЛИЦАМИ
    const DISCOUNT_TYPE_VETERAN_OF_THE_LABOR_FRONT = 5; // ВЕТЕРАН ТРУДОВОГО ФРОНТА 1941-1945
    const DISCOUNT_TYPE_FORMER_PRISONER = 6; // БЫВШИЙ УЗНИК КОНЦЕНТРАЦИОННЫХ ЛАГЕРЕЙ
    const DISCOUNT_TYPE_WIDOW_PARENT_DEAD_SERVICE_SERVANT = 7; // ВДОВА/РОДИТЕЛЬ ПОГИБШЕГО ВОЕННОСЛУЖАЩЕГО
    const DISCOUNT_TYPE_INJURED_CHERNOBYL_NPP = 8; // ПОСТРАДАВШИЙ В ЧЕРНОБОЛЬСКОЙ АЭС
    const DISCOUNT_TYPE_PARTICIPANTS_AFGHAN_WAR = 9; // УЧАСТНИКИ АФГАНСКОЙ ВОЙНЫ

    public $_vehicleTypesList;
    public $_useTerritoryRegionsList;
    public $_periodList;
    public $_driverLimitList;

    public $bot_group_chat_id = '-887395551'; // Online Sale Osgo group id

    public $_use_territory_id;
    public $_policy_price_uzs;
    public $_policy_price_usd;
    public $_ins_amount;
    public $_tmp_message;
    public $offer;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_osgo}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            AuthorBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $start_day_min = self::START_DAY_MIN;
        $start_day_max = self::START_DAY_MAX;
        $begin_Date = ($this->isNewRecord) ? date('d.m.Y',strtotime("+{$start_day_min} day", time())) : $this->start_date;
        $end_Date = ($this->isNewRecord) ? date('d.m.Y',strtotime("+{$start_day_max} days", time())) : $this->end_date;

        return [
            [['vehicle_type_id', '_use_territory_id', 'region_id', 'period_id', 'driver_limit_id', 'start_date', 'end_date',  ], 'required', 'on' => self::SCENARIO_SITE_STEP_CALC, 'message' => Yii::t('validation','Необходимо заполнить')],
            [['vehicle_type_id', 'region_id', 'period_id', 'driver_limit_id', 'start_date', 'end_date',  ], 'safe', 'on' => self::SCENARIO_SITE_STEP_FORM],
            [['vehicle_gov_number', 'tech_pass_series', 'tech_pass_number', 'vehicle_model_name', 'vehicle_type_id', 'owner_orgname', 'region_id', 'vehicle_issue_year', 'app_phone', ], 'required', 'on' => self::SCENARIO_SITE_STEP_FORM, 'message' => Yii::t('validation','Необходимо заполнить')],
            [['start_date', 'end_date', 'owner_birthday', 'owner_region', 'owner_district', 'owner_is_driver', 'owner_is_pensioner', 'owner_pass_issue_date', 'app_birthday', 'app_pass_issue_date', 'app_inn', 'app_orgname', 'tech_pass_issue_date', 'vehicle_type_id', 'owner_fy', 'app_region', 'app_district',], 'safe'],
            [
                ['start_date', ], 'date', 'format' => 'dd.MM.yyyy',
                'min' => $begin_Date, 'tooSmall' => Yii::t('validation', 'Дата начала страхования не соответствует требованиям'),
                'max' => $end_Date, 'tooBig' => Yii::t('validation', 'Дата начала страхования не соответствует требованиям'),
            ],
            [['app_birthday', 'app_pass_sery', 'app_pass_num', 'app_last_name', 'app_first_name', 'app_middle_name', 'app_region', 'app_district',], 'required', 'message' => Yii::t('validation','Необходимо заполнить'),
                'on' => self::SCENARIO_SITE_STEP_FORM, 'when' => function ($model) {
                        return !$model->owner_is_applicant;
                    }, 'whenClient' => "function (attribute, value) {
                        return !($('#policyosgo-owner_is_applicant').is(':checked'));
                    }"
            ],
            [['owner_pinfl', 'owner_pass_sery', 'owner_pass_num', ], 'required', 'message' => Yii::t('validation','Необходимо заполнить'),
                'on' => self::SCENARIO_SITE_STEP_FORM, 'when' => function ($model) {
                        return !$model->owner_fy;
                    }, 'whenClient' => "function (attribute, value) {
                        return ($('#owner_fy').val() == 0);
                    }"
            ],
            [['app_phone'], 'match', 'pattern' => '/^\+998 \((\d{2})\) (\d{3})-(\d{2})-(\d{2})$/',
                'on' => self::SCENARIO_SITE_STEP_FORM, 'message' => Yii::t('validation','Неверный формат телефона')
            ],
            [['owner_is_pensioner', ], 'default', 'value' => self::DEFAULT_OWNER_IS_PENSIONER],
            [['amount_uzs', 'amount_usd'], 'number'],
            [['period_id', 'driver_limit_id', 'discount_id', 'citizenship_id', 'owner_is_applicant', 'legal_type', 'vehicle_marka_id', 'vehicle_model_id', 'vehicle_issue_year', 'ins_anketa_id', 'ins_policy_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['uuid_fond','ins_log'], 'string'],
            [['policy_series', 'policy_number', 'owner_orgname', 'owner_first_name', 'owner_last_name', 'owner_middle_name', 'owner_pinfl', 'owner_inn', 'owner_pass_sery', 'owner_pass_num', 'owner_pass_issued_by', 'app_first_name', 'app_last_name', 'app_middle_name', 'app_pinfl', 'app_pass_sery', 'app_pass_num', 'app_pass_issued_by', 'app_phone', 'app_email', 'app_address', 'app_gender', 'vehicle_gov_number', 'tech_pass_series', 'tech_pass_number', 'vehicle_model_name', 'vehicle_body_number', 'vehicle_engine_number', 'source'], 'string', 'max' => 255],

            [['region_id', ], 'safe'],

            ['offer', 'boolean'],
            ['offer', 'required', 'requiredValue' => true, 'message' => Yii::t('validation', 'Это поле обязательно для заполнения.'), 'on' => self::SCENARIO_SITE_STEP_CALC],

            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
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
            'start_date' => Yii::t('policy', 'Start Date'),
            'end_date' => Yii::t('policy', 'End Date'),
            'policy_series' => Yii::t('policy', 'Policy Series'),
            'policy_number' => Yii::t('policy', 'Policy Number'),
            'amount_uzs' => Yii::t('policy', 'Amount Uzs'),
            'amount_usd' => Yii::t('policy', 'Amount Usd'),
            '_use_territory_id' => Yii::t('policy', 'Регион регистрации транспортного средства'),
            'region_id' => Yii::t('policy', 'Region'),
            'period_id' => Yii::t('policy', 'Период страхования'),
            'driver_limit_id' => Yii::t('policy', 'Количество водителей'),
            'discount_id' => Yii::t('policy', 'Discount ID'),
            'citizenship_id' => Yii::t('policy', 'Citizenship'),
            'owner_orgname' => Yii::t('policy', 'Owner full name'),
            'owner_first_name' => Yii::t('policy', 'Owner First Name'),
            'owner_last_name' => Yii::t('policy', 'Owner Last Name'),
            'owner_middle_name' => Yii::t('policy', 'Owner Middle Name'),
            'owner_birthday' => Yii::t('policy', 'Owner Birthday'),
            'owner_pinfl' => Yii::t('policy', 'Owner Pinfl'),
            'owner_inn' => Yii::t('policy', 'Owner Inn'),
            'owner_pass_sery' => Yii::t('policy', 'Owner Passport/ID sery'),
            'owner_pass_num' => Yii::t('policy', 'Owner Passport/ID number'),
            'owner_pass_issued_by' => Yii::t('policy', 'Owner Pass Issued By'),
            'owner_pass_issue_date' => Yii::t('policy', 'Owner Pass Issue Date'),
            'owner_is_applicant' => Yii::t('policy', 'Мурожаат қилувчи ТВ эгаси'),
            'owner_region' => Yii::t('policy', 'Регион регистрации ТС'),
            'owner_district' => Yii::t('policy', 'Район регистрации ТС'),
            'owner_is_driver' => Yii::t('policy', 'Я также являюсь одним из водителей данного транспортного средства'),
            'owner_is_pensioner' => Yii::t('policy', 'Сийлов'),
            'app_first_name' => Yii::t('policy', 'App First Name'),
            'app_last_name' => Yii::t('policy', 'App Last Name'),
            'app_middle_name' => Yii::t('policy', 'App Middle Name'),
            'app_birthday' => Yii::t('policy', 'App Birthday'),
            'app_pinfl' => Yii::t('policy', 'App Pinfl'),
            'app_pass_sery' => Yii::t('policy', 'Applicant passport/ID'),
            'app_pass_num' => Yii::t('policy', 'App Passport/ID Num'),
            'app_pass_issued_by' => Yii::t('policy', 'App Pass Issued By'),
            'app_pass_issue_date' => Yii::t('policy', 'App Pass Issue Date'),
            'app_phone' => Yii::t('policy', 'Owner Phone'),
            'app_email' => Yii::t('policy', 'App Email'),
            'app_address' => Yii::t('policy', 'App Address'),
            'app_gender' => Yii::t('policy', 'App Gender'),
            'legal_type' => Yii::t('policy', 'Legal Type'),
            'vehicle_gov_number' => Yii::t('policy', 'Vehicle Gov Number'),
            'tech_pass_series' => Yii::t('policy', 'Tech Pass Series'),
            'tech_pass_number' => Yii::t('policy', 'Tech Pass Number'),
            'tech_pass_issue_date' => Yii::t('policy', 'Tech Pass Issue Date'),
            'vehicle_model_name' => Yii::t('policy', 'Vehicle Model Name'),
            'vehicle_marka_id' => Yii::t('policy', 'Vehicle Marka ID'),
            'vehicle_model_id' => Yii::t('policy', 'Vehicle Model ID'),
            'vehicle_type_id' => Yii::t('policy', 'Вид транспортного средства'),
            'vehicle_issue_year' => Yii::t('policy', 'Vehicle Issue Year'),
            'vehicle_body_number' => Yii::t('policy', 'Vehicle Body Number'),
            'vehicle_engine_number' => Yii::t('policy', 'Vehicle Engine Number'),
            'source' => Yii::t('policy', 'Source'),
            'ins_anketa_id' => Yii::t('policy', 'Ins Anketa ID'),
            'ins_policy_id' => Yii::t('policy', 'Ins Policy ID'),
            'ins_log' => Yii::t('policy', 'Ins Log'),
            'status' => Yii::t('policy', 'Status'),
            'offer' => Yii::t('policy', 'Я прочитал и принимаю'),
            'created_by' => Yii::t('policy', 'Created By'),
            'updated_by' => Yii::t('policy', 'Updated By'),
            'created_at' => Yii::t('policy', 'Created At'),
            'updated_at' => Yii::t('policy', 'Updated At'),
        ];
    }

    /**
     * @return void
     */
    public function _loadDefaultValues()
    {
        $this->vehicle_type_id = self::DEFAULT_VEHICLE_TYPE;
        $this->_use_territory_id = self::DEFAULT_USE_TERRITORY_REGION;
        $this->region_id = self::DEFAULT_USE_TERRITORY_REGION;
        $this->period_id = self::DEFAULT_PERIOD;
        $this->driver_limit_id = self::DEFAULT_DRIVER_LIMIT;
        $this->discount_id = self::DEFAULT_DISCOUNT;
        $this->start_date = date('d.m.Y');
        $this->setEndDate();
        $this->end_date = date('d.m.Y', strtotime($this->end_date));

        $this->owner_is_applicant = self::DEFAULT_OWNER_IS_APPLICANT;
        $this->owner_is_pensioner = self::DEFAULT_OWNER_IS_PENSIONER;
        $this->app_phone = (!Yii::$app->user->isGuest && is_numeric(clear_phone_full(Yii::$app->user->identity['username']))) ? Yii::$app->user->identity['username'] : '+998';
        $this->vehicle_gov_number = LOCAL_DOMAIN ? '10X654WA' : null;
        $this->tech_pass_series = LOCAL_DOMAIN ? 'AAF' : null;
        $this->tech_pass_number = LOCAL_DOMAIN ? '3523002' : null;
    }

    /**
     * @return string
     */
    public function getAppFullName()
    {
        return $this->app_last_name . ' ' . $this->app_first_name .' ' . $this->app_middle_name;
    }

    /**
     * @return string
     */
    public function getOwnerFullName()
    {
        return !empty($this->owner_orgname) ? $this->owner_orgname : $this->owner_last_name . ' ' . $this->owner_first_name .' ' . $this->owner_middle_name;
    }

    /**
     * @return string
     */
    public function getFullPolicyNumber()
    {
        $policy_number = $this->policy_series ?: null;
        $policy_number .= (!empty($this->policy_number)) ? ' '.$this->policy_number : null;
        return $policy_number;
    }

    /**
     * @return string
     */
    public function getFullTechPassNumber()
    {
        $full_string = $this->tech_pass_series ?: null;
        $full_string .= (!empty($this->tech_pass_number)) ? ' '.$this->tech_pass_number : null;
        return $full_string;
    }


    /**
     * @return string
     */
    public function getAppFullPassNumber()
    {
        $full_string = $this->app_pass_sery ?: null;
        $full_string .= (!empty($this->app_pass_num)) ? ' '.$this->app_pass_num : null;
        return $full_string;
    }

    /**
     * @return string
     */
    public function getOwnerFullPassNumber()
    {
        $full_string = $this->owner_pass_sery ?: null;
        $full_string .= (!empty($this->owner_pass_num)) ? ' '.$this->owner_pass_num : null;
        return $full_string;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyOrder()
    {
        return $this->hasOne(PolicyOrder::className(), ['revision_id' => 'id'])->onCondition(['revision_model' => $this->formName()]);
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
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyOsgoDrivers()
    {
        return $this->hasMany(PolicyOsgoDriver::className(), ['policy_osgo_id' => 'id'])->orderBy(['id' => SORT_ASC]);
    }


    /**
     * @return void
     */
    public function setEndDate()
    {
        if ($this->period_id == self::PERIOD_12_MONTH) {
            $this->end_date = date('Y-m-d', strtotime("+1 year", strtotime($this->start_date)));
        } else {
            $this->end_date = date('Y-m-d', strtotime("+6 months", strtotime($this->start_date)));
        }
        $this->end_date = date('Y-m-d', strtotime("-1 day", strtotime($this->end_date)));
    }

    /**
     * @return void
     */
    public function setAppPhone()
    {
        if (empty($this->app_phone)) {
            $this->app_phone = (!Yii::$app->user->isGuest && is_numeric(clear_phone_full(Yii::$app->user->identity['username']))) ? '+'.clear_phone_full(Yii::$app->user->identity['username']) : '+998';
        }
    }

    /**
     * @return int
     */
    public function getMinDriverLimit()
    {
        return ($this->driver_limit_id == self::DRIVER_LIMITED) ? self::MIN_DRIVER_LIMIT_1 : self::MIN_DRIVER_LIMIT;
    }

    /**
     * @return array|mixed
     * @throws BadRequestHttpException
     */
    public function calculatePremPrice()
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_CALC_PREM);
            if (!empty($this->vehicle_type_id) && !empty($this->region_id) && !is_null($this->driver_limit_id) && !empty($this->period_id)) {
                $handBookService->setParams([
                    'vehicle' => $this->vehicle_type_id,
                    'use_territory' => $this->region_id,
                    'period' => $this->period_id,
                    'driver_limit' => $this->driver_limit_id,
                    'discount' => $this->discount_id,
                ]);
                $data = $handBookService->sendRequestIns();
                if (!empty($data['prem'])) {
                    $response = $data;
                    $this->_policy_price_uzs = !empty($data['prem'] ) ? $data['prem']  : 0;
                    $this->amount_uzs = !empty($data['prem'] ) ? $data['prem']  : 0;
                    $this->_policy_price_usd = !empty($data['response']['fullprice']['price']) ? $data['response']['fullprice']['price'] : 0;
                } else {
                    $this->_policy_price_usd = 0;
                    $this->_policy_price_uzs = 0;
                    $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                    $this->_tmp_message = $title;
                    _send_error($title, json_encode($data,JSON_UNESCAPED_UNICODE));
                    throw new BadRequestHttpException($title);
                }
                $this->_ins_amount = self::INSURANCE_SUM;
            } else {
                $this->_policy_price_usd = 0;
                $this->_policy_price_uzs = 0;
//            $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
//            _send_error($title, $title);
//            throw new BadRequestHttpException($title);
            }
        } else {
            $vehicle_type_coeff = is_numeric($this->_getVehicleTypesCoeffList($this->vehicle_type_id)) ? $this->_getVehicleTypesCoeffList($this->vehicle_type_id) : 1;
            $use_territory_coeff = is_numeric($this->_getUseTerritoryCoeffList($this->region_id)) ? $this->_getUseTerritoryCoeffList($this->region_id) : 1;
            $driver_limit_coeff = is_numeric($this->_getDriverLimitCoeffList($this->driver_limit_id)) ? $this->_getDriverLimitCoeffList($this->driver_limit_id) : 1;
            $period_coeff = is_numeric($this->_getPeriodCoeffList($this->period_id)) ? $this->_getPeriodCoeffList($this->period_id) : 1;
            $discount_coeff = is_numeric($this->_getDiscountCoeffList($this->owner_is_pensioner)) ? $this->_getDiscountCoeffList($this->owner_is_pensioner) : 1;

            $prem = self::INSURANCE_SUM * $vehicle_type_coeff / 100 * $use_territory_coeff * $driver_limit_coeff * $period_coeff * $discount_coeff;

            Yii::warning("\n\ncalculatePremPrice\n");
            Yii::warning($this->attributes);
            Yii::warning("\n\nvehicle_type_coeff\n");
            Yii::warning($vehicle_type_coeff);
            Yii::warning("\n\nuse_territory_coeff\n");
            Yii::warning($use_territory_coeff);
            Yii::warning("\n\ndriver_limit_coeff\n");
            Yii::warning($driver_limit_coeff);
            Yii::warning("\n\nperiod_coeff\n");
            Yii::warning($period_coeff);
            Yii::warning("\n\nprem\n");
            Yii::warning($prem);

            $this->_policy_price_uzs = $prem ?: 0;
            $this->amount_uzs = $prem ?: 0;
            $this->_ins_amount = self::INSURANCE_SUM;
            $response['prem'] = $prem;
        }

        return $response;
    }


    /**
     * @param null $item
     * @return array
     * @throws BadRequestHttpException
     */
    public function getVehicleTypesList ($item=null)
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_GET_VEHICLE_TYPES);

            $data = $handBookService->sendRequestIns();
            if (!empty($data) && is_array($data)) {
                $name_field_default = 'name';
                $name_field = 'name';
                $name_field .= '_'._lang();
                $name_field = mb_strtoupper($name_field);
                $name_field_default = mb_strtoupper($name_field_default);
                foreach ($data as $dataItem) {
//                dd($dataItem);
                    if (!empty($dataItem['ID'])) {
                        $response[$dataItem['ID']] = !empty($dataItem[$name_field]) ? $dataItem[$name_field] : $dataItem[$name_field_default];
                        if ($dataItem['ID'] == self::DEFAULT_VEHICLE_TYPE) {
                            if ($this->vehicle_type_id == null) {
                                $this->vehicle_type_id = $dataItem['ID'];
                            }
                        }
                    }
                }
            } else {
                $this->vehicle_type_id = self::DEFAULT_VEHICLE_TYPE;
                $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                $this->_tmp_message = $title;
                _send_error($title, json_encode($data,JSON_UNESCAPED_UNICODE));
                throw new BadRequestHttpException($title);
            }

        } else {
            $response = [
                self::VEHICLE_TYPE_CAR => Yii::t('policy','Легковые автомобили'),
                self::VEHICLE_TYPE_TRACK => Yii::t('policy','Грузовые автомобили'),
                self::VEHICLE_TYPE_BUS => Yii::t('policy','Автобусы и микроавтобусы'),
                self::VEHICLE_TYPE_TRAMS => Yii::t('policy','Трамваи, мотоциклы и мотороллеры, тракторы, самоходные дорожно-строительные и иные машины'),
            ];
        }

        if (!empty($item)) {
            return !empty($response[$item]) ? $response[$item] : $item;
        }
        return $response;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getUseTerritoryList($item=null)
    {
        $arr = [
            self::USE_TERRITORY_REGION_CITY => Yii::t('policy','г.Ташкент и Ташкентская область'),
            self::USE_TERRITORY_REGION_DISTRICT => Yii::t('policy','Другие регионы'),
        ];

        if (!empty($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getDriverLimitList($item=null)
    {
        $arr = [
            self::DRIVER_UNLIMITED => Yii::t('policy','Не ограничено'),
            self::DRIVER_LIMITED => Yii::t('policy','Ограничено до 5 человек'),
        ];

        if (!is_null($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getAddDriverLabelList($item=null)
    {
        $arr = [
            self::DRIVER_UNLIMITED => Yii::t('policy','Добавить родственник'),
            self::DRIVER_LIMITED => Yii::t('policy','Добавить водителя'),
        ];

        if (!is_null($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getCountDriverLabelList($item=null)
    {
        $arr = [
            self::DRIVER_UNLIMITED => Yii::t('policy','Родственник'),
            self::DRIVER_LIMITED => Yii::t('policy','Водитель'),
        ];

        if (!is_null($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getPeriodList($item=null)
    {
        $arr = [
            self::PERIOD_12_MONTH => Yii::t('policy','1 год'),
            self::PERIOD_6_MONTH => Yii::t('policy','6 месяцев'),
        ];

        if (!empty($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getDiscountList($item=null)
    {
        $arr = [
            self::DISCOUNT_TYPE_NO => Yii::t('policy','БЕЗ ЛЬГОТ'),
            self::DISCOUNT_TYPE_PENSIONER => Yii::t('policy','ПЕНСИОНЕР'),
            self::DISCOUNT_TYPE_DISABLED_PERSON => Yii::t('policy','ИНВАЛИД'),
            self::DISCOUNT_TYPE_SECOND_WAR => Yii::t('policy','УЧАСТНИК ВОЙНЫ 1941-1945 ЛИБО ПРИРАВНЕННЫМИ К НИМ ЛИЦАМИ'),
            self::DISCOUNT_TYPE_VETERAN_OF_THE_LABOR_FRONT => Yii::t('policy','ВЕТЕРАН ТРУДОВОГО ФРОНТА 1941-1945'),
            self::DISCOUNT_TYPE_FORMER_PRISONER => Yii::t('policy','БЫВШИЙ УЗНИК КОНЦЕНТРАЦИОННЫХ ЛАГЕРЕЙ'),
            self::DISCOUNT_TYPE_WIDOW_PARENT_DEAD_SERVICE_SERVANT => Yii::t('policy','ВДОВА/РОДИТЕЛЬ ПОГИБШЕГО ВОЕННОСЛУЖАЩЕГО'),
            self::DISCOUNT_TYPE_INJURED_CHERNOBYL_NPP => Yii::t('policy','ПОСТРАДАВШИЙ В ЧЕРНОБОЛЬСКОЙ АЭС'),
            self::DISCOUNT_TYPE_PARTICIPANTS_AFGHAN_WAR => Yii::t('policy','УЧАСТНИКИ АФГАНСКОЙ ВОЙНЫ'),
        ];

        if (!empty($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return float|float[]|mixed
     */
    public function _getVehicleTypesCoeffList($item=null)
    {
        $arr = [
            self::VEHICLE_TYPE_CAR => 0.1,
            self::VEHICLE_TYPE_TRACK => 0.12,
            self::VEHICLE_TYPE_BUS => 0.12,
            self::VEHICLE_TYPE_TRAMS => 0.04,
        ];

        if (!empty($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getUseTerritoryCoeffList($item=null)
    {
        $arr = [
            self::USE_TERRITORY_REGION_CITY => 1.4,
            2 => 1.4,
            self::USE_TERRITORY_REGION_DISTRICT => 1,
            4 => 1,
            5 => 1,
            6 => 1,
            7 => 1,
            8 => 1,
            9 => 1,
            10 => 1,
            11 => 1,
            12 => 1,
            13 => 1,
            14 => 1,
        ];

        if (!empty($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return int|int[]|mixed
     */
    public function _getDriverLimitCoeffList($item=null)
    {
        $arr = [
            self::DRIVER_UNLIMITED => 3,
            self::DRIVER_LIMITED => 1,
        ];

        if (!is_null($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getPeriodCoeffList($item=null)
    {
        $arr = [
            self::PERIOD_6_MONTH => 0.7,
            self::PERIOD_12_MONTH => 1,
        ];

        if (!empty($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    /**
     * @param $item
     * @return array|mixed
     */
    public function _getDiscountCoeffList($item=null)
    {
        $arr = [
            self::DISCOUNT_TYPE_PENSIONER => 0.5,
            self::DISCOUNT_TYPE_DISABLED_PERSON => 0.5,
            self::DISCOUNT_TYPE_SECOND_WAR => 0.5,
            self::DISCOUNT_TYPE_VETERAN_OF_THE_LABOR_FRONT => 0.5,
            self::DISCOUNT_TYPE_FORMER_PRISONER => 0.5,
            self::DISCOUNT_TYPE_WIDOW_PARENT_DEAD_SERVICE_SERVANT => 0.5,
            self::DISCOUNT_TYPE_INJURED_CHERNOBYL_NPP => 0.5,
            self::DISCOUNT_TYPE_PARTICIPANTS_AFGHAN_WAR => 0.5,
        ];

        if (!empty($item)) {
            return !empty($arr[$item]) ? $arr[$item] : $item;
        }
        return $arr;
    }

    public function isOsgoLimited()
    {
        return Settings::getValueByKey(Settings::KEY_OSGO_LIMITED);
    }

    public function _isEnabledOsgoLimited()
    {
        return ( (($this->driver_limit_id == self::DRIVER_LIMITED) || ($this->period_id == self::PERIOD_6_MONTH)) && $this->isOsgoLimited() );
    }

    /**
     * @param $items
     * @return array|mixed|string|string[]
     * @throws BadRequestHttpException
     */
    public static function _getTechPassData($items=null, PolicyOsgo $model = null)
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS || 1) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_VEHICLE);

            $handBookService->setParams([
                'techPassportSeria' => !empty($items['tech_pass_series']) ? $items['tech_pass_series'] : '',
                'techPassportNumber' => !empty($items['tech_pass_number']) ? $items['tech_pass_number'] : '',
                'govNumber' => !empty($items['vehicle_gov_number']) ? $items['vehicle_gov_number'] : '',
            ]);

            $car_number_prefix = _getCarAutoNumberPrefix($items['vehicle_gov_number']);
            $region_list_info = HandbookFondRegion::_getAllModelsList();

            $_use_territory_id_by_car_number = null;
            $_region_id = null;
            $_district_id = null;
            $_region_name = null;
            if (!empty($model->region_id) || PolicyOsgo::CALC_TYPE_SECOND) {
                if ($model->_isEnabledOsgoLimited()) {
                    return [
                        'ERROR' => HandBookIns::FOND_ERROR_503,
                        'ERROR_MESSAGE' => HandBookIns::getFondError(HandBookIns::FOND_ERROR_503),
                    ];
                }
                foreach ($region_list_info as $_key => $region) {
                    if (in_array($car_number_prefix, explode(',',$region->car_number_prefixes))) {
                        $_use_territory_id_by_car_number = $region->territory_id;
                        $_region_id = $region->ins_id;
                        if (!empty($model->region_id) && PolicyOsgo::CALC_TYPE_SECOND) {
                            $model->region_id = $region->territory_id; /*Setting region id by car number*/
                        }
                        $_region_name = $region->shortName;
                        $_district_id = !empty($region->handbookFondRegion->ins_id) ? $region->handbookFondRegion->ins_id : null;
                        break;
                    }
                }
//                if (!empty($model->region_id) && !in_array($_use_territory_id_by_car_number, _checkRegionByUseTerritory($model->region_id))) {
//                    return [
//                        'ERROR' => HandBookIns::FOND_ERROR_422,
//                        'ERROR_MESSAGE' => HandBookIns::getFondError(HandBookIns::FOND_ERROR_422),
//                    ];
//                }
            }

            $data = $handBookService->sendRequestIns();
            if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                $modelOsgo = empty($model) ? new PolicyOsgo() : $model;
                if (!empty($model->vehicle_type_id) && !empty($data['VEHICLE_TYPE_ID']) && PolicyOsgo::CALC_TYPE_SECOND) {
                    $modelOsgo->vehicle_type_id = $data['VEHICLE_TYPE_ID'];
                }

                if (!empty($modelOsgo->vehicle_type_id) && ($modelOsgo->vehicle_type_id != $data['VEHICLE_TYPE_ID'])) {
//                    return [
//                        'ERROR' => HandBookIns::FOND_ERROR_423,
//                        'ERROR_MESSAGE' => HandBookIns::getFondError(HandBookIns::FOND_ERROR_423),
//                    ];
                }

                $response = $data;
                $response['VEHICLE_TYPE_NAME'] = $modelOsgo->getVehicleTypesList($data['VEHICLE_TYPE_ID']);
                $response['VEHICLE_TERRITORY_ID'] = $_use_territory_id_by_car_number;
                $response['REGION_NAME'] = $_region_name;
                $response['PASSPORT_SERIES'] = null;
                $response['PASSPORT_NUMBER'] = null;
                $response['PASSPORT_ISSUED_BY'] = null;
                $response['PASSPORT_ISSUE_DATE'] = null;
                $response['BIRTHDAY'] = null;
                $response['ADDRESS'] = null;
                $didox_tin = null;
                if (!empty($data['PINFL'])) {
                    $didox_tin = trim($data['PINFL']);
                    $response['BIRTHDAY'] = _getBirthdayFromPinfl(trim($data['PINFL']));
                } elseif (!empty($data['INN'])) {
                    $didox_tin = trim($data['INN']);
                }
                if (!empty($didox_tin)) {
                    $didox_data = self::_getDidoxTinData($didox_tin);
                    if ( (!is_null($data['FY']) && $data['FY'] == 0) && !empty($didox_data['passSeries']) && !empty($didox_data['passNumber'])) {
                        $response['PASSPORT_SERIES'] = trim(mb_strtoupper($didox_data['passSeries']));
                        $response['PASSPORT_NUMBER'] = trim(mb_strtoupper($didox_data['passNumber']));
                        $response['PASSPORT_ISSUED_BY'] = trim(mb_strtoupper($didox_data['passOrg']));
                        $response['PASSPORT_ISSUE_DATE'] = trim(mb_strtoupper($didox_data['passIssueDate']));
                        $response['ADDRESS'] = trim(mb_strtoupper($didox_data['address']));
                    }
                }
                if (!empty($response['PINFL']) && !empty($response['PASSPORT_SERIES']) && !empty($response['PASSPORT_NUMBER'])) {
                    $items_pinfl = [
                        'pinfl' => !empty($response['PINFL']) ? trim($response['PINFL']) : '',
                        'pass_series' => !empty($response['PASSPORT_SERIES']) ? trim($response['PASSPORT_SERIES']) : '',
                        'pass_number' => !empty($response['PASSPORT_NUMBER']) ? trim($response['PASSPORT_NUMBER']) : '',
                    ];
                    $pinfl_data = self::_getPassPersonalIDData($items_pinfl);
                    if ( !empty($pinfl_data['LAST_NAME_LATIN']) && !empty($pinfl_data['FIRST_NAME_LATIN'])) {
                        $response['REGION_ID'] = !empty($pinfl_data['OBLAST']) ? $pinfl_data['OBLAST'] : $_region_id;
                        $response['DISTRICT_ID'] = !empty($pinfl_data['RAYON']) ? $pinfl_data['RAYON'] : $_district_id;
                        $response['BIRTHDAY'] = !empty($pinfl_data['BIRTH_DATE']) ? $pinfl_data['BIRTH_DATE'] : $response['BIRTHDAY'];
                        $response['ISPENSIONER'] = !is_null($pinfl_data['ISPENSIONER']) ? $pinfl_data['ISPENSIONER'] : PolicyOsgo::DEFAULT_OWNER_IS_PENSIONER;
                        $items_discount = [
                            'pinfl' => !empty($response['PINFL']) ? trim($response['PINFL']) : '',
                            'govNumber' => !empty($items['vehicle_gov_number']) ? $items['vehicle_gov_number'] : '',
                        ];
                        if ($response['ISPENSIONER'] != self::DEFAULT_OWNER_IS_PENSIONER) {
                            $discount_data = self::_getProvidedDiscountsData($items_discount);
                            if ( !empty($discount_data['POLICY']) && !empty($discount_data['GOVNUMBER'])) {
                                $response['ISPENSIONER'] = PolicyOsgo::DEFAULT_OWNER_IS_PENSIONER;
                                $response['ERROR_MESSAGE'] = Yii::t('policy', 'Sizda siylov mavjud, lekin foydalana olmaysiz! Sababi {policy} raqamli polisni {car_number} avtomoshina uchun ishlatgansiz',[
                                    'policy' => $discount_data['POLICY'],
                                    'car_number' => $discount_data['GOVNUMBER'],
                                ]);
                            }
                        }

                    } else {
                        $response['ERROR'] = $pinfl_data['ERROR'];
                        $response['ERROR_MESSAGE'] = $pinfl_data['ERROR_MESSAGE'];
                    }
                }
            } else {
                $response = $data;
            }

        }

        if (!empty($item)) {
            return !empty($response[$item]) ? $response[$item] : $item;
        }
        return $response;
    }

    /**
     * @param $item
     * @return array|mixed|string|string[]
     * @throws BadRequestHttpException
     */
    public static function _getDidoxTinData($item=null)
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS || 1) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(BASE_URL_DIDOX_API);
            $handBookService->setMethod(HandBookIns::METHOD_DIDOX_GET_PROFILE.$item);

            $data = $handBookService->sendRequestIns('');
            if (!empty($data) && is_array($data)) {
                $response = $data;
            } else {
                $data = $handBookService->sendRequestIns('');
                if (!empty($data) && is_array($data)) {
                    $response = $data;
                } else {
                    $data = $handBookService->sendRequestIns('');
                    if (!empty($data) && is_array($data)) {
                        $response = $data;
                    } else {
                        $title = Yii::t('policy', 'DIDOXda Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                        _send_error($title, json_encode($data,JSON_UNESCAPED_UNICODE));
                        throw new BadRequestHttpException($title);
                    }

                }

            }

        }

        return $response;
    }

    /**
     * @param $item
     * @return array|mixed|string|string[]
     * @throws BadRequestHttpException
     */
    public static function _getDriverLicenseData($items=null)
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS || 1) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_DRIVER_LICENSE);

            $handBookService->setParams([
                'pinfl' => !empty($items['pinfl']) ? $items['pinfl'] : '',
                'passportSeries' => !empty($items['pass_series']) ? $items['pass_series'] : '',
                'passportNumber' => !empty($items['pass_number']) ? $items['pass_number'] : '',
            ]);

            $data = $handBookService->sendRequestIns();
            if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                $response = $data;
            } else {
                $response = $data;
            }

        }

        return $response;
    }

    /**
     * @param $items
     * @return array|mixed|string|string[]
     * @throws BadRequestHttpException
     */
    public static function _getPassBirthdayData($items=null)
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS || 1) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_PASSPORT_BIRTH_DATE);

            $handBookService->setParams([
                'birthDate' => !empty($items['birthday']) ? $items['birthday'] : '',
                'passportSeries' => !empty($items['pass_series']) ? $items['pass_series'] : '',
                'passportNumber' => !empty($items['pass_number']) ? $items['pass_number'] : '',
            ]);

            $data = $handBookService->sendRequestIns();
            if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                $response = $data;
                $response['ADDRESS'] = null;
                $response['PINFL'] = null;
                $response['FULL_NAME_TMP'] = !empty($response['LAST_NAME']) ? $response['LAST_NAME'] : '';
                $response['FULL_NAME_TMP'] .= !empty($response['FIRST_NAME']) ? " ".$response['FIRST_NAME'] : '';
                $response['FULL_NAME_TMP'] .= !empty($response['MIDDLE_NAME']) ? " ".$response['MIDDLE_NAME'] : '';
                if (!empty($data['PINFL'])) {
                    $response['PINFL'] = trim($data['PINFL']);
                    $items['pinfl'] = trim($data['PINFL']);
                }

                if (!empty($response['PINFL']) && (!is_null($items['driver_id']) && $items['driver_id'] != '')) {
                    $driver_data = self::_getDriverLicenseData($items);
                    if (empty($driver_data['ERROR']) && $driver_data['ERROR'] == 0) {

                        $response['LICENSE_SERIA'] = trim(mb_strtoupper($driver_data['LICENSE_SERIA']));
                        $response['LICENSE_NUMBER'] = trim(mb_strtoupper($driver_data['LICENSE_NUMBER']));
                        $response['ISSUE_DATE'] = trim(mb_strtoupper($driver_data['ISSUE_DATE']));

                    }
//                    else {
//                        $response['ERROR'] = 404;
//                        $response['ERROR_MESSAGE'] = !empty($driver_data['ERROR_MESSAGE']) ? $driver_data['ERROR_MESSAGE'] : Yii::t('policy','Driver licence not found');
//                    }
                }
            } else {
                $response = $data;
            }

        }

        return $response;
    }


    /**
     * @param $items
     * @return array|mixed|string|string[]
     * @throws BadRequestHttpException
     */
    public static function _getPassPersonalIDData($items=null)
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS || 1) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_PASSPORT_PERSONAL_ID);

            $handBookService->setParams([
                'pinfl' => !empty($items['pinfl']) ? $items['pinfl'] : '',
                'passportSeries' => !empty($items['pass_series']) ? $items['pass_series'] : '',
                'passportNumber' => !empty($items['pass_number']) ? $items['pass_number'] : '',
            ]);

            $data = $handBookService->sendRequestIns();
            if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                $response = $data;
            } elseif (!empty($data['ERROR'])) {
                $response['ERROR'] = $data['ERROR'];
                $response['ERROR_MESSAGE'] = !empty($data['ERROR_MESSAGE']) ? $data['ERROR_MESSAGE'] : Yii::t('policy','Data not found by pinfl');
            } else {
                $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                _send_error("_getPassPersonalIDData - ".$title, json_encode($data,JSON_UNESCAPED_UNICODE));
                $response['ERROR'] = 422;
                $response['ERROR_MESSAGE'] = $title;
            }

        }

        return $response;
    }

    /**
     * @param $items
     * @return array|mixed|string|string[]
     * @throws BadRequestHttpException
     */
    public static function _getIsPensionerData($items=null)
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS || 1) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_IS_PENSIONER);

            $handBookService->setParams([
                'pinfl' => !empty($items['pinfl']) ? $items['pinfl'] : '',
                'passportSeries' => !empty($items['pass_series']) ? $items['pass_series'] : '',
                'passportNumber' => !empty($items['pass_number']) ? $items['pass_number'] : '',
            ]);

            $data = $handBookService->sendRequestIns();
            if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                $response = $data;
            } elseif (!empty($data['ERROR'])) {
                $response['ERROR'] = $data['ERROR'];
                $response['ERROR_MESSAGE'] = !empty($data['ERROR_MESSAGE']) ? $data['ERROR_MESSAGE'] : Yii::t('policy','Data not found by pinfl');
            } else {
                $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                _send_error("_getIsPensioner - ".$title, json_encode($data,JSON_UNESCAPED_UNICODE));
                $response['ERROR'] = 422;
                $response['ERROR_MESSAGE'] = $title;
            }

        }

        return $response;
    }

    /**
     * @param $items
     * @return array|mixed|string|string[]
     * @throws BadRequestHttpException
     */
    public static function _getProvidedDiscountsData($items=null)
    {
        $response = [];
        if (self::ENABLE_CALCULATE_INS || 1) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_PROVIDED_DISCOUNTS);

            $handBookService->setParams([
                'pinfl' => !empty($items['pinfl']) ? $items['pinfl'] : '',
                'govNumber' => !empty($items['govNumber']) ? $items['govNumber'] : '',
            ]);

            $data = $handBookService->sendRequestIns();
            if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                $response = $data;
            } elseif (!empty($data['ERROR'])) {
                $response['ERROR'] = $data['ERROR'];
                $response['ERROR_MESSAGE'] = !empty($data['ERROR_MESSAGE']) ? $data['ERROR_MESSAGE'] : Yii::t('policy','Data not found by pinfl');
            } else {
                $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                _send_error("_getProvidedDiscounts - ".$title, json_encode($data,JSON_UNESCAPED_UNICODE));
                $response['ERROR'] = 422;
                $response['ERROR_MESSAGE'] = $title;
            }

        }

        return $response;
    }


    /**
     *
     */
    public function confirmPayment()
    {
        $this->sendNewPolicyInfoMessage();
    }

    /**
     *
     */
    public function cancelPayment()
    {
//        $this->sendCancelPolicyInfoMessage();
    }

    /**
     *
     */
    public function sendNewPolicyInfoMessage()
    {
        $policy_number = !empty($this->fullPolicyNumber) ? $this->fullPolicyNumber : '<b>'.Yii::t('policy', 'Polis nomer topilmadi.').'</b>';
        $policy_link = !empty($this->uuid_fond) ? '<a href="http://polis.e-osgo.uz/site/export-to-pdf?id='.$this->uuid_fond.'">'.$policy_number.'</a>' : '<b>'.Yii::t('policy', 'Xatolik!!! Polis uuid berilmadi.').'</b>';
        $text = Yii::t('policy','E-Polis sotib olindi. Telefon: {phone}. Summasi: {amount_uzs} UZS. To`lov turi: {payment_type}. Polis: {policy}. Vaqti: {time}',[
            'phone' => '+'.clear_phone_full($this->app_phone),
            'amount_uzs' => number_format($this->amount_uzs,'0','.',' '),
            'payment_type' => !empty($this->policyOrder->payment_type) ? mb_strtoupper($this->policyOrder->payment_type) : '<b> topilmadi!!!</b>',
            'policy' => $policy_link,
            'time' => date('d.m.Y H:i', $this->created_at),
        ]);

        Yii::warning("\n\n$text\n");

        $res = sendTelegramData('sendMessage', [
            'chat_id' => $this->bot_group_chat_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ],BOT_TOKENT_SALE,'policy_buy_travel');

    }

    /**
     *
     */
    public function sendCancelPolicyInfoMessage()
    {
        $text = Yii::t('policy','❌ Polis to`lovi bekor qilindi. Telefon: {phone}. Summasi: {amount_uzs} UZS. To`lov turi: {payment_type}. Polis: {policy}',[
            'phone' => '+'.$this->app_phone,
            'amount_uzs' => number_format($this->amount_uzs,'0','.',' '),
            'payment_type' => !empty($this->policyOrder->payment_type) ? mb_strtoupper($this->policyOrder->payment_type) : 'topilmadi!!!',
            'policy' => !empty($this->fullPolicyNumber) ? '<a href="http://polis.e-osgo.uz/site/export-to-pdf?id='.$this->uuid_fond.'">'.$this->fullPolicyNumber.'</a>' : '<b>'.Yii::t('policy', 'Xatolik!!! Polis berilmadi.').'</b>',
        ]);

        Yii::warning("\n\n$text\n");

        $res = sendTelegramData('sendMessage', [
            'chat_id' => $this->bot_group_chat_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ],BOT_TOKENT_SALE,'policy_buy_travel');

    }

    /**
     * @return bool
     * @throws BadRequestHttpException
     */
    public function saveInsAnketa()
    {
        if (empty($this->ins_anketa_id) && empty($this->policy_number)) {
            $drivers = null;
            if (!empty($this->policyOsgoDrivers)) {
                foreach ($this->policyOsgoDrivers as $driver) {
                    $drivers[] = [
                        'surname' => $driver->last_name,
                        'name' => $driver->first_name,
                        'patronym' => $driver->middle_name,
                        'paspsery' => $driver->pass_sery,
                        'paspnumber' => $driver->pass_num,
                        'pinfl' => $driver->pinfl,
                        'datebirth' => date('d.m.Y',strtotime($driver->birthday)),
                        'licsery' => $driver->license_series,
                        'licnumber' => $driver->license_number,
                        'licdate' => date('d.m.Y',strtotime($driver->license_issue_date)),
                        'relative' => !empty($driver->relationship_id) ? $driver->relationship_id : 0,
                        'resident' => !empty($driver->resident_id) ? $driver->resident_id : PolicyOsgoDriver::DEFAULT_RESIDENT,
                    ];
                }
            } elseif (empty($this->policyOsgoDrivers) && $this->driver_limit_id == self::DRIVER_LIMITED) {
                $title = Yii::t('policy','Ҳайдовчилар тўлдирилмаган');
                throw new BadRequestHttpException($title);
            }
            $data = [
                'renumber' => $this->vehicle_gov_number,
                'texpsery' => $this->tech_pass_series,
                'texpnumber' => $this->tech_pass_number,
                'marka' => $this->vehicle_marka_id,
                'model' => $this->vehicle_marka_id,
                'vmodel' => $this->vehicle_model_name,
                'type' => $this->vehicle_type_id,
                'texpdate' => date('d.m.Y',strtotime($this->tech_pass_issue_date)),
                'year' => $this->vehicle_issue_year,
                'kuzov' => $this->vehicle_body_number,
                'dvigatel' => $this->vehicle_engine_number,
                'use_territory' => $this->region_id,
                'owner_fy' => $this->owner_fy,
                'owner_pinfl' => $this->owner_pinfl,
                'owner_birthdate' => date('d.m.Y',strtotime($this->owner_birthday)),
                'owner_pasp_sery' => $this->owner_pass_sery,
                'owner_pasp_num' => $this->owner_pass_num,
                'owner_surname' => $this->owner_last_name,
                'owner_name' => $this->owner_first_name,
                'owner_patronym' => $this->owner_middle_name,
                'owner_isdriver' => !empty($this->owner_is_driver) ? 1 : 0,
                'owner_oblast' => $this->owner_region,
                'owner_rayon' => $this->owner_district,
                'has_benefit' => $this->owner_is_pensioner,
                'owner_inn' => $this->owner_inn,
                'owner_orgname' => $this->owner_orgname,
                'applicant_isowner' => !empty($this->owner_is_applicant) ? 1 : 0,
                'owner_phone' => clear_phone_full($this->app_phone),
                'period' => $this->period_id,
                'driver_limit' => $this->driver_limit_id,
                'prem' => $this->amount_uzs,
                'opl_type' => (!empty($this->policyOrder->payment_type) && !empty(PaymentTransaction::getInsPaymentType($this->policyOrder->payment_type))) ? PaymentTransaction::getInsPaymentType($this->policyOrder->payment_type) : 1,
                'contract_begin' => date('d.m.Y',strtotime($this->start_date)),
                'is_renewal' => '',
                'old_polis' => '',
            ];
            if (empty($this->owner_is_applicant)) {
                $applicant_info = [
                    'appl_fizyur' => '0',
                    'appl_pinfl' => $this->app_pinfl,
                    'appl_birthdate' => date('d.m.Y',strtotime($this->app_birthday)),
                    'appl_pasp_sery' => $this->app_pass_sery,
                    'appl_pasp_num' => $this->app_pass_num,
                    'appl_surname' => $this->app_last_name,
                    'appl_name' => $this->app_first_name,
                    'appl_patronym' => $this->app_middle_name,
                    'appl_oblast' => $this->app_region,
                    'appl_rayon' => $this->app_district,
                    'appl_inn' => !empty($this->app_inn) ? $this->app_inn : '',
                    'appl_orgname' => !empty($this->appl_orgname) ? $this->appl_orgname : '',
                ];
                $data = array_merge($data,$applicant_info);
            }
            $data['drivers'] = $drivers;

            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethod(HandBookIns::METHOD_OSGO_POST_CREATE_ANKETA);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            if (!empty($data)) {
                $handBookService->setParams($data);
                $data = $handBookService->sendRequestIns();
                if (!empty($data['anketa_id']) && !empty($data['uuid'])) {
                    $response = $data;
                    $this->ins_anketa_id = $response['anketa_id'] ?: null;
                    $this->uuid_fond = $response['uuid'] ?: null;
                    if (!$this->save(false)) {
                        _send_error('Policy Osgo model saqlashda xatolik', json_encode(['error' => $this->errors], JSON_UNESCAPED_UNICODE));
                        if (LOG_DEBUG_SITE) {
                            $session = Yii::$app->session;
                            if (!$session->isActive) $session->open();
                            $session->addFlash('error', _generate_error($this->errors));
                        }
                    }
                    return true;
                } elseif (!empty($data['anketa_id'])) {
                    $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                    $titleLog = Yii::t('policy', 'sendRequestIns Anketa saqlashda xatolik uuid bosh qaytdi');
                    $this->_tmp_message = $title;
                    _send_error($titleLog, json_encode($data,JSON_UNESCAPED_UNICODE));
                    $session = Yii::$app->session;
                    if (!$session->isActive) $session->open();
                    $session->addFlash('error', $title);
//                    throw new BadRequestHttpException($title);

                } else {
                    $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                    $titleLog = Yii::t('policy', 'sendRequestIns Anketa saqlashda xatolik');
                    $this->_tmp_message = $title;
                    _send_error($titleLog, json_encode($data,JSON_UNESCAPED_UNICODE));

                    $session = Yii::$app->session;
                    if (!$session->isActive) $session->open();

                    if (!empty($data['error_text']) && !empty($data['error_code'])) {
                        $session->addFlash('error', $data['error_text']);
                    } else {
                        $session->addFlash('error', $title);
                    }
//                    throw new BadRequestHttpException($title);

                }
            } else {
//            $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
//            _send_error($title, $title);
//            throw new BadRequestHttpException($title);
            }
        }

        return false;
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }


    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (empty($this->policyOrder)) {
            $model = new PolicyOrder([
                'revision_id' => $this->primaryKey,
                'revision_model' => $this->formName(),
                'first_name' => $this->app_first_name,
                'last_name' => $this->app_last_name,
                'phone' => clear_phone_full($this->app_phone),
                'total_amount' => $this->amount_uzs,
            ]);
            if (!$model->save()) {
                $title = 'Policy osgo order save error';
                _send_error($title,json_encode(['error' => $model->errors],JSON_UNESCAPED_UNICODE));
                $title = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                throw new BadRequestHttpException($title);
            }
        }
        return true;
    }


    /**
     * Status
     */
    const STATUS_DELETED = -1;
    const STATUS_NEW = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_GIVEN = 2;
    const STATUS_BRACKED = 3;


}
