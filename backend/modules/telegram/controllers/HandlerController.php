<?php

namespace backend\modules\telegram\controllers;

use backend\models\page\SourceCounter;
use backend\modules\handbook\models\InsAgent;
use backend\modules\policy\models\PolicyOsgo;
use backend\modules\telegram\models\BotUser;
use backend\modules\telegram\models\BotUserLog;
use backend\modules\telegram\models\BotUserMessage;
use backend\modules\telegram\models\BotUserToUser;
use backend\modules\translatemanager\models\Language;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Cookie;

/**
 * Default controller for the `telegram` module
 *
 * @property int $chat_id
 * @property string $bot_token
 * @property array $request_data
 * @property BotUser $modelBotUser
 */
class HandlerController extends Controller
{
    public $enableCsrfValidation = false;

    public $chat_id = null;
    public $bot_token = null;
    public $modelBotUser;
    public $request_data = null;
    public $request_data_tmp = null;
    public $default_error_message = null;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->request_data = json_decode(Yii::$app->request->getRawBody(), true);
        $this->request_data_tmp = json_decode(Yii::$app->request->getRawBody(), true);
        $this->default_error_message = Yii::t('policy', 'Хатолик юз берди биз оздан сўнг қайта уриниб кўринг')."\n";
        $this->bot_token = !empty(Yii::$app->params['tg.botTokenRobot']) ? Yii::$app->params['tg.botTokenRobot'] : BOT_TOKENT_SALE;

    }

    public function checkAccess($key) {
        if(Yii::$app->request->cookieValidationKey != $key) {
            return false;
        }
        return true;
    }

    /**
     * @return array|string
     */
    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $header = Yii::$app->request->getHeaders();
        Yii::warning($this->request_data);
        if(!$this->checkAccess($header->get('X-Telegram-Bot-Api-Secret-Token'))) {
            Yii::$app->response->statusCode = 401;
            $response = [
                "result" => false,
                "message" => Yii::t('model','Access denied'),
                "response" => null
            ];
            Yii::warning("\nRESPONSE ACCESS\n\n");
            Yii::warning($response);
            return $response;
        }
        $chat_id = null; //127566656;
        $response = null;
        $message_text = !empty($this->request_data['message']['text']) ? $this->request_data['message']['text'] : '';
        $message_text_ar = explode('/start', $message_text);
        $referal = null;
        $referral = null;
        if (!empty($message_text_ar[0]) && !empty($message_text_ar[1])) {
            $message_text = '/start';
        }
        if (!empty($message_text_ar[1])) {
            if (is_numeric(trim($message_text_ar[1]))) {
                $referal = intval(trim($message_text_ar[1]));
                SourceCounter::counterSource($referal);
            } else {
                $referral = (trim($message_text_ar[1]));
                $response_r = InsAgent::counterSource($referral);
                $referral = !empty($response_r['s_p_id']) ? $response_r['s_p_id'] : null;
            }
        }
        if ( !empty($this->request_data['callback_query']['data']) ) {
            $t_id = null;
            if (!empty($this->request_data['callback_query']['from']['id'])) {
                $t_id = $this->request_data['callback_query']['from']['id'];
                $this->chat_id = $t_id;
                $bot_user_data = $this->request_data['callback_query']['from'];
                $bot_user_data['t_id'] = $this->request_data['callback_query']['from']['id'];
                $bot_user_data['t_username'] = !empty($bot_user_data['username']) ? $bot_user_data['username'] : null;
                $bot_user_data['source'] = $referal;
                $bot_user_data['ins_agent_id'] = $referral;
                $language_code = $bot_user_data['language_code'];
                unset($bot_user_data['language_code']);
                unset($bot_user_data['id']);
                if (!empty($bot_user_data['username'])) {
                    unset($bot_user_data['username']);
                }

                $this->modelBotUser = BotUser::findOne(['t_id' => $bot_user_data['t_id']]) ?:
                    new BotUser($bot_user_data);

                if ($this->modelBotUser->isNewRecord) {
                    $this->modelBotUser->language_code = $language_code;
                }
                if (!$this->modelBotUser->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                    Yii::warning($this->modelBotUser->errors);
                    _send_error('HandlerController modelBotUser not saved 118', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                } else {
                    $this->setLang();
                    $botLogModel = new BotUserLog(
                        [
                            'bot_user_id' => $this->modelBotUser->id,
                            'data' => json_encode($this->request_data),
                        ]
                    );
                    if (!$botLogModel->save()) {
                        Yii::$app->session->addFlash('error', _generate_error($botLogModel->errors));
                        Yii::warning($botLogModel->errors);
                        _send_error('HandlerController $botLogModel not saved', json_encode($botLogModel->errors, JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $response = $this->nextStep();

        } elseif ( !empty($this->request_data['message']['text']) && $message_text == '/start') {

            $bot_user_data = $this->request_data['message']['from'];
            $bot_user_data['t_id'] = $bot_user_data['id'];
            $bot_user_data['t_username'] = !empty($bot_user_data['username']) ? $bot_user_data['username'] : null;
            $bot_user_data['source'] = $referal;
            $bot_user_data['ins_agent_id'] = $referral;
            $language_code = $bot_user_data['language_code'];
            unset($bot_user_data['language_code']);
            unset($bot_user_data['id']);
            if (!empty($bot_user_data['username'])) {
                unset($bot_user_data['username']);
            }
            $this->chat_id = $bot_user_data['t_id'];

            $this->modelBotUser = BotUser::findOne(['t_id' => $bot_user_data['t_id']]) ?:
                new BotUser($bot_user_data);
            if ($this->modelBotUser->isNewRecord) {
                $this->modelBotUser->language_code = $language_code;
                if (!$this->modelBotUser->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                    Yii::warning($this->modelBotUser->errors);
                    _send_error('HandlerController modelBotUser not saved 158', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                }
                $response = $this->sendWelcomeMessage();
            } else {
                $this->setLang();
                $response = $this->nextStep();
            }

            if (!$this->modelBotUser->save()) {
                Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                Yii::warning($this->modelBotUser->errors);
                _send_error('HandlerController modelBotUser not saved /start 164', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
            } else {
                $this->setLang();
                $botLogModel = new BotUserLog(
                    [
                        'bot_user_id' => $this->modelBotUser->id,
                        'data' => json_encode($this->request_data),
                    ]
                );
                if (!$botLogModel->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($botLogModel->errors));
                    Yii::warning($botLogModel->errors);
                    _send_error('HandlerController $botLogModel not saved', json_encode($botLogModel->errors, JSON_UNESCAPED_UNICODE));
                }
            }

            if (!empty($this->modelBotUser->t_id)) {
                $modelMessage = new BotUserMessage();
                $modelMessage->message = $message_text;
                $modelMessage->type = BotUserMessage::TYPE_IN;
                $modelMessage->bot_user_id = $this->modelBotUser->id;
                if (!$modelMessage->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($modelMessage->errors));
                    Yii::warning($modelMessage->errors);
                    _send_error('HandlerController $modelMessage not saved', json_encode($modelMessage->errors, JSON_UNESCAPED_UNICODE));
                }
            }

        } else {

            if (!empty($this->request_data['message']['from'])) {

                $bot_user_data = $this->request_data['message']['from'];
                $bot_user_data['t_id'] = $bot_user_data['id'];
                $bot_user_data['t_username'] = !empty($bot_user_data['username']) ? $bot_user_data['username'] : null;
                $bot_user_data['source'] = $referal;
                $bot_user_data['ins_agent_id'] = $referral;
                $language_code = $bot_user_data['language_code'];
                unset($bot_user_data['language_code']);
                unset($bot_user_data['id']);
                if (!empty($bot_user_data['username'])) {
                    unset($bot_user_data['username']);
                }
                $this->chat_id = $bot_user_data['t_id'];

                $this->modelBotUser = BotUser::findOne(['t_id' => $bot_user_data['t_id']]) ?:
                    new BotUser($bot_user_data);

                if ($this->modelBotUser->isNewRecord) {
                    $this->modelBotUser->language_code = $language_code;
                    $response = $this->sendWelcomeMessage();
                    if (!empty($response['ok']) && !empty($response['result']['message_id'])) {
                        $this->modelBotUser->message_id_l = intval($response['result']['message_id']);
                        $this->modelBotUser->message_id_d = intval($response['result']['message_id']);
                    }
                } else {
                    $this->setLang();
                    $text = !empty($this->request_data['message']['text']) ? $this->request_data['message']['text'] : null;
                    if (!empty($text)) {
                        $explode = explode(' ', $text);
                        if (!empty($explode) && is_array($explode) && !empty($explode[1])) {
                            $str_1 = $explode[0];
                            switch ($str_1) {
                                case BotUser::PREFIX_ICON_NEW_POLICY:
                                    if (!empty($this->modelBotUser)) {
                                        $this->modelBotUser->current_product = null;
                                        $this->modelBotUser->current_step_type = BotUser::STEP_TYPE_CHOOSE_PRODUCT;
                                        $this->modelBotUser->current_step_val = null;
                                        if (!$this->modelBotUser->save()) {
                                            Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                                            Yii::warning($this->modelBotUser->errors);
                                            _send_error('HandlerController modelBotUser not saved PREFIX_ICON_NEW_POLICY', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                                        }
                                    }
                                    $response = $this->sendChooseProductTypeMessage();
                                    break;
                                case BotUser::PREFIX_ICON_HOME:
                                    if (!empty($this->modelBotUser)) {
                                        $this->modelBotUser->current_product = null;
                                        $this->modelBotUser->current_step_type = null;
                                        $this->modelBotUser->current_step_val = null;
                                        if (!$this->modelBotUser->save()) {
                                            Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                                            Yii::warning($this->modelBotUser->errors);
                                            _send_error('HandlerController modelBotUser not saved PREFIX_ICON_HOME', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                                        }
                                    }
                                    $response = $this->sendChooseProductTypeMessage();
                                    break;
                                default:
                                    $response = $this->nextStep();
                            }
                        } else {
                            $response = $this->nextStep();
                        }
                    } else {
                        $response = $this->nextStep();
                    }
                }
                if (!$this->modelBotUser->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                    Yii::warning($this->modelBotUser->errors);
                    _send_error('HandlerController modelBotUser not saved 266 ', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                } else {
                    $this->setLang();
                    $botLogModel = new BotUserLog(
                        [
                            'bot_user_id' => $this->modelBotUser->id,
                            'data' => json_encode($this->request_data),
                        ]
                    );
                    if (!$botLogModel->save()) {
                        Yii::$app->session->addFlash('error', _generate_error($botLogModel->errors));
                        Yii::warning($botLogModel->errors);
                        _send_error('HandlerController $botLogModel not saved', json_encode($botLogModel->errors, JSON_UNESCAPED_UNICODE));
                    }
                }

                if (!empty($this->modelBotUser->t_id)) {
                    $modelMessage = new BotUserMessage();
                    $modelMessage->message = $message_text;
                    $modelMessage->type = BotUserMessage::TYPE_IN;
                    $modelMessage->bot_user_id = $this->modelBotUser->id;
                    if (!$modelMessage->save()) {
                        Yii::$app->session->addFlash('error', _generate_error($modelMessage->errors));
                        Yii::warning($modelMessage->errors);
                        _send_error('HandlerController $modelMessage not saved', json_encode($modelMessage->errors, JSON_UNESCAPED_UNICODE));
                    }
                }

            }
        }
        if (!empty($response) && is_array($response)) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (empty($response['method'])) {
                $response['method'] = 'sendMessage';
            }

            if (!empty($this->modelBotUser->t_id) && !empty($response['text'])) {
                $modelMessage = new BotUserMessage();
                $modelMessage->message = $response['text'];
                $modelMessage->type = BotUserMessage::TYPE_REPLY;
                $modelMessage->bot_user_id = $this->modelBotUser->id;
                if (!$modelMessage->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($modelMessage->errors));
                    Yii::warning($modelMessage->errors);
                    _send_error('HandlerController $modelMessage not saved', json_encode($modelMessage->errors, JSON_UNESCAPED_UNICODE));
                }
            }

            set_history($this->request_data_tmp, $response, 'tg_handbook_webhook_reply');
            return $response;
        }

        return $this->render('index');
    }

    /**
     * @return void
     */
    public function setLang()
    {
        if (!empty($this->modelBotUser->language_code)) {
            $lang = $this->modelBotUser->language_code;
            Yii::$app->language = $lang;
            $cookie = new Cookie([
                'name' => 'language',
                'domain' => '',
                'value' => $lang,
                'expire' => time() + 86400 * 30
            ]);
            Yii::$app->response->cookies->add($cookie);
        }
    }

    /**
     * @return false|mixed
     */
    public function sendWelcomeMessage()
    {
        $message = Yii::t('telegram', 'Assalomu alaykum! / Здравствуйте!')."\n";
        $params = [
            'chat_id' => $this->chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
        ];
        $res = sendTelegramData('sendMessage', $params, $this->bot_token);


        return $this->chooseLanguage();
    }

    /**
     * @return false|mixed
     */
    public function sendErrorMessage($message=null, $force = false)
    {
        $message = !empty($message) ? $message : $this->default_error_message;
        $params = [
            'chat_id' => $this->chat_id,
            'text' => '❌ '.$message,
            'parse_mode' => 'HTML',
        ];
        if ($force) {
            $res = sendTelegramData('sendMessage', $params, $this->bot_token);
            return $res;
        }
        return $params;
    }

    /**
     * @return bool
     */
    public function nextStep()
    {
        $params = null;
        if (!empty($this->modelBotUser->current_step_type) && (!empty($this->modelBotUser->phone) || $this->modelBotUser->current_step_type == BotUser::STEP_TYPE_CHOOSE_LANGUAGE)) {
            switch ($this->modelBotUser->current_step_type) {
                case BotUser::STEP_TYPE_CHOOSE_LANGUAGE:
                    $params = $this->chooseLanguage();
                    break;
                case BotUser::STEP_TYPE_PHONE:
                    $params = $this->sendPhoneRequest();
                    break;
                case BotUser::STEP_TYPE_CHOOSE_PRODUCT:
                    $params = $this->sendChooseProductTypeMessage();
                    break;
                case BotUser::STEP_TYPE_POLICY_OSGO_VEHICLE_GOV_NUMBER:
                    $params = $this->osgoVehicleGovNumberInfo();
                    break;
                case BotUser::STEP_TYPE_POLICY_OSGO_TECH_PASS:
                    $params = $this->osgoTechPassInfo();
                    break;
                case BotUser::STEP_TYPE_POLICY_OSGO_CHOOSE_APPLICANT:
                    $params = $this->osgoChooseApplicantType();
                    break;
                case BotUser::STEP_TYPE_POLICY_OSGO_APPLICANT:
                    $params = $this->osgoApplicantInfo();
                    break;
                case BotUser::STEP_TYPE_POLICY_OSGO_CHOOSE_DRIVER_LIMIT:
                    $params = $this->osgoChooseDriverLimit();
                    break;
                case BotUser::STEP_TYPE_POLICY_OSGO_OWNER_IS_DRIVER:
                    $params = $this->osgoChooseOwnerIsDriver();
                    break;
                case BotUser::STEP_TYPE_POLICY_OSGO_ADD_DRIVERS:
                    $params = $this->osgoAddDriver();
                    break;
                default:
                    $params = $this->sendHomeMessage();
            }
        } elseif (empty($this->modelBotUser->phone)) {
            $params = $this->sendPhoneRequest();
        } else {
            $params = $this->sendHomeMessage();
        }
        return $params;
    }

    /**
     * @return false|mixed
     */
    public function chooseLanguage()
    {
        $params = null;
        if ( !empty($this->request_data['callback_query']['data']) ) {

            $explode = explode('-',$this->request_data['callback_query']['data']);
            $param_name = !empty($explode[0]) ? ($explode[0]) : null;
            $param_1 = !empty($explode[1]) ? $explode[1] : null;
            $param_2 = !empty($explode[2]) ? $explode[2] : null;
            $param_3 = !empty($explode[3]) ? $explode[3] : null;

            switch ($param_name) {
                case BotUser::PARAM_LANGUAGE:
                    $langModel = Language::findOne(['language' => $param_1]);
                    $param_1 = !empty($langModel->language_id) ? $langModel->language_id : $param_1;
                    $this->modelBotUser->language_code = $param_1;
                    Yii::$app->language = $param_1;
                    $cookie = new Cookie([
                        'name' => 'language',
                        'domain' => '',
                        'value' => $param_1,
                        'expire' => time() + 86400 * 30
                    ]);
                    Yii::$app->response->cookies->add($cookie);
                    $message =Yii::t('telegram','✅ Malumotlar saqlandi');
                    $params = [
                        'callback_query_id' => $this->request_data['callback_query']['id'],
                        'text' => $message
                    ];
                    $res = sendTelegramData('answerCallbackQuery', $params, $this->bot_token);
                    if (!empty($res['ok'])) {
                        if (!empty($this->request_data['callback_query']['from']['id'])) {
                            $params = [
                                'chat_id' => $this->request_data['callback_query']['from']['id'],
                                'message_id' => $this->request_data['callback_query']['message']['message_id'],
                            ];
                            $res = sendTelegramData('deleteMessage', $params, $this->bot_token);
                        }

                        $this->modelBotUser->current_product = null;
                        $this->modelBotUser->current_step_type = BotUser::STEP_TYPE_PHONE;
                        if (!$this->modelBotUser->save()) {
                            Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                            Yii::warning($this->modelBotUser->errors);
                            _send_error('HandlerController modelBotUser not saved 470', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                        } else {
                            $this->request_data = null;
                            $params = $this->nextStep();
                        }

                    } else {
                        if (!empty($this->request_data['callback_query']['from']['id'])) {
                            $params = [
                                'chat_id' => $this->request_data['callback_query']['from']['id'],
                                'message_id' => $this->request_data['callback_query']['message']['message_id'],
                            ];
                            $res = sendTelegramData('deleteMessage', $params, $this->bot_token);
                        }

                        $this->modelBotUser->current_product = null;
                        $this->modelBotUser->current_step_type = BotUser::STEP_TYPE_PHONE;
                        if (!$this->modelBotUser->save()) {
                            Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                            Yii::warning($this->modelBotUser->errors);
                            _send_error('HandlerController modelBotUser not saved 470', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                        } else {
                            $this->request_data = null;
                            $params = $this->nextStep();
                        }

                    }
                    if (!$this->modelBotUser->save()) {
                        Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                        Yii::warning($this->modelBotUser->errors);
                        _send_error('HandlerController modelBotUser not saved 480', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                    }
                    break;
            }

        } else {
            if (!empty($this->modelBotUser)) {
                $this->modelBotUser->current_product = null;
                $this->modelBotUser->current_step_type = BotUser::STEP_TYPE_CHOOSE_LANGUAGE;
                if (!$this->modelBotUser->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                    Yii::warning($this->modelBotUser->errors);
                    _send_error('HandlerController modelBotUser not saved 492', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                }
            }
            $message = Yii::t('telegram', 'Iltimos o’zingga qulay tilni tanlang. / Пожалуйста, выберите язык.')."\n";
            $reply_markup = BotUser::chooseLangugage();
            $params = [
                'chat_id' => $this->chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard'=>[$reply_markup]
                ]),
            ];
        }
        return $params;
    }

    /**
     * @return false|mixed
     */
    public function sendHomeMessage()
    {
        $menu_keyboard = BotUser::mainMenu();
        $message = Yii::t('telegram', 'Iltimos kerakli menuni tanlang')."\n";
        $params = [
            'chat_id' => $this->chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'keyboard'=>$menu_keyboard,
                'resize_keyboard'=>true,
            ]),
        ];
        if (!empty($this->modelBotUser)) {
            $this->modelBotUser->current_product = null;
            $this->modelBotUser->current_step_type = null;
            if (!$this->modelBotUser->save()) {
                Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                Yii::warning($this->modelBotUser->errors);
                _send_error('HandlerController modelBotUser not saved 531', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
            }
        }
        return $params;
    }

    /**
     * @return false|mixed
     */
    public function sendPhoneRequest()
    {
        $params = null;
        if (!empty($this->request_data['message']['contact']) || (!empty($this->request_data['message']['text']) && (strlen(trim($this->request_data['message']['text'])) <= BotUser::PHONE_NUMBER_LENGTH)) ) {

            $str = !empty($this->request_data['message']['contact']['phone_number']) ? $this->request_data['message']['contact']['phone_number'] : $this->request_data['message']['text'];
            $str = mb_strtoupper(trim($str));
            $phone = clear_phone_full($str);
            $this->modelBotUser->phone = $phone;
            $this->modelBotUser->current_step_type = null;
            if (!$this->modelBotUser->save()) {
                Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                Yii::warning($this->modelBotUser->errors);
                _send_error('HandlerController modelBotUser not saved 553', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
            }
            $params = $this->sendHomeMessage();
        } else {
            $message = Yii::t('telegram', 'Iltimos botimizdan foydalanish uchun telefon raqamingizni jo’nating')."\n";
            $params = [
                'chat_id' => $this->chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard'=> [
                        [
                            [
                                'text' => Yii::t('telegram', "Share my phone number"),
                                'request_contact' => true,
                            ],
                        ],
                    ],
                    'resize_keyboard'=>true,
                    'one_time_keyboard'=>true,
                ]),
            ];
            if (!empty($this->modelBotUser)) {
                $this->modelBotUser->current_product = null;
                $this->modelBotUser->current_step_type = BotUser::STEP_TYPE_PHONE;
                $this->modelBotUser->current_step_val = null;
                if (!$this->modelBotUser->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                    Yii::warning($this->modelBotUser->errors);
                    _send_error('HandlerController modelBotUser not saved 582', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                }
            }
        }
        return $params;
    }

    /**
     * @return false|mixed|null
     */
    public function sendChooseProductTypeMessage()
    {
        $params = null;
        $message = Yii::t('telegram', 'Qaysi product bo`yicha polis rasmiylashtirmoqchisiz?.')."\n";
        $reply_markup = BotUser::chooseProductType($this->modelBotUser->id, $this->modelBotUser->language_code, $this->modelBotUser->ins_agent_id);

        if ( !empty($this->request_data['callback_query']['data']) ) {

            $explode = explode('-',$this->request_data['callback_query']['data']);
            $param_name = !empty($explode[0]) ? ($explode[0]) : null;
            $param_1 = !empty($explode[1]) ? $explode[1] : null;
            $param_2 = !empty($explode[2]) ? $explode[2] : null;
            $param_3 = !empty($explode[3]) ? $explode[3] : null;

            switch ($param_name) {
                case BotUser::PARAM_PRODUCT:
                    if ($param_1 == BotUser::PRODUCT_TYPE_OSGO && empty($param_2)) {
                        if (!empty($this->request_data['callback_query']['from']['id'])) {
                            $params = [
                                'chat_id' => $this->request_data['callback_query']['from']['id'],
                                'message_id' => $this->request_data['callback_query']['message']['message_id'],
                            ];
                            $res = sendTelegramData('deleteMessage', $params, $this->bot_token);
                        }
                        $this->modelBotUser->current_product = BotUser::PRODUCT_TYPE_OSGO;
                        $this->modelBotUser->current_step_type = BotUser::STEP_TYPE_POLICY_OSGO_VEHICLE_GOV_NUMBER;
                        $this->modelBotUser->current_step_val = null;
                        if (!$this->modelBotUser->save()) {
                            Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                            Yii::warning($this->modelBotUser->errors);
                            _send_error('HandlerController modelBotUser not saved 622', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                            $params = $this->sendErrorMessage();
                        } else {
                            $params = $this->nextStep();
                        }
                    }
                    break;
            }

        } elseif (!empty($reply_markup)) {
            if (!empty($this->modelBotUser)) {
                $this->modelBotUser->current_product = null;
                $this->modelBotUser->current_step_type = BotUser::STEP_TYPE_CHOOSE_PRODUCT;
                $this->modelBotUser->current_step_val = null;
                if (!$this->modelBotUser->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                    Yii::warning($this->modelBotUser->errors);
                    _send_error('HandlerController modelBotUser not saved 639', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                }
            }
            $params = [
                'chat_id' => $this->chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'inline_keyboard'=>$reply_markup
                ]),
            ];
//            $res = sendTelegramData('sendMessage', $params, $this->bot_token);
        } else {
            if (!empty($this->modelBotUser)) {
                $this->modelBotUser->current_product = BotUser::DEFAULT_PRODUCT_TYPE;
                $this->modelBotUser->current_step_type = BotUser::STEP_TYPE_POLICY_OSGO_VEHICLE_GOV_NUMBER;
                $this->modelBotUser->current_step_val = null;
                if (!$this->modelBotUser->save()) {
                    Yii::$app->session->addFlash('error', _generate_error($this->modelBotUser->errors));
                    Yii::warning($this->modelBotUser->errors);
                    _send_error('HandlerController modelBotUser not saved 659', json_encode($this->modelBotUser->errors, JSON_UNESCAPED_UNICODE));
                } else {
                    $params = $this->nextStep();
                }
            }
        }
        return $params;
    }


    public function actionSetWebhookTelegram()
    {
        $model = new BotUser();
        if (Yii::$app->user->can('administrator')) {
            if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
                $baseUrl = 'https://kapitalsugurta.uz';
                $url = Url::to(['/telegram/handler/index']);
                $fullUrl = $model->base_url;
                $params = [
                    'url' => $fullUrl,
                    'secret_token' => Yii::$app->request->cookieValidationKey,
                ];
                Yii::warning($params);
                $res = sendTelegramData('setWebhook', $params, $this->bot_token);
                Yii::warning($res);
                if (!empty($res['description'])) {
                    Yii::$app->session->addFlash('warning', $res['description']);
                }
            } else {
                $params = [
                    'getWebhookInfo' => true,
                ];
                $res = sendTelegramData('getWebhookInfo', $params, $this->bot_token);
                Yii::warning($res);
                if (!empty($res['description'])) {
                    Yii::$app->session->addFlash('warning', $res['ok']);
                }
                if (!empty($res['result']['url'])) {
                    $model->base_url = $res['result']['url'];
                } else {
                    $baseUrl = Yii::$app->request->baseUrl;
                    $url = Url::to(['/telegram/handler/index']);
                    $fullUrl = $baseUrl.$url;
                    $model->base_url = $fullUrl;
                }
            }
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    public function actionGetWebhookInfoTelegram()
    {
        if (Yii::$app->user->can('administrator')) {
            $params = [
                'getWebhookInfo' => true,
            ];
            $res = sendTelegramData('getWebhookInfo', $params, $this->bot_token);
            Yii::warning($res);
            if (!empty($res['description'])) {
                Yii::$app->session->addFlash('warning', $res['ok']);
            }
        }
        return $this->render('index');
    }

    public function actionDeleteWebhookTelegram()
    {
        if (Yii::$app->user->can('administrator')) {
            $params = [
                'deleteWebhook' => true,
            ];
            $res = sendTelegramData('deleteWebhook', $params, $this->bot_token);
            Yii::warning($res);
            if (!empty($res['description'])) {
                Yii::$app->session->addFlash('warning', $res['description']);
            }
        }
        return $this->render('index');
    }

}
