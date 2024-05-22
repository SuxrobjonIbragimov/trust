<?php


namespace common\library\paycom\Paycom;


use backend\modules\policy\models\PolicyOrder;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\base\Model;

/**
 * PaycomSubscribeForm is the model behind the paycom form.
 *
 * @property string $number
 * @property string $expire
 * @property string $code
 * @property string $phone
 * @property integer $step
 * @property string $_tmp_message
 * @property integer $_order_id
 *
 */
class PaycomSubscribeForm extends Model
{
    const SCENARIO_CARD_INFO = 'scenario_card_info';
    const SCENARIO_VERIFICATION = 'scenario_verify';

    const STEP_CARD_INFO = 0;
    const STEP_VERIFICATION = 1;

    const PREFIX_REQUEST_ID = '';
    const WAITING_TIME_FOR_REDIRECT = 5000;  // Время ожидания после успешного платежа в миллисекундах, до возврата покупателя на сайт мерчанта.

    public $number;
    public $expire;
    public $code;
    public $phone;
    public $step;
    public $_tmp_message;
    public $_order_id;

    public $payme_card_token;
    public $payme_service_tansaction_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'expire', ], 'required', 'on' => self::SCENARIO_CARD_INFO],
            [['code',], 'required', 'on' => self::SCENARIO_VERIFICATION],

            [['number', 'expire', 'phone',], 'string', ],
            [['_tmp_message',], 'safe', ],
            [['code', 'step',], 'integer'],
            [['_order_id',], 'safe'],
//            [['phone'], 'match', 'pattern' => '/^\+998(\d{2})-(\d{3})-(\d{4})$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'number' => Yii::t('slug','Номер карты'),
            'expire' => Yii::t('slug','Срок действия карты'),
            'code' => Yii::t('slug','Код для подтверждения'),
        ];
    }

    public function generatePayLink($params)
    {
        $result = null;
        $paycom_application = new PaycomApplication();
        $merchantID = !empty($params['osgo']) ? $paycom_application->merchant->config['osgo']['merchant_id'] : $paycom_application->merchant->config['merchant_id'];
        $order_id = $params['order_id'];
        $product_name = $params['product_name'];
        $amount = $params['amount'];
        $lang = _lang();
        $hash = $params['h'];
        $return_url = Url::to(['/policy/check/status', 'h' => $hash], true);
        $return_url = urlencode($return_url);
        $ct = self::WAITING_TIME_FOR_REDIRECT;
        $result = 'https://checkout.paycom.uz/'.base64_encode("m={$merchantID};ac.order_id={$order_id};ac.product_name={$product_name};a={$amount};l={$lang};c={$return_url};ct={$ct}");
        return $result;

    }

    public function createPayment(PolicyOrder $order)
    {
        if ($this->validate()) {
            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();

            $sessionId = $session->id;
            $condition = [];

            if (Yii::$app->user->isGuest) {
                $userId = 0;
                $condition = ArrayHelper::merge($condition, ['session_id' => $sessionId]);
            } else {
                $userId = Yii::$app->user->id;
                $condition = ArrayHelper::merge($condition, ['user_id' => $userId]);
            }

            $paymeSubscribeApi = new PaycomSubscribeApi();
            $paymeSubscribeApi->order_id = $order->id;
            $paymeSubscribeApi->setMethod(PaycomSubscribeApi::METHOD_CARDS_CREATE);

            $order_amount = $order->total_amount;
            $params = [
                'card' => [
                    'number' => clear_card($this->number),
                    'expire' => clear_card_expire($this->expire),
                ],
                'amount' => (int)($order_amount*100),
                'save' => PaycomSubscribeApi::TOKEN_SAVE,
            ];
            $paymeSubscribeApi->setParams($params);
            $response = $paymeSubscribeApi->sendRequest();
            if (!empty($response['result'])) {
                $token_card = !empty($response['result']['card']['token']) ? $response['result']['card']['token'] : null;
                $session->set('payme_card_token',$token_card);
                return true;
            } elseif (!empty($response['error'])) {
                $message = !empty($response['error']['data']['message'][_lang()]) ? $response['error']['data']['message'][_lang()] : $response['error']['message'];
                $this->_tmp_message = $message;
                $session->addFlash('error', $message);
            }
            return false;
        } else {
            $this->_tmp_message = _generate_error($this->errors);
            Yii::error($this->errors);
        }
        return false;
    }

    public function getVerifyCode(PolicyOrder $order,$token=null)
    {
        if ($this->validate()) {
            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();
            $paymeSubscribeApi = new PaycomSubscribeApi();
            $paymeSubscribeApi->order_id = $order->id;
            $paymeSubscribeApi->setMethod(PaycomSubscribeApi::METHOD_CARDS_GET_VERIFY_CODE);
            $params = [
                'token' => $session->get('payme_card_token')
            ];
            $paymeSubscribeApi->setParams($params);
            $response = $paymeSubscribeApi->sendRequest();
            if (!empty($response['result'])) {
                $this->phone = $response['result']['phone'];
                return true;
            } elseif (!empty($response['error'])) {
                $message = $response['error']['message'];
                $this->_tmp_message = $message;
                $session->addFlash('error', $message);
            }
            return false;
        } else {
            $this->_tmp_message = _generate_error($this->errors);
            Yii::error($this->errors);
        }
        return false;
    }

    public function verifyPayment(PolicyOrder $order)
    {
        if ($this->validate()) {
            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();
            $paymeSubscribeApi = new PaycomSubscribeApi();
            $paymeSubscribeApi->order_id = $order->id;
            $paymeSubscribeApi->setMethod(PaycomSubscribeApi::METHOD_CARDS_VERIFY);
            $params = [
                'token' => $session->get('payme_card_token'),
                'code' => $this->code,
            ];
            $paymeSubscribeApi->setParams($params);
            $response = $paymeSubscribeApi->sendRequest();
            if (!empty($response['result'])) {
                $session->set('payme_card_token',$response['result']['card']['token']);
                $this->payme_card_token = $response['result']['receipt']['_id'];
                return true;
            } elseif (!empty($response['error'])) {
                $message = $response['error']['message'];
                $this->_tmp_message = $message;
                $session->addFlash('error', $message);
            }
            return false;
        } else {
            $this->_tmp_message = _generate_error($this->errors);
            Yii::error($this->errors);
        }
        return false;
    }

    public function receiptsCreate(PolicyOrder $order)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();
        if ($this->validate()) {
            $paymeSubscribeApi = new PaycomSubscribeApi();
            $paymeSubscribeApi->order_id = $order->id;
            $paymeSubscribeApi->setMethod(PaycomSubscribeApi::METHOD_RECEIPTS_CREATE);

            $order_amount = $order->total_amount;
            $params = [
                'amount' => (int)($order_amount*100),
                'account' => [
                    'order_id' => $order->id,
                    'product_name' => $order->productName,
                ],
            ];
            $paymeSubscribeApi->setParams($params);
            $response = $paymeSubscribeApi->sendRequest();
            if (!empty($response['result'])) {
                $session->set('payme_service_tansaction_id',$response['result']['receipt']['_id']);
                $this->payme_service_tansaction_id = $response['result']['receipt']['_id'];
                return true;
            } elseif (!empty($response['error'])) {
                $message = $response['error']['message'];
                $this->_tmp_message = $message;
                $session->addFlash('error', $message);
            }
            return false;
        } else {
            $this->_tmp_message = _generate_error($this->errors);
            $session->addFlash('error', _generate_error($this->errors));
        }
        return false;
    }

    public function receiptsPay(PolicyOrder $order)
    {
        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();
        if ($this->validate()) {
            $paymeSubscribeApi = new PaycomSubscribeApi();
            $paymeSubscribeApi->order_id = $order->id;
            $paymeSubscribeApi->setMethod(PaycomSubscribeApi::METHOD_RECEIPTS_PAY);
            $params = [
                'id' => $session->get('payme_service_tansaction_id'),
                'token' => $session->get('payme_card_token'),
            ];
            $paymeSubscribeApi->setParams($params);
            $response = $paymeSubscribeApi->sendRequest();
            if (!empty($response['result'])) {
                $session->set('payme_service_tansaction_id',$response['result']['receipt']['_id']);
                $this->_tmp_message = Yii::t('slug','Thank you for ordering!');
                $this->_tmp_message .= ' '.Yii::t('slug','Payment successfully.');
                $this->_tmp_message .= ' '.Yii::t('app','Your order id: {order_id}. Please remember this number!',['order_id' => $order->id]);
                return true;
            } elseif (!empty($response['error'])) {
                $message = $response['error']['message'];
                $this->_tmp_message = $message;
                $session->addFlash('error', $message);
            }
            return false;
        } else {
            $this->_tmp_message = _generate_error($this->errors);
            $session->addFlash('error', _generate_error($this->errors));
        }
        return false;
    }

    public function receiptsSend(PolicyOrder $order)
    {
        return true;
        if ($this->validate()) {
            $session = Yii::$app->session;
            if (!$session->isActive) $session->open();
            $paymeSubscribeApi = new PaycomSubscribeApi();
            $paymeSubscribeApi->order_id = $order->id;
            $paymeSubscribeApi->setMethod(PaycomSubscribeApi::METHOD_RECEIPTS_SEND);
            $params = [
                'id' => $session->get('payme_service_tansaction_id'),
                'phone' => isset(Yii::$app->params['adminPhone']) ? Yii::$app->params['adminPhone'] : '998974708092',
            ];
            $paymeSubscribeApi->setParams($params);
            $response = $paymeSubscribeApi->sendRequest();
            if (!empty($response['result'])) {
                return true;
            } elseif (!empty($response['error'])) {
                $session->addFlash('error', $response['error']['message']);
            }
            return false;
        } else {
            dd($this->errors);
        }
        return false;
    }


    /**
     * @param $id
     * @return string
     */
    public static function _generateRequestId($id)
    {
        if (self::PREFIX_REQUEST_ID) {
            return self::PREFIX_REQUEST_ID.$id;
        }
        return $id;
    }

    public static function decodeRequestId($id)
    {
        $shop_transaction_id = $id;
        if (self::PREFIX_REQUEST_ID) {
            $shop_transaction_ar = explode(self::PREFIX_REQUEST_ID,$shop_transaction_id);
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


}