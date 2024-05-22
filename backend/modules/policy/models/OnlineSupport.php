<?php
namespace backend\modules\policy\models;

use app\models\user\Profile;
use Yii;
use yii\base\Model;
use yii\helpers\BaseUrl;
use yii\httpclient\Client;

/**
 * Password reset form
 *
 * @property string $type
 * @property float $lat
 * @property float $lng
 * @property string $phone
 * @property string $link
 * @property string $policy_number
 *
 * @property string $type_message
 * @property string $bot_group_chat_id
 *
 */
class OnlineSupport extends Model
{
    const SCENARIO_SITE = 'scenario_app';
    const SCENARIO_CALL_ME = 'scenario_call_me';
    const SCENARIO_VIDEO_CALL = 'scenario_video_call';
    const SCENARIO_CAR_MONITORING = 'scenario_car_monitoring';

    public $phone;
    public $link;
    public $policy_number;
    public $lat;
    public $lng;
    public $type_message = null;
    public $bot_group_chat_id = CHAT_ID_GROUP_INSURANCE_ONLINE_CALL_ME;

    public function __construct($token = null, $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone', ], 'required', 'on' => self::SCENARIO_CALL_ME, 'message' => Yii::t('validation','Необходимо заполнить')],
            [['link', ], 'required', 'on' => self::SCENARIO_VIDEO_CALL, 'message' => Yii::t('validation','Необходимо заполнить')],
            [['policy_number'], 'required', 'on' => self::SCENARIO_CAR_MONITORING, 'message' => Yii::t('validation','Необходимо заполнить')],
            [['lat', 'lng', 'link', 'phone', 'policy_number', 'type_message', 'bot_group_chat_id'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => Yii::t('frontend','Phone'),
            'policy_number' => Yii::t('frontend','Policy number'),
            'link' => Yii::t('frontend','Link'),
            'lat' => Yii::t('frontend','Lat'),
            'lng' => Yii::t('frontend','Lng'),
        ];
    }

    const TYPE_CALL_ME = 1;
    const TYPE_VIDEO_CALL = 2;
    const TYPE_CAR_MONITORING = 3;

    public static function _getTypeMethodsList()
    {
        return [
            self::TYPE_CALL_ME,
            self::TYPE_VIDEO_CALL,
            self::TYPE_CAR_MONITORING,
        ];
    }

    /**
     * @param $value
     */
    public function _setScenario($value)
    {
        switch ($value) {
            case self::TYPE_CALL_ME:
                $this->type_message = Yii::t('frontend','Call me');
                $this->bot_group_chat_id = CHAT_ID_GROUP_INSURANCE_ONLINE_CALL_ME;
                $this->setScenario(self::SCENARIO_CALL_ME);
                break;
            case self::TYPE_VIDEO_CALL:
                $this->type_message = Yii::t('frontend','Video call');
                $this->bot_group_chat_id = CHAT_ID_GROUP_INSURANCE_ONLINE_VIDEO_CALL;
                $this->setScenario(self::SCENARIO_VIDEO_CALL);
                break;
            case self::TYPE_CAR_MONITORING:
                $this->type_message = Yii::t('frontend','Car monitoring');
                $this->bot_group_chat_id = CHAT_ID_GROUP_INSURANCE_ONLINE_CAR_MONITORING;
                $this->setScenario(self::SCENARIO_CAR_MONITORING);
                break;
        }
    }

    public function sendNewRequest($type_support)
    {
        $typeMessage = $this->type_message;
        $textCation = null;
        $full_name = null;
        if (!Yii::$app->user->isGuest) {
            $profile = Profile::findOne(['user_id' => Yii::$app->user->id]);
            $full_name = !empty($profile->fullName) ? $profile->fullName : null;
        }
        if ($full_name) {
            $full_name = "👤 {$full_name}";
        }
        if ($this->phone) {
            $phone = clear_phone_full($this->phone);
            $textCation = "📞 +{$phone}";
            $phone = "📞 +{$phone}";
        }
        $policy_number = null;
        if ($this->policy_number) {
            $policy_number = "📄 <b>{$this->policy_number}</b>";
        }
        $link = null;
        if ($this->link) {
            $link = "🌐 {$this->link}";
            $textCation = "🌐 {$this->link}";
        }
        $text =Yii::t('policy',"{$typeMessage}:{full_name}{phone}{policy_number}{link}",[
            'full_name' => $full_name ? "\n{$full_name}" : null,
            'phone' => $phone ? "\n{$phone}" : null,
            'policy_number' => $policy_number ? "\n{$policy_number}" : null,
            'link' => $link ? "\n{$link}" : null,
        ]);

        if (!empty($this->lat) && !empty($this->lng)) {

            if (!empty($link) && ('http' == substr($link, 0, 4))) {
                $reply_markup[] = [
                    'text' => $textCation,
                    'url' => $link,
                ];
            } else {
                $reply_markup[] = [
                    'text' => $textCation,
                    'callback_data' => $textCation,
                ];
            }
            $res = sendTelegramData('sendMessage', [
                'chat_id' => $this->bot_group_chat_id,
                'text' => $text,
                'parse_mode' => 'HTML'
            ],BOT_TOKENT_SALE,'online_support_'.$type_support);
            if (!empty($res['ok']) && !empty($res['result'])) {
                sendTelegramData('sendLocation', [
                    'chat_id' => $this->bot_group_chat_id,
                    'reply_to_message_id' => $res['result']['message_id'],
                    'latitude' => $this->lat,
                    'longitude' => $this->lng,
                    'reply_markup' => json_encode([
                        'inline_keyboard'=>[$reply_markup]
                    ]),
                ],BOT_TOKENT_SALE,'online_support_'.$type_support);
            } else {
                sendTelegramData('sendLocation', [
                    'chat_id' => $this->bot_group_chat_id,
                    'latitude' => $this->lat,
                    'longitude' => $this->lng,
                    'reply_markup' => json_encode([
                        'inline_keyboard'=>[$reply_markup]
                    ]),
                ],BOT_TOKENT_SALE,'online_support_'.$type_support);
            }
            if (LOG_DEBUG_SITE) {
                Yii::warning("\n\n\n\n");
                Yii::warning($res);
            }
            return true;
        } else {
            sendTelegramData('sendMessage', [
                'chat_id' => $this->bot_group_chat_id,
                'text' => $text,
                'parse_mode' => 'HTML'
            ],BOT_TOKENT_SALE,'online_support_'.$type_support);
            return true;
        }
    }
}
