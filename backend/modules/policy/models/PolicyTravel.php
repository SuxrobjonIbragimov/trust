<?php

namespace backend\modules\policy\models;

use backend\models\page\SourceCounter;
use backend\modules\handbook\models\InsAgent;
use backend\modules\telegram\models\BotUser;
use common\components\behaviors\AuthorBehavior;
use common\library\payment\models\PaymentTransaction;
use common\models\Settings;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "{{%policy_travel}}".
 *
 * @property int $id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $days
 * @property string|null $policy_series
 * @property string|null $policy_number
 * @property float|null $amount_uzs
 * @property float|null $amount_usd
 * @property int|null $purpose_id
 * @property int|null $program_id
 * @property int|null $abroad_group
 * @property int|null $abroad_type_id
 * @property int|null $multi_days_id
 * @property bool|null $is_family
 * @property string|null $app_name
 * @property string|null $app_surname
 * @property string|null $app_birthday
 * @property string|null $app_pinfl
 * @property string|null $app_pass_sery
 * @property string|null $app_pass_num
 * @property string|null $app_phone
 * @property string|null $app_email
 * @property string|null $app_address
 * @property string|null $source
 * @property string|null $uuid_ins
 * @property int|null $ins_anketa_id
 * @property int|null $ins_policy_id
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property string $fullPolicyNumber
 * @property string $appFullName
 * @property string $bot_group_chat_id
 *
 * @property int $isFamily
 * @property int $_travelCountries
 * @property int $_traveller_birthday
 * @property int $_policy_price_uzs
 * @property int $_policy_price_usd
 * @property int $_ins_amount
 * @property int $_tmp_message
 * @property int $_travelCountriesList
 * @property int $_travelPurposesList
 * @property int $_travelProgramsList
 * @property boolean $is_shengen
 * @property int $bot_user_id
 * @property int $ins_agent_id
 *
 * @property PolicyOrder $policyOrder
 * @property SourceCounter $source0
 * @property BotUser $botUser
 * @property PolicyTravelAbroadType $abroadType
 * @property PolicyTravelMultiDays $multiDays
 * @property PolicyTravelProgram $program
 * @property PolicyTravelPurpose $purpose
 * @property User $createdBy
 * @property User $updatedBy
 * @property PolicyTravelToCountry[] $policyTravelToCountries
 * @property PolicyTravelTraveller[] $policyTravelTravellers
 */
class PolicyTravel extends \yii\db\ActiveRecord
{

    const SCENARIO_SITE_STEP_CALC = 'site_step_calc';
    const SCENARIO_SITE_STEP_FORM = 'site_step_form';

    const ABROAD_TYPE_ONE = 1;
    const ABROAD_TYPE_MULTI = 2;

    const AGENT_PERCENT = 75;
    const INS_USER_ID_TRAVEL_ONLINE_STTE = 2;

    const TRAVELLER_PARENT_LIMIT = 2;
    const TRAVELLER_LIMIT = 6;
    const TRAVELLER_CHILD_LIMIT = 3;

    const CEIL_PRICE_TO = 1;

    public $bot_group_chat_id = '-885619457';
    public $_travelCountries;
    public $_travelCountriesList;
    public $_travelPurposesList;
    public $_travelProgramsList;
    public $_traveller_birthday;

    public $_policy_price_uzs;
    public $_policy_price_usd;
    public $_ins_amount;
    public $_tmp_message;
    public $offer;

    public $covid_has = false;
    public $also_traveller = false;
    public $is_shengen = false;
    public $programs_info = [];

    public $country_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_travel}}';
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
        // maximum birthday
        $max = date('d.m.Y',strtotime("-18 year", time()));
        $begin_Date = ($this->isNewRecord) ? date('d.m.Y') : date('d.m.Y', strtotime($this->start_date));
        return [
            [['_travelCountries', 'start_date', 'end_date', 'days',  'purpose_id', 'program_id', ], 'required', 'on' => self::SCENARIO_SITE_STEP_CALC, ],
            [['app_name', 'app_surname', 'app_pass_sery', 'app_pass_num', 'app_birthday', 'app_phone', 'app_address', ], 'required', 'on' => self::SCENARIO_SITE_STEP_CALC, 'message' => Yii::t('policy', 'Необходимо заполнить')],
            [['start_date', 'end_date', 'app_birthday', 'uuid_ins'], 'safe'],
            [['start_date', ], 'date', 'format' => 'dd.MM.yyyy', 'min' => $begin_Date, 'tooSmall' => Yii::t('policy', 'Дата начала страхования не соответствует требованиям')],
            [['end_date', ], 'date', 'format' => 'dd.MM.yyyy', 'min' => date('d.m.Y',strtotime($this->start_date)), 'tooSmall' => Yii::t('policy', 'Дата окончания страхования должна быть равна или позже даты начала страхования')],
            [['app_birthday', ], 'date', 'format' => 'dd.MM.yyyy', 'max' => $max, 'tooBig' => Yii::t('policy', 'The insurer must be at least 18 years old')],
            [['days', 'purpose_id', 'program_id', 'abroad_group', 'abroad_type_id', 'multi_days_id', 'ins_anketa_id', 'ins_policy_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['days', 'purpose_id', 'program_id', 'abroad_group', 'abroad_type_id', 'multi_days_id', 'ins_anketa_id', 'ins_policy_id', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['amount_uzs', 'amount_usd'], 'number'],
            [['app_phone'], 'match', 'pattern' => '/^\+998 \((\d{2})\) (\d{3})-(\d{2})-(\d{2})$/',
                'on' => self::SCENARIO_SITE_STEP_CALC, 'message' => Yii::t('validation','Неверный формат телефона')
            ],
            [['multi_days_id', ], 'required', 'message' => Yii::t('validation','Необходимо заполнить'),
                'on' => self::SCENARIO_SITE_STEP_CALC, 'when' => function ($model) {
                return ($model->abroad_type_id == self::ABROAD_TYPE_MULTI);
            }, 'whenClient' => "function (attribute, value) {
                        return ($('#policytravel-abroad_type_id').val() == 2);
                    }"
            ],
            [['end_date', 'days', ], 'required', 'message' => Yii::t('validation','Необходимо заполнить'),
                'on' => self::SCENARIO_SITE_STEP_CALC, 'when' => function ($model) {
                return ($model->abroad_type_id == self::ABROAD_TYPE_ONE);
            }, 'whenClient' => "function (attribute, value) {
                        return ($('#policytravel-abroad_type_id').val() == 1);
                    }"
            ],
            [['is_family', 'covid_has', 'programs_info', 'source', 'bot_user_id'], 'safe',  'on' => self::SCENARIO_SITE_STEP_CALC, ],
            [['is_family', 'covid_has', 'programs_info', 'source', 'bot_user_id'], 'safe',  'on' => self::SCENARIO_SITE_STEP_FORM, ],
            [['_policy_price_uzs', '_policy_price_usd', '_ins_amount', '_tmp_message'], 'safe'],
            [['_policy_price_uzs', '_policy_price_usd', '_ins_amount'], 'default', 'value' => 0],
            [['abroad_group', ], 'default', 'value' => ($this->is_family) ? 1 : 0],
            [['also_traveller', ], 'default', 'value' => true],
            [['abroad_type_id', ], 'default', 'value' => self::ABROAD_TYPE_ONE],
            [['is_family'], 'boolean'],
            [['country_ids'], 'safe'],
            [['source', 'ins_agent_id'], 'safe'],
//            [['purpose_id'], 'in', 'range' => $this->getPurposesList()],
//            [['program_id'], 'in', 'range' => $this->getProgramsList()],
            [['policy_series', 'policy_number', 'app_name', 'app_pinfl', 'app_pass_sery', 'app_pass_num', 'app_phone', 'app_email', 'app_address', ], 'string', 'max' => 255],
            [['abroad_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelAbroadType::className(), 'targetAttribute' => ['abroad_type_id' => 'id']],
            [['multi_days_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelMultiDays::className(), 'targetAttribute' => ['multi_days_id' => 'id']],
            [['program_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelProgram::className(), 'targetAttribute' => ['program_id' => 'id']],
            [['purpose_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyTravelPurpose::className(), 'targetAttribute' => ['purpose_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if ($this->is_family) {
                $this->abroad_group = 1;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy', 'ID'),
            '_travelCountries' => Yii::t('policy', 'Travel Countries'),
            'start_date' => Yii::t('policy', 'Start Date'),
            'end_date' => Yii::t('policy', 'End Date'),
            'days' => Yii::t('policy', 'Days'),
            'policy_series' => Yii::t('policy', 'Policy Series'),
            'policy_number' => Yii::t('policy', 'Policy Number'),
            'amount_uzs' => Yii::t('policy', 'Amount Uzs'),
            'amount_usd' => Yii::t('policy', 'Amount Usd'),
            'purpose_id' => Yii::t('policy', 'Purpose ID'),
            'program_id' => Yii::t('policy', 'Program ID'),
            'abroad_group' => Yii::t('policy', 'Abroad Group'),
            'abroad_type_id' => Yii::t('policy', 'Abroad Type'),
            'multi_days_id' => Yii::t('policy', 'Multi days'),
            'is_family' => Yii::t('policy', 'Traveling with family'),
            'app_name' => Yii::t('policy', 'App Name'),
            'app_surname' => Yii::t('policy', 'App Surname'),
            'app_birthday' => Yii::t('policy', 'App Birthday'),
            'app_pinfl' => Yii::t('policy', 'App Pinfl'),
            'app_pass_sery' => Yii::t('policy', 'App Pass Sery'),
            'app_pass_num' => Yii::t('policy', 'App Pass Num'),
            'app_phone' => Yii::t('policy', 'App Phone'),
            'app_email' => Yii::t('policy', 'App Email'),
            'app_address' => Yii::t('policy', 'App Address'),
            'source' => Yii::t('policy', 'Source'),
            'bot_user_id' => Yii::t('policy', 'Telegram user'),
            'uuid_ins' => Yii::t('policy', 'Uuid Ins'),
            'ins_anketa_id' => Yii::t('policy', 'Ins Anketa ID'),
            'ins_policy_id' => Yii::t('policy', 'Ins Policy ID'),
            'status' => Yii::t('policy', 'Status'),
            'offer' => Yii::t('policy', 'Я прочитал и принимаю'),
            'also_traveller' => Yii::t('policy', 'I am also traveller'),
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
        $phone_def = '+998';
        if (!empty(Yii::$app->session->get('b_u_id'))) {
            $bot_user_id = Yii::$app->session->get('b_u_id');
            $botModel = BotUser::findOne($bot_user_id);
            if (!empty($botModel->phone)) {
                $phone_def = '+'.clear_phone_full($botModel->phone);
            }
        }
        $this->abroad_type_id = self::ABROAD_TYPE_ONE;
        $this->also_traveller = 1;
        $this->app_phone = (!Yii::$app->user->isGuest && is_numeric(clear_phone_full(Yii::$app->user->identity['username']))) ? Yii::$app->user->identity['username'] : $phone_def;
    }

    /**
     * @return bool
     */
    public function isMulti()
    {
        return ($this->abroad_type_id == self::ABROAD_TYPE_MULTI);
    }

    /**
     * @return void
     */
    public function setDays()
    {
        if ($this->isMulti() && !empty($this->multi_days_id)) {
            $revisionModel = PolicyTravelMultiDays::findOne($this->multi_days_id);
            $this->days = !empty($revisionModel->days) ? $revisionModel->days : $this->days;
        }
    }

    /**
     * Status
     */
    const STATUS_DELETED = -1;
    const STATUS_NEW = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_GIVEN = 2;
    const STATUS_BRACKED = 3;

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getStatusArray($status = null)
    {
        $array = [
            self::STATUS_NEW => Yii::t('policy', 'New'),
            self::STATUS_GIVEN => Yii::t('policy', 'Given'),
            self::STATUS_BRACKED => Yii::t('policy', 'Bracked'),
        ];
        if(Yii::$app->user->can('administrator')) {
            $array[self::STATUS_DELETED] = Yii::t('policy', 'Deleted');
        }

        return $status === null ? $array : $array[$status];
    }

    /**
     * Status Name
     * @return string
     */
    public function getStatusName()
    {
        $array = [
            self::STATUS_NEW => '<span class="text-bold text-warning">' . self::getStatusArray(self::STATUS_NEW) . '</span>',
            self::STATUS_GIVEN => '<span class="text-bold text-green">' . self::getStatusArray(self::STATUS_GIVEN) . '</span>',
            self::STATUS_BRACKED => '<span class="text-bold text-red">' . self::getStatusArray(self::STATUS_BRACKED) . '</span>',
        ];

        if(Yii::$app->user->can('administrator')) {
            $array[self::STATUS_DELETED] = '<span class="text-bold text-red">' . self::getStatusArray(self::STATUS_DELETED) . '</span>';
        }

        return isset($array[$this->status]) ? $array[$this->status] : $this->status;
    }

    /**
     * @return string
     */
    public function getAppFullName()
    {
        return $this->app_surname . ' ' . $this->app_name;
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
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyOrder()
    {
        return $this->hasOne(PolicyOrder::className(), ['revision_id' => 'id'])->onCondition(['revision_model' => $this->formName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBotUser()
    {
        return $this->hasOne(BotUser::className(), ['id' => 'bot_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource0()
    {
        return $this->hasOne(SourceCounter::className(), ['id' => 'source']);
    }

    /**
     * Gets query for [[Program]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProgram()
    {
        return $this->hasOne(PolicyTravelProgram::className(), ['id' => 'program_id']);
    }

    /**
     * Gets query for [[AbroadType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbroadType()
    {
        return $this->hasOne(PolicyTravelAbroadType::className(), ['id' => 'abroad_type_id']);
    }

    /**
     * Gets query for [[MultiDays]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMultiDays()
    {
        return $this->hasOne(PolicyTravelMultiDays::className(), ['id' => 'multi_days_id']);
    }

    /**
     * Gets query for [[Purpose]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPurpose()
    {
        return $this->hasOne(PolicyTravelPurpose::className(), ['id' => 'purpose_id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[PolicyTravelToCountries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelToCountries()
    {
        return $this->hasMany(PolicyTravelToCountry::className(), ['policy_travel_id' => 'id']);
    }

    /**
     * Gets query for [[PolicyTravelTravellers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyTravelTravellers()
    {
        return $this->hasMany(PolicyTravelTraveller::className(), ['policy_travel_id' => 'id'])->orderBy(['id' => SORT_ASC]);
    }


    public function setEndDate()
    {
        if (!empty($this->start_date)) {
            $days = ($this->days-1);
            $this->end_date = date('d.m.Y', strtotime("+{$days} days", strtotime($this->start_date)));
            if ($this->is_shengen) {
                $this->end_date = date('d.m.Y', strtotime("+15 days", strtotime($this->end_date)));
            } else {
                $this->end_date = date('d.m.Y', strtotime($this->end_date));
            }
        }
    }


    /**
     * @param null $item
     * @return array
     * @throws BadRequestHttpException
     */
    public function getCountriesList ($item=null, $lang = null)
    {
        $response = HandbookCountry::_getItemsList();
        if (empty($response)) {
            $this->program_id = null;
            $title = Yii::t('error', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            _send_error($title, json_encode($response,JSON_UNESCAPED_UNICODE));
            throw new BadRequestHttpException($title);
        }

        return $response;
    }

    /**
     * @param null $item
     * @return array
     * @throws BadRequestHttpException
     */
    public function getAbroadTypeList ($item=null)
    {
        $response = PolicyTravelAbroadType::_getItemsList();
        if (empty($response)) {
            $this->program_id = null;
            $title = Yii::t('error', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            _send_error($title, json_encode($response,JSON_UNESCAPED_UNICODE));
            throw new BadRequestHttpException($title);
        }

        return $response;
    }


    /**
     * @param null $item
     * @return array
     * @throws BadRequestHttpException
     */
    public function getMultiDaysList ($item=null)
    {
        $response = PolicyTravelMultiDays::_getItemsList();
        if (empty($response)) {
            $this->program_id = null;
            $title = Yii::t('error', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            _send_error($title, json_encode($response,JSON_UNESCAPED_UNICODE));
            throw new BadRequestHttpException($title);
        }

        return $response;
    }

    /**
     * @param null $item
     * @return array
     * @throws BadRequestHttpException
     */
    public function getPurposesList ($item=null)
    {
        $response = PolicyTravelPurpose::_getItemsList();
        if (empty($response)) {
            $this->program_id = null;
            $title = Yii::t('error', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            _send_error($title, json_encode($response,JSON_UNESCAPED_UNICODE));
            throw new BadRequestHttpException($title);
        }

        return $response;
    }

    /**
     * @param null $item
     * @return array
     * @throws BadRequestHttpException
     */
    public function getProgramsList ($item=null, $covid =false)
    {
        $country_ids = $this->_travelCountries;

        $countries = HandbookCountry::find()->where(['id' => $country_ids])->asArray()->all();

        $c_ids = array_unique(array_column($countries, 'id'));

        $pr_to_countries = PolicyTravelProgramToCountry::find()->select('policy_travel_program_id')->where(['country_id' => $c_ids])->asArray()->all();

        $p_ids = array_unique(array_column($pr_to_countries, 'policy_travel_program_id'));

        $program_ids = [];

        foreach($p_ids as $pr) {
            $av = true;
            foreach($c_ids as $par) {
                if(is_null(PolicyTravelProgramToCountry::find()->where(['policy_travel_program_id' => $pr, 'country_id' => $par])->one()))
                    $av = false;
            }
            if($av) $program_ids[] = $pr;
        }

        $response = PolicyTravelProgram::_getItemsList($program_ids);
        if (empty($response)) {
            $this->program_id = null;
            $title = Yii::t('error', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            _send_error($title, json_encode($response,JSON_UNESCAPED_UNICODE));
            throw new BadRequestHttpException($title);
        }

        return $response;
    }

    /**
     * @return array|mixed
     * @throws BadRequestHttpException
     */
    public function calculateFullPrice()
    {
        $response = [];
        if (!empty($this->days) && !empty($this->program_id) && !is_null($this->purpose_id) && !empty($this->_traveller_birthday)) {
            $this->abroad_group = $this->is_family ? 1 : 0;

            $begin_date = $this->start_date;
            $end_date = $this->end_date;
            $purpose_id = $this->purpose_id;
            $program_id = $this->program_id;
            $isFamily = $this->is_family;

            $days = abs(round((strtotime($begin_date) - strtotime($end_date)) / 86400)) + 1;

            $purpose = PolicyTravelPurpose::findOne($purpose_id);
            $program = PolicyTravelProgram::findOne($program_id);
            $abroad_type = PolicyTravelAbroadType::findOne($this->abroad_type_id);
            $multi = null;
            if ($this->isMulti() && !empty($this->multi_days_id)) {
                $multi = PolicyTravelMultiDays::findOne([$this->multi_days_id]);
            }

            $handBookService = new HandBookIns();
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_POST_TRAVEL_CALCULATE_FULL_PRICE);
            $handBookService->setParams([
                'day' => $this->days,
                'program_id' => !empty($program->id) ? $program->ins_id : null,
                'activity_id' => !empty($purpose->id) ? $purpose->ins_id : null,
                'group_id' => $this->abroad_group,
                'type_id' => !empty($abroad_type->id) ? $abroad_type->ins_id : null,
                'multi_id' => !empty($multi->id) ? $multi->ins_id : 0,
                'date_reg' => date('d.m.Y'),
                'date_births' => $this->_traveller_birthday,
            ]);

            $data = $handBookService->sendRequestIns();
            if (!empty($data['COST_USD'])) {
                $this->_policy_price_uzs = floatval($data['COST_UZS']);
                $this->_policy_price_usd = floatval($data['COST_USD']);
            } else {
                $this->_policy_price_usd = 0;
                $this->_policy_price_uzs = 0;
            }


        } else {
            $this->_policy_price_usd = 0;
            $this->_policy_price_uzs = 0;
        }

        return $response;
    }


    /**
     *
     */
    public function confirmPayment()
    {
//        $this->saveInsAnketa();
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
        $base_url = EBASE_URL_INS;
        $policy_link = !empty($this->ins_policy_id) ? '<a href="https://api.ksc.uz/p/'.$this->ins_policy_id.'/'.$this->ins_anketa_id.'">'.$policy_number.'</a>' : '<b>'.Yii::t('policy', 'Xatolik!!! Polis uuid berilmadi.').'</b>';
        $text = Yii::t('policy',"✈️ E-TRAVEL sotib olindi\n📞 {phone}\n💰 {amount_uzs} UZS\n💳 {payment_type}\n🆔 {id}\n📃 {policy}\n🕓 {time}",[
            'phone' => mask_to_phone_format($this->app_phone),
            'amount_uzs' => number_format($this->amount_uzs,'0','.',' '),
            'payment_type' => !empty($this->policyOrder->payment_type) ? mb_strtoupper($this->policyOrder->payment_type) : '<b> topilmadi!!!</b>',
            'policy' => $policy_link,
            'id' => $this->ins_anketa_id,
            'time' => date('d.m.Y H:i', $this->created_at),
        ]);

        if (!empty($this->botUser)) {
            $bot_token = !empty(Yii::$app->params['tg.botTokenRobot']) ? Yii::$app->params['tg.botTokenRobot'] : null;

            $res = sendTelegramData('sendMessage', [
                'chat_id' => $this->botUser->t_id,
                'text' => $text,
                'parse_mode' => 'HTML'
            ],$bot_token,'policy_buy_osgo_bot_user');
            $_name = !empty($this->botUser->first_name) ? trim($this->botUser->first_name) : $this->botUser->t_id;
            $t_id = $this->botUser->t_id;
            $t_link = "<a href='tg://user?id={$t_id}'>{$_name}</a>";
            if (!empty($this->botUser->phone)) {
                $phone = $this->botUser->phone;
                $t_link = "<a href='https://t.me/+{$phone}'>{$_name}</a>";
            }
            $full_str = !empty($this->botUser->t_username) ? "@".$this->botUser->t_username : $t_link;
            $text .= "\n🌐 {$full_str}";
        }

        /*if (!empty($this->ins_agent_id) && ($insAgentModel = InsAgent::findOne(['id' => $this->ins_agent_id])) != null) {
            $source_name = $insAgentModel->user_id;
            $text .= "\n🆔 Agent: {$source_name}";
        } else*/if (!empty($this->source0)) {
            $source_name=$this->source0->name;
            $text .= "\n📲  {$source_name}";
        }


        Yii::warning("\n\n$text\n");

        $res = sendTelegramData('sendMessage', [
            'chat_id' => $this->bot_group_chat_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ],BOT_TOKENT_SALE,'policy_buy_travel');

        if (!empty($this->ins_agent_id)) {
            if (!empty($this->ins_agent_id) && ($insAgentModel = InsAgent::findOne(['id' => $this->ins_agent_id])) != null) {
                $source_name = $insAgentModel->user_id;
                $text .= "\n🆔 Agent: {$source_name}";
            }
            $chat_id = $this->bot_group_chat_id_me;
            $res = sendTelegramData('sendMessage', [
                'chat_id' => $chat_id,
                'text' => $text,
                'parse_mode' => 'HTML'
            ],BOT_TOKENT_SALE,'policy_buy_osgo');
        }

    }

    /**
     *
     */
    public function sendCancelPolicyInfoMessage()
    {
        $text = Yii::t('policy','❌ Polis to`lovi bekor qilindi. Telefon: {phone}. Summasi: {amount_uzs} UZS. To`lov turi: {payment_type}. Polis: {policy}',[
            'phone' => '+'.$this->app_phone,
            'amount_uzs' => number_format($this->amount_uzs,'0','.',' '),
            'payment_type' => !empty($this->policyOrder->paymentTransaction->type) ? mb_strtoupper($this->policyOrder->paymentTransaction->type) : 'topilmadi!!!',
            'policy' => !empty($this->fullPolicyNumber) ? '<a href="'.Yii::$app->urlManager->createAbsoluteUrl(['/policy/default/download','h' => _model_encrypt($this)]).'">'.$this->fullPolicyNumber.'</a>' : '<b>'.Yii::t('policy', 'Xatolik!!! Polis berilmadi.').'</b>',
        ]);

        Yii::warning("\n\n$text\n");

        $res = sendTelegramData('sendMessage', [
            'chat_id' => $this->bot_group_chat_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ],BOT_TOKENT_SALE,'policy_buy_travel');

    }

    /**
     * @param int $user_id
     * @return bool
     * @throws BadRequestHttpException
     */
    public function saveInsAnketa($user_id = self::INS_USER_ID_TRAVEL_ONLINE_STTE)
    {
        if (empty($this->ins_policy_id) && empty($this->ins_anketa_id) && empty($this->policy_number)) {
            $insured = null;
            if (!empty($this->policyTravelTravellers)) {
                foreach ($this->policyTravelTravellers as $traveller) {
                    $insured[] = [
                        'last_name' => $traveller->surname,
                        'first_name' => $traveller->first_name,
                        'pass_sery' => $traveller->pass_sery,
                        'pass_num' => $traveller->pass_num,
                        'date_birth' => date('d.m.Y',strtotime($traveller->birthday)),
                        'pinfl' => $traveller->pinfl,
                    ];
                }
            } else {
                $title = Yii::t('policy','Саёҳатчилар тўлдирилмаган');
                throw new BadRequestHttpException($title);
            }
            if ($this->days>1) {
                $day = $this->days-1;
                if ($day>1) {
                    $end_date = date('d.m.Y',strtotime("+{$day} days",strtotime($this->start_date)));
                } else {
                    $end_date = date('d.m.Y',strtotime("+{$day} day",strtotime($this->start_date)));
                }
            } else {
                $end_date = date('d.m.Y',strtotime($this->start_date));
            }

            $purpose = PolicyTravelPurpose::findOne($this->purpose_id);
            $program = PolicyTravelProgram::findOne($this->program_id);
            $abroad_type = PolicyTravelAbroadType::findOne($this->abroad_type_id);
            $multi = null;
            if ($this->isMulti() && !empty($this->multi_days_id)) {
                $multi = PolicyTravelMultiDays::findOne([$this->multi_days_id]);
            }

            $countries = [];
            if (!empty($this->policyTravelToCountries)) {
                foreach ($this->policyTravelToCountries as $trcountrymodel) {
                    $countries[] = $trcountrymodel->country->ins_id;
                }
            }
            $data = [
                'date_reg' => date('d.m.Y',$this->created_at),
                'start_date' => date('d.m.Y',strtotime($this->start_date)),
                'end_date' => $end_date,
                'days' => intval($this->days),
                'program_id' => !empty($program->id) ? $program->ins_id : null,
                'activity_id' => !empty($purpose->id) ? $purpose->ins_id : null,
                'type_id' => !empty($abroad_type->id) ? $abroad_type->ins_id : null,
                'multi_id' => !empty($multi->id) ? $multi->ins_id : 0,
                'group_id' => $this->abroad_group,
                'applicant' => [
                    'first_name' => $this->app_name,
                    'last_name' => $this->app_surname,
                    'date_birth' => date('d.m.Y',strtotime($this->app_birthday)),
                    'pass_sery' => $this->app_pass_sery,
                    'pass_num' => $this->app_pass_num,
                    'address' => $this->app_address,
                    'phone' => $this->app_phone,
                    'pinfl' => $this->app_pinfl,
                    'fizyur' => '0',
                    'inn' => '',
                    'org_name' => '',
                ],
                'countries' => $countries,
                'insured' => $insured,
            ];

            $handBookService = new HandBookIns();
            $handBookService->setMethod(HandBookIns::METHOD_POST_TRAVEL_SAVE);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);

            if (!empty($this->ins_agent_id) && (!empty(Settings::getValueByKey(Settings::KEY_REFERRAL_AGENT)))) {
                $insAgentModel = InsAgent::findOne(['id' => $this->ins_agent_id]);
                if (!empty($insAgentModel->token)) {
                    $handBookService->setBearer(trim($insAgentModel->token));
                }
            }

            if (!empty($data)) {
                $handBookService->setParams($data);
                $data = $handBookService->sendRequestIns();
                if (!empty($data['result']) && !empty($data['result_message']) && ($data['result_message'] == 'Authorization incorrect')) {
                    $this->ins_agent_id = 63;
                    $insAgentModel = InsAgent::findOne(['id' => $this->ins_agent_id]);
                    if (!empty($insAgentModel->token)) {
                        $handBookService->setBearer(trim($insAgentModel->token));
                    } else {
                        $handBookService->setBearer(null);
                    }
                    $data = $handBookService->sendRequestIns();
                }
                if (!empty($data['anketa_id'])) {
                    $response = $data;
                    $this->ins_anketa_id = $response['anketa_id'] ?: null;
                    $this->uuid_ins = $response['uuid'] ?: null;
                    $this->amount_usd = !empty($response['premium_usd']) ? floatval($response['premium_usd']) : 0;
                    $this->amount_uzs = !empty($response['premium_uzs']) ? floatval($response['premium_uzs']) : 0;
                    if (!$this->save(false)) {
                        _send_error('Policy Travel model saqlashda xatolik', json_encode(['error' => $this->errors], JSON_UNESCAPED_UNICODE));
                        if (LOG_DEBUG_SITE) {
                            $session = Yii::$app->session;
                            if (!$session->isActive) $session->open();
                            $session->addFlash('error', $this->errors);
                        }
                    }
                    return true;
                } else {
                    $title = Yii::t('policy','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                    $titleLog = 'sendRequestIns Anketa saqlashda xatolik';
                    $this->_tmp_message = $title;
                    _send_error($titleLog, json_encode($data,JSON_UNESCAPED_UNICODE));
//                    throw new BadRequestHttpException($title);

                }
            }
        }

        return false;
    }


    /**
     * @param $items
     * @return array|mixed|string|string[]
     * @throws BadRequestHttpException
     */
    public static function _getPassBirthdayData($items=null)
    {
        $response = ['ERROR' => 0];
        if (1) {
            $handBookService = new HandBookIns();
            $handBookService->setBaseUrl(EBASE_URL_INS);
            $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
            $handBookService->setMethod(HandBookIns::METHOD_POST_TRAVEL_PROVIDER_PASSPORT);

            $handBookService->setParams([
                'birthDate' => !empty($items['birthday']) ? $items['birthday'] : '',
                'passportSeries' => !empty($items['pass_series']) ? $items['pass_series'] : '',
                'passportNumber' => !empty($items['pass_number']) ? $items['pass_number'] : '',
            ]);

            $data = $handBookService->sendRequestIns();
            if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                $response = $data;
                $response['ERROR'] = 0;
                $response['ADDRESS'] = !empty($response['ADDRESS']) ? $response['ADDRESS'] : null;
                $response['PINFL'] = null;
                $response['FULL_NAME_TMP'] = !empty($response['LAST_NAME']) ? $response['LAST_NAME'] : '';
                $response['FULL_NAME_TMP'] .= !empty($response['FIRST_NAME']) ? " ".$response['FIRST_NAME'] : '';
                $response['FULL_NAME_TMP'] .= !empty($response['MIDDLE_NAME']) ? " ".$response['MIDDLE_NAME'] : '';
                if (!empty($data['PINFL'])) {
                    $response['PINFL'] = trim($data['PINFL']);
                    $items['pinfl'] = trim($data['PINFL']);
                }

            } else {
                $data = $handBookService->sendRequestIns();

                if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                    $response = $data;
                    $response['ERROR'] = 0;
                    $response['ADDRESS'] = !empty($response['ADDRESS']) ? $response['ADDRESS'] : null;
                    $response['PINFL'] = null;
                    $response['FULL_NAME_TMP'] = !empty($response['LAST_NAME']) ? $response['LAST_NAME'] : '';
                    $response['FULL_NAME_TMP'] .= !empty($response['FIRST_NAME']) ? " ".$response['FIRST_NAME'] : '';
                    $response['FULL_NAME_TMP'] .= !empty($response['MIDDLE_NAME']) ? " ".$response['MIDDLE_NAME'] : '';
                    if (!empty($data['PINFL'])) {
                        $response['PINFL'] = trim($data['PINFL']);
                        $items['pinfl'] = trim($data['PINFL']);
                    }

                } else {
                    $data = $handBookService->sendRequestIns();

                    if (!empty($data) && is_array($data) && empty($data['ERROR'])) {
                        $response = $data;
                        $response['ERROR'] = 0;
                        $response['ADDRESS'] = !empty($response['ADDRESS']) ? $response['ADDRESS'] : null;
                        $response['PINFL'] = null;
                        $response['FULL_NAME_TMP'] = !empty($response['LAST_NAME']) ? $response['LAST_NAME'] : '';
                        $response['FULL_NAME_TMP'] .= !empty($response['FIRST_NAME']) ? " ".$response['FIRST_NAME'] : '';
                        $response['FULL_NAME_TMP'] .= !empty($response['MIDDLE_NAME']) ? " ".$response['MIDDLE_NAME'] : '';
                        if (!empty($data['PINFL'])) {
                            $response['PINFL'] = trim($data['PINFL']);
                            $items['pinfl'] = trim($data['PINFL']);
                        }

                    } else {
                        $response = $data;
                    }
                }
            }

        }

        return $response;
    }



    public static function _getProgramInfoLabels()
    {
        return [
            'medex' => Yii::t('policy', 'Медицинские услуги:'),
            'covid' => Yii::t('policy', 'В том числе COVID-19:'),
            'evacuation' => Yii::t('policy', 'Evacuation:'),
            'transport' => Yii::t('policy', 'Медико-транспортные услуги:'),
            'accident' => Yii::t('policy', 'Несчастный случай:'),
            'compensation' => Yii::t('policy', 'Compensation:'),
            'total' => Yii::t('policy', 'Итого:'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $session = Yii::$app->session;
        if (!empty($session['source_promo_id']['number'])) {
            $this->ins_agent_id = $session['source_promo_id']['number'];
        } else {
            $cookies = Yii::$app->request->cookies;
            if (($cookie = $cookies->get('source_promo_id')) !== null) {
                $this->ins_agent_id = $cookie->value;
            }
        }
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
                'first_name' => $this->app_name,
                'phone' => clear_phone_full($this->app_phone),
                'total_amount' => $this->amount_uzs,
            ]);
            if (!$model->save()) {
                $title = 'Policy travel order save error';
                _send_error($title,json_encode(['error' => $model->errors],JSON_UNESCAPED_UNICODE));
                $title = Yii::t('error', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
                throw new BadRequestHttpException($title);
            }
        }
        return true;
    }



}
