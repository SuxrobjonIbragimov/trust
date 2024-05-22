<?php

namespace common\library\click;

use common\library\request\models\RequestHistory;
use backend\modules\policy\models\PolicyOrder;
use common\library\click\exceptions\ClickException;
use common\library\click\utils\Configs;
use common\library\payment\models\PaymentTransaction;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Url;
use yii\httpclient\Client;

class ClickMerchantApi
{
    const PAYMENT_TYPE = 'click';
    const CURRENCY_UZS = 'UZS';
    const TRANSACTION_PREFIX = "click_dev_";

    const TOKEN_SAVE = false;

    // Методы для клиентской части приложения мерчанта:
    const METHOD_PAYMENT_CANCEL = 'payment/reversal'; // Создание токена пластиковой карты.

    public $configs;
    public $transactionModel = null;
    public $method = null;
    public $params = null;
    public $baseUrl = null;
    public $requestMethod = 'POST';
    private $token = '';

    public function __construct()
    {
        if ($this->transactionModel === null) {
            $this->transactionModel = new PaymentTransaction();
        }
        $configs = new Configs();
        $this->configs = $configs->get_provider_configs();
        $this->token = $this->configs['click']['user_id'].':'.sha1(time().$this->configs['click']['secret_key']).':'.time();
//        dd($this->token);
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;

        if ( ($this->method == self::METHOD_PAYMENT_CANCEL) )
        {
            $payment_id = '1645660390';
            $this->method .= '/'.$this->configs['click']['service_id'].'/'.$this->params['payment_id'];
        }
    }

    /**
     * @param string $method
     */
    public function setParams($params=null)
    {
        $params['service_id'] = $this->configs['click']['service_id'];
        $this->params = $params;
    }

    /**
     * setRequestMethod
     * @param string $method
     */
    public function setRequestMethod($method='POST')
    {
        $this->requestMethod = $method;
    }

    /**
     * @param $params
     * @return array
     */
    public function sendRequest($params = [], $resp = [])
    {
        $baseUrl = $this->configs['endpointMerchant'];
        $baseUrl .='/'.$this->method;
        $client = new Client(['baseUrl' => $baseUrl]);

        $content = $this->params;
        $request = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod($this->requestMethod)
            ->addHeaders([
                'Accept' => 'application/json',
                'Auth' => $this->token,
                'Content-type' => 'application/json',
            ])
            ->setData($content);

        // Set Request history
        try {
            $response = $request->send();
            set_history($request, $response->data,'Click_merchant_api_try_'.$this->method,$this->params);
            if ($response->isOk) {
                return $response->data;
            }
            return [
                'success' => false,
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
                'success' => false,
                'code' => isset($response->data['error']['code']) ? $response->data['error']['code'] : -999,
            ];
        }

    }

    /**
     * @param $request
     * @param $response
     */
    protected function checkTransaction($condition)
    {
        try {
            $this->transactionModel = PaymentTransaction::findOne($condition);
            return $this->transactionModel;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @param $content
     * @param $response
     * @return PaymentTransaction|null
     */
    public function performTransaction($content, $response = null)
    {
        try {
            $condition = null;
            if (!empty($response["merchant_trans_id"])) {
                $condition = [
                    'order_id' => self::decodeTransactionId($response['merchant_trans_id']),
                ];
                if (!empty($response['click_trans_id'])) {
                    $condition['service_transaction_id'] = $response['click_trans_id'];
                }
            }
            if ($condition && $model = $this->checkTransaction($condition)) {
                $model->service_transaction_id = $response['click_trans_id'];
                $model->service_id = $response['service_id'];
                $model->click_paydoc_id = $response['click_paydoc_id'];
                $model->merchant_trans_id = $response['merchant_trans_id'];
                $model->action = $response['action'];
                $model->sign_string = $response['sign_string'];
                $model->status = $response['status'];
            } else {
                $model = new PaymentTransaction();
            }
            if ($model->isNewRecord) {
                $model->order_id = !empty($content['order_id']) ? $content['order_id'] : null;
                $model->created = time();
                $model->total = $content['total'];
                $model->currency = self::CURRENCY_UZS;
                $model->type = self::PAYMENT_TYPE;
            }
            $model->save();
            $this->transactionModel = $model;
            return $this->transactionModel;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function generateTransactionId($id)
    {
        if (self::TRANSACTION_PREFIX) {
            return self::TRANSACTION_PREFIX.$id;
        }
        return $id;
    }

    public static function decodeTransactionId($id)
    {
        $shop_transaction_id = $id;
        if (self::TRANSACTION_PREFIX) {
            $shop_transaction_ar = explode(self::TRANSACTION_PREFIX,$shop_transaction_id);
            if (is_array($shop_transaction_ar)) {
                foreach ($shop_transaction_ar as $item) {
                    if (!empty($item)) {
                        $shop_transaction_id = $item;
                    }
                }
            }
        }
        return $shop_transaction_id;
    }

    /**
     * @param $request
     * @param $response
     */
    protected function setHistory($request, $response)
    {
        try {
            $user = Yii::$app->user->identity;
            $model = new RequestHistory();
            $model->method = 'click_merchant_api_';
            $model->user_id = $user['id'] ? : null;
            $model->request = json_encode($request, JSON_UNESCAPED_UNICODE);
            $model->response = json_encode($response, JSON_UNESCAPED_UNICODE);
            $model->save();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


}