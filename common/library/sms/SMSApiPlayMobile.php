<?php


namespace common\library\sms;

use common\library\request\models\RequestHistory;
use common\library\sms\models\Sms;
use Yii;
use yii\helpers\Url;
use yii\httpclient\Client;

class SMSApiPlayMobile
{
    const RESPONSE_SUCCESS = 1;
    const RESPONSE_UN_SUCCESS = 0;

    const IS_TEST = false;
    const AUTO_CAPTURE = true;
    const TRANSACTION_PREFIX = "insuranceon_prod_";

    private $baseUrl = "http://91.204.239.44/broker-api/send";
    private $language = 'ru';
    private $login = 'inson';
    private $password = 'QXBgjaQeJc';
    private $method = "";
    private $params = [];

    public $type = Sms::TYPE_OPT;

    public function __construct($token = null)
    {
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param array $params
     */
    public function setParams($params = [])
    {
        $state_params = [
            'messages' => $params,
        ];
        $this->params = $state_params;
    }

    /**
     * @param $model
     * @param int $total
     * @param array $basket
     */
    public function prepareSMS($data = [])
    {
        $state_params = [];
        foreach ($data as $index => $item) {
            if (is_array($item)) {
                $modelSms = $this->performSMS($item);
                $state_params[$index] = [
                    'recipient' => $modelSms->recipient,
                    'message-id' => $modelSms->message_id,
                    'sms' => [
                        "originator" => "3700",
                        "content" => [
                            'text' => $modelSms->text,
                        ],
                    ],
                ];
            }
        }

        $content = $state_params;
        $this->setParams($content);

    }

    /**
     * @return array|string
     * @throws \yii\base\InvalidConfigException
     */
    public function sendRequest()
    {
        if (self::IS_TEST) {
            return 200;
        }
        $baseUrl = $this->baseUrl;
        $client = new Client(['baseUrl' => $baseUrl]);

        $content = $this->params;
        $request = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->addHeaders([
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->setData($content);
        $request->headers->set('Authorization', 'Basic ' . base64_encode("$this->login:$this->password"));

        // Set Request history
        try {
            $response = $request->send();
            $this->setHistory($request, $response);
            if ($response->statusCode == 200) {
                $message = [
                    Yii::t('slug','Код подтверждения отправлено указанный номер '),
                ];
                Yii::$app->session->setFlash('success', $message);
                return $response->statusCode;
            }
            return [
                'success' => self::RESPONSE_UN_SUCCESS,
                'code' => isset($response->data['error']['code']) ? $response->data['error']['code'] : -999,
                'message' => isset($response->data['error']['message']) ? $response->data['error']['message'] : $response->data
            ];
        } catch (\Exception $e) {

            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);

            $message = [
                Yii::t('slug','Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
            return [
                'success' => self::RESPONSE_UN_SUCCESS,
                'code' => isset($response->data['error']['code']) ? $response->data['error']['code'] : -999,
                'message' => $response->getContent()
            ];
        }

    }

    /**
     * @param $condition
     * @return Sms|false|null
     */
    public static function checkSMS($condition)
    {
        if (self::IS_TEST) {
            return (!empty($condition['code']) && $condition['code'] == '12345');
        }
        try {
            $modelSms = Sms::findOne($condition);
            if (!empty($modelSms)) {
                return $modelSms;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);
            $message = [
                $title,
                Yii::t('slug','Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
        }
        return false;
    }

    /**
     * @param $condition
     * @return Sms|null
     */
    public static function smsVerified($condition)
    {
        if (self::IS_TEST) {
            return null;
        }
        try {
            $modelSms = Sms::findOne($condition);
            $modelSms->status = Sms::STATUS_VERIFIED;
            if (!$modelSms->save()) {
                dd($modelSms->errors);
            };
            return $modelSms;
        } catch (\Exception $e) {
            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);
            $message = [
                $title,
                Yii::t('slug','Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
        }
    }

    /**
     * @param $content
     * @param null $response
     * @return Sms|null
     */
    protected function performSMS($content, $response = null)
    {
        try {
            $condition = null;
            if (!empty($response["message_id"])) {
                $message_id = self::decodeMessageId($response['message_id']);
                $condition = [
                    'id' => $message_id,
                ];
            }
            if (!empty($content["recipient"]) && (isset($content['status']) && $content['status'] != Sms::STATUS_VERIFIED)) {
                $condition = [
                    'recipient' => clear_phone_full($content["recipient"]),
                    'status' => Sms::STATUS_NOT_VERIFIED,
                ];
            }
            if ($condition && $model = self::checkSMS($condition)) {
                $model->status = !empty($response['status']) ? $response['status'] : $model->status;
            } else {
                $model = new Sms();
            }
            $message_id = false;
            if ($model->isNewRecord) {
                $code = rand(10000, 99999);
                $message_id = true;
                $model->recipient = !empty($content['recipient']) ? $content['recipient'] : null;
                $model->code = empty($content['text']) ? $code : null;
                $model->type = $this->type;
                $model->status = !empty($content['status']) ? $content['status'] : Sms::STATUS_NOT_VERIFIED;
                $model->text = !empty($content['text']) ? $content['text'] : Yii::t('app','{code} - Your verification code for {appName}', [
                    'code' => $code,
                    'appName' => Yii::$app->name,
                ]);
                $model->priority = !empty($content['priority']) ? $content['priority'] : Sms::PRIORITY_NORMAL;
            }
            if ($message_id && $model->save()) {
                $model->message_id = self::generateMessageId($model->id);
            }
            $model->save();
            return $model;
        } catch (\Exception $e) {
            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);
            $message = [
                $title,
                Yii::t('slug','Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
        }
    }

    public static function generateMessageId($id)
    {
        if (self::TRANSACTION_PREFIX) {
            return self::TRANSACTION_PREFIX . $id;
        }
        return $id;
    }

    public static function decodeMessageId($id)
    {
        $message_id = $id;
        if (self::TRANSACTION_PREFIX) {
            $shop_transaction_ar = explode(self::TRANSACTION_PREFIX, $message_id);
            if (is_array($shop_transaction_ar)) {
                foreach ($shop_transaction_ar as $item) {
                    if (!empty($item)) {
                        $message_id = $item;
                    }
                }
            }
        }
        return $message_id;
    }


    /**
     * @param $request
     * @param $response
     */
    private function setHistory($request, $response)
    {
        try {
            $user_id = Yii::$app->user->id;
            $model = new RequestHistory();
            $model->method = 'sms_playMobile';
            $model->user_id = $user_id;
            $model->params = json_encode($this->params,JSON_UNESCAPED_UNICODE);
            $model->request = json_encode($request,JSON_UNESCAPED_UNICODE);
            $model->response = json_encode($response->content,JSON_UNESCAPED_UNICODE);
            if (!$model->save()) {
                dd($model->errors);
            }
        } catch (\Exception $e) {

            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);

            $message = [
                $title,
                Yii::t('slug','Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
        }
    }

}