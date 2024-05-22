<?php

namespace backend\modules\telegram\models;

use backend\models\page\SourceCounter;
use backend\modules\policy\models\PolicyOsgo;
use backend\modules\telegram\controllers\HandlerController;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%bot_user}}".
 *
 * @property int $id
 * @property int|null $t_id
 * @property bool|null $is_bot
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $t_username
 * @property string|null $phone
 * @property string|null $language_code
 * @property string|null $callback_data
 * @property string|null $current_product
 * @property string|null $current_step_type
 * @property string|null $current_step_val
 * @property int|null $message_id_l
 * @property int|null $message_id_d
 * @property int|null $message_id_e
 * @property int|null $is_premium
 * @property string $source
 * @property string|null $info
 * @property bool|null $is_admin
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $ins_agent_id
 *
 * @property int|null $base_url
 *
 * @property BotUserToUser[] $botUserToContractors
 * @property BotUserLog[] $botUserLogs
 * @property PolicyOsgo[] $policyOsgos
 * @property PolicyOsgo $activePolicyOsgo
 * @property SourceCounter $source0
 * @property User[] $users
 */
class BotUser extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = -1;

    const PARAM_LANGUAGE = 'language';
    const PARAM_POLICY = 'policy';
    const PARAM_PRODUCT = 'product';

    const PREFIX_ICON_HOME = '🏠';
    const PREFIX_ICON_NEW_POLICY = '➕';
    const PREFIX_ICON_SETTINGS = '⚙';
    const PREFIX_ICON_MY_POLICY = '💾';

    const PRODUCT_TYPE_OSGO = 'osgo';
    const PRODUCT_TYPE_TRAVEL = 'travel';
    const PRODUCT_TYPE_ACCIDENT = 'accident';
    const PRODUCT_TYPE_DOCUMENT = 'document';
    const PRODUCT_TYPE_SPORT = 'sport';

    const PRODUCT_TYPE_OSGO_STEP_VEHICLE = 'step_vehicle';
    const PRODUCT_TYPE_OSGO_STEP_APPLICANT = 'step_applicant';
    const CALL_BACK_DATA_APPROVE = 'approve';
    const CALL_BACK_DATA_CANCEL = 'cancel';
    const CALL_BACK_DATA_EDIT = 'edit';

    const DEFAULT_PRODUCT_TYPE = self::PRODUCT_TYPE_OSGO;

    const STEP_TYPE_CHOOSE_LANGUAGE = 'choose_language';
    const STEP_TYPE_PHONE = 'phone';
    const STEP_TYPE_CHOOSE_PRODUCT = 'choose_product';
    const STEP_TYPE_POLICY_OSGO_VEHICLE_GOV_NUMBER = 'vehicle_gov_number';
    const STEP_TYPE_POLICY_OSGO_TECH_PASS = 'tech_pass';
    const STEP_TYPE_POLICY_OSGO_CHOOSE_APPLICANT = 'choose_applicant';
    const STEP_TYPE_POLICY_OSGO_APPLICANT = 'applicant';
    const STEP_TYPE_POLICY_OSGO_CHOOSE_DRIVER_LIMIT = 'choose_driver_limit';
    const STEP_TYPE_POLICY_OSGO_OWNER_IS_DRIVER = 'owner_is_driver';
    const STEP_TYPE_POLICY_OSGO_ADD_DRIVERS = 'add_driver';
    const STEP_TYPE_POLICY_OSGO_ADD_DRIVER_PASSPORT = 'add_driver_passport';
    const STEP_TYPE_POLICY_OSGO_ADD_DRIVER_BIRTHDAY = 'add_driver_birthday';
    const STEP_TYPE_POLICY_OSGO_ADD_DRIVER_LICENSE = 'add_driver_license';

    const PHONE_NUMBER_LENGTH = 15;
    const GOV_NUMBER_LENGTH = 10;
    const TECH_PASS_LENGTH = 12;
    const PINFL_LENGTH = 14;
    const PASS_SERIES_NUM_LENGTH = 10;
    const BIRTHDAY_LENGTH = 10;

    public $base_url;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bot_user}}';
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
            [['t_id', ], 'required'],
            [['t_id', ], 'unique'],
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['t_id', 'created_at', 'updated_at'], 'integer'],
            [['is_bot', 'is_admin'], 'boolean'],
            [['source', 'ins_agent_id'], 'safe'],
            [['current_step_val', 't_username',  'last_name', 'source' , 'base_url', ], 'safe'],
            [['message_id_l', 'message_id_d', 'message_id_e', 'is_premium'], 'safe'],
            [['first_name', 'last_name', 'phone', 'language_code', 'callback_data', 'current_product', 'current_step_type', 'info'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('telegram', 'ID'),
            't_id' => Yii::t('telegram', 'T ID'),
            'is_bot' => Yii::t('telegram', 'Is Bot'),
            'first_name' => Yii::t('telegram', 'First Name'),
            'last_name' => Yii::t('telegram', 'Last Name'),
            't_username' => Yii::t('telegram', 'T Username'),
            'language_code' => Yii::t('telegram', 'Language Code'),
            'callback_data' => Yii::t('telegram', 'Callback Data'),
            'current_product' => Yii::t('telegram', 'Current product'),
            'current_step_type' => Yii::t('telegram', 'Current step type'),
            'current_step_val' => Yii::t('telegram', 'Current step value'),
            'is_premium' => Yii::t('telegram', 'Is Premium'),
            'info' => Yii::t('telegram', 'Info'),
            'source' => Yii::t('telegram', 'Source'),
            'created_at' => Yii::t('telegram', 'Created At'),
            'updated_at' => Yii::t('telegram', 'Updated At'),
            'base_url' => Yii::t('telegram', 'Webhook url'),
        ];
    }

    /**
     * Gets query for [[BotUserToContractors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBotUserToContractors()
    {
        return $this->hasMany(BotUserToUser::className(), ['bot_user_id' => 'id']);
    }

    /**
     * Gets query for [[BotUserToContractors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBotUserLogs()
    {
        return $this->hasMany(BotUserLog::className(), ['bot_user_id' => 'id']);
    }

    /**
     * Gets query for [[BotUserToContractors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyOsgos()
    {
        return $this->hasMany(PolicyOsgo::className(), ['bot_user_id' => 'id']);
    }

    /**
     * Gets query for [[BotUserToContractors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActivePolicyOsgo()
    {
        return $this->hasOne(PolicyOsgo::className(), ['bot_user_id' => 'id'])->andOnCondition(['status' => PolicyOsgo::STATUS_NEW])->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * Gets query for [[Contractors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContractors()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('{{%bot_user_to_user}}', ['bot_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource0()
    {
        return $this->hasOne(SourceCounter::className(), ['id' => 'source']);
    }

    const CAN_CHECK_ITEM_1 = 127566656;

    public static function getCheckItemUsers()
    {
        return [
          self::CAN_CHECK_ITEM_1 => self::CAN_CHECK_ITEM_1,
        ];
    }

    const CAN_DELIVERY_ITEM_1 = 127566656;

    public static function getDeliveryItemUsers()
    {
        return [
          self::CAN_DELIVERY_ITEM_1 => self::CAN_DELIVERY_ITEM_1,
        ];
    }

    public static function chooseLangugage()
    {
        $response = null;
        $languageFlags = [
            'uz' => '🇺🇿 ',
            'ru' => '🇷🇺 ',
            'en' => '🇺🇸 ',
            'uz-UZ' => '🇺🇿 ',
            'ru-RU' => '🇷🇺 ',
            'en-US' => '🇺🇸 ',
        ];
        $languageNames = \backend\modules\translatemanager\models\Language::getLanguageNames(true);
        if ($languageNames) {
            foreach ($languageNames as $lang_id => $name) {
                $str = !empty($languageFlags[$lang_id]) ? $languageFlags[$lang_id] : '';
                $str .= $name;
                $response[] = [
                    'text' => $str,
                    'callback_data' => self::PARAM_LANGUAGE.'-'.$lang_id,
                ];
            }
        }

        return $response;
    }

    public static function mainMenu()
    {
        return [
            [
                ['text' => self::PREFIX_ICON_HOME.' '.Yii::t('telegram', 'Home')],
                ['text' => self::PREFIX_ICON_NEW_POLICY.' '.Yii::t('telegram', 'New policy')],
            ],
//            [
//                ['text' => self::PREFIX_ICON_SETTINGS.' '.Yii::t('telegram', 'Settings')],
//                ['text' => self::PREFIX_ICON_MY_POLICY.' '.Yii::t('telegram', 'My policy')],
//            ],
        ];
    }


    /**
     * @param $status
     * @return array|mixed
     */
    public static function getProductTypeArray($status = null)
    {
        $array = [
            self::PRODUCT_TYPE_OSGO => Yii::t('policy', 'OSAGO'),
            self::PRODUCT_TYPE_TRAVEL => Yii::t('policy', 'TRAVEL'),
            self::PRODUCT_TYPE_ACCIDENT => Yii::t('policy', 'Baxtsiz hodisalardan sug\'urta qilish'),
            self::PRODUCT_TYPE_DOCUMENT => Yii::t('policy', 'Shahsiy hujjatlarni sug\'urtalash'),
            self::PRODUCT_TYPE_SPORT => Yii::t('policy', 'Sportchilarni sug\'urtalash'),
        ];

        return $status === null ? $array : $array[$status];
    }

    /**
     * ProductType Name
     * @return string
     */
    public function getProductTypeName()
    {
        $array = [
            self::PRODUCT_TYPE_OSGO => '<span class="text-bold text-warning">' . self::getProductTypeArray(self::PRODUCT_TYPE_OSGO) . '</span>',
            self::PRODUCT_TYPE_TRAVEL => '<span class="text-bold text-green">' . self::getProductTypeArray(self::PRODUCT_TYPE_TRAVEL) . '</span>',
        ];

        return isset($array[$this->status]) ? $array[$this->status] : $this->status;
    }


    public static function chooseProductType($bot_user_id=null, $lang = null, $s_p_id=null)
    {
        $response = null;
        $itemIcons = [
//            self::PRODUCT_TYPE_OSGO => '🚙 ',
//            self::PRODUCT_TYPE_TRAVEL => '🕵️‍♂️ ',
        ];
        $items = self::getProductTypeArray();
        if ($items && count($items)>1) {
            foreach ($items as $key => $name) {
                $str = !empty($itemIcons[$key]) ? $itemIcons[$key] : '';
                $str .= $name;
                $baseUrl = 'https://xs.ap-1.sharedwithexpose.com/policy/osgo/form';
                $baseUrl = 'https://kapitalsugurta.uz/policy/'.$key.'/calculate';
                $response[][] = [
                    'text' => $str,
//                    'callback_data' => self::PARAM_PRODUCT.'-'.$key,
                    'web_app' => [
                        'url' => $baseUrl."?telegram_bot=telegram_bot&s_p_id={$s_p_id}&language-picker-language={$lang}&b_u_id=".$bot_user_id
                    ],
                ];
            }
        }

        return $response;
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!empty($session['source_id_sess']['number'])) {
            $this->source = $session['source_id_sess']['number'];
        } else {
            $cookies = Yii::$app->request->cookies;
            if (($cookie = $cookies->get('source_id')) !== null) {
                $this->source = $cookie->value;
            }
        }
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
        if (empty($this->current_step_type) && empty($this->phone)) {
            $this->sendMessageToUser();
        }
        return true;
    }

    public function sendMessageToUser()
    {
        $bot_token = !empty(Yii::$app->params['tg.botTokenRobot']) ? Yii::$app->params['tg.botTokenRobot'] : BOT_TOKENT_SALE;

        if (!empty($this->t_id)) {

            $message = Yii::t('telegram', 'Assalomu alaykum! / Здравствуйте!')."\n";
            $params = [
                'chat_id' => $this->t_id,
                'text' => $message,
                'parse_mode' => 'HTML',
            ];
            $res = sendTelegramData('sendMessage', $params, $bot_token);

            $this->current_product = null;
            $this->current_step_type = BotUser::STEP_TYPE_CHOOSE_LANGUAGE;
            if (!$this->save()) {
                Yii::$app->session->addFlash('error', _generate_error($this->errors));
                Yii::warning($this->errors);
                _send_error('HandlerController modelBotUser not saved', json_encode($this->errors, JSON_UNESCAPED_UNICODE));
            }

            $message = Yii::t('telegram', 'Iltimos o’zingga qulay tilni tanlang. / Пожалуйста, выберите язык.')."\n";
            $reply_markup = BotUser::chooseLangugage();
            $params = [
                'chat_id' => $this->t_id,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard'=>[$reply_markup]
                ]),
            ];
            $res = sendTelegramData('sendMessage', $params, $bot_token);
        }

        return true;

    }
}
