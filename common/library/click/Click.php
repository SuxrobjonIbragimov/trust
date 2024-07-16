<?php

namespace common\library\click;

use backend\modules\policy\models\PolicyOrder;
use common\library\click\exceptions\ClickException;
use common\library\click\utils\Configs;
use common\library\payment\models\PaymentTransaction;
use Yii;
use yii\helpers\Url;

class Click
{
    const PAYMENT_TYPE = 'click';
    const CURRENCY_UZS = 'UZS';
    const TRANSACTION_PREFIX = "";

    public $configs;
    public $transactionModel = null;

    public function __construct()
    {
        if ($this->transactionModel === null) {
            $this->transactionModel = new PaymentTransaction();
        }
        $configs = new Configs();
        $this->configs = $configs->get_provider_configs();
    }

    public function request_check($request){
        // check to error in request from click
        if(!(
                isset($request['click_trans_id']) &&
                isset($request['service_id']) &&
                isset($request['merchant_trans_id']) &&
                isset($request['amount']) &&
                isset($request['action']) &&
                isset($request['error']) &&
                isset($request['error_note']) &&
                isset($request['sign_time']) &&
                isset($request['sign_string']) &&
                isset($request['click_paydoc_id'])
            ) || $request['action'] == 1 && ! isset($request['merchant_prepare_id'])) {
            // return response array-like
            return [
                'error' => ClickException::ERROR_IN_REQUEST_FROM_CLICK,
                'error_note' => 'Error in request from click',
                'request' => $request
            ];
        }

        // prepare sign string as md5 digest
        $sign_string = md5(
            $request['click_trans_id'] .
            $request['service_id'] .
            $this->configs['click']['secret_key'] .
            $request['merchant_trans_id'] .
            ($request['action'] == 1 ? $request['merchant_prepare_id'] : '') .
            $request['amount'] .
            $request['action'] .
            $request['sign_time']
        );
        // check sign string to possible
        if($sign_string != $request['sign_string']){
            // return response array-like
            return [
                'error' => ClickException::ERROR_SIGN_CHECK_FAILED,
                'error_note' => 'SIGN CHECK FAILED!'
            ];
        }

        // check to action not found error
        if (!((int)$request['action'] == 0 || (int)$request['action'] == 1)) {
            // return response array-like
            return [
                'error' => ClickException::ERROR_ACTION_NOT_FOUND,
                'error_note' => 'Action not found'
            ];
        }

        // get payment data by merchant_trans_id
        $condition = [
            'order_id' => self::decodeTransactionId($request['merchant_trans_id']),
        ];
        if((!empty($condition) && $this->checkTransaction($condition))) {
            if($this->transactionModel && $request['error'] != ClickException::ERROR_CANCEL_PAYMENT) {

                if($this->transactionModel->status == PaymentTransaction::STATUS_PAYMENT_PAID){
                    // return response array-like
                    return [
                        'error' => ClickException::ERROR_ALREADY_PAID,
                        'error_note' => 'Already paid'
                    ];
                }

                // check status to transaction cancelled
                if($this->transactionModel->status == PaymentTransaction::STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE || $this->transactionModel->status == PaymentTransaction::STATUS_PAYMENT_CANCELED){
                    // return response array-like
                    return [
                        'error' => ClickException::ERROR_TRANSACTION_CANCELLED,
                        'error_note' => 'Transaction cancelled'
                    ];
                }

                // get payment data by merchant_prepare_id
                if($request['action'] == 1) {
                    //$trans = Transactions::findOne([$request['merchant_prepare_id']]);

                    if(empty($this->transactionModel)){
                        // return response array-like
                        return [
                            'error' => ClickException::ERROR_TRANSACTION_DOES_NOT_EXIST,
                            'error_note' => 'Transaction does not exist '
                        ];
                    }
                }

                // check to correct amount
                if(abs((float)$this->transactionModel->total - (float)$request['amount']) > 0.01){
                    // return response array-like
                    return [
                        'error' => ClickException::ERROR_INCORRECT_AMOUNT,
                        'error_note' => 'Incorrect parameter amount'
                    ];
                }
            }
        } else  {
            // return response array-like
            return [
                'error' => ClickException::ERROR_USER_DOES_NOT_EXIST,
                'error_note' => 'User does not exist'
            ];
        }

        // return response array-like as success
        return [
            'error' => ClickException::ERROR_NO,
            'error_note' => 'Success',
            'click_trans_id' => $request['click_trans_id'],
            'merchant_trans_id' => $request['merchant_trans_id'],
            'merchant_prepare_id' => !empty($request['merchant_prepare_id']) ? $request['merchant_prepare_id'] : null,
            'merchant_confirm_id' => !empty($request['merchant_confirm_id']) ? $request['merchant_confirm_id'] : null,
        ];

    }

    public function prepare($request)
    {
        $params = [];
        if (!empty($request['merchant_trans_id'])) {
            $condition = [
                'order_id' => self::decodeTransactionId($request['merchant_trans_id']),
            ];
            if ($this->checkTransaction($condition)) {
                $this->transactionModel->service_transaction_id = $request['click_trans_id'];
                $this->transactionModel->click_paydoc_id = $request['click_paydoc_id'];
                $this->transactionModel->merchant_trans_id = $request['merchant_trans_id'];
                $this->transactionModel->merchant_prepare_id = $this->transactionModel->id;
                $this->transactionModel->action = isset($request['action']) ? $request['action'] : 0;
                $this->transactionModel->sign_time = !empty($request['sign_time']) ? $request['sign_time'] : null;
                $this->transactionModel->sign_string = !empty($request['sign_string']) ? $request['sign_string'] : null;
                $this->transactionModel->status = !empty($request['status']) ? $request['status'] : PaymentTransaction::STATUS_PAYMENT_WAIT;
                if ($this->transactionModel->save()) {
                    $params = [
                        'click_trans_id' => $this->transactionModel->service_transaction_id,
                        'merchant_trans_id' => $this->transactionModel->merchant_trans_id,
                        'merchant_prepare_id' => $this->transactionModel->id,
                    ];
                    if ($request['amount'] == $this->transactionModel->total) {
                        $params['error'] = ClickException::ERROR_NO;
//                        $params['error'] = ClickException::ERROR_MINUS_ONE; // FOR DEBUG
                    } else {
                        $params['error'] = ClickException::ERROR_INCORRECT_AMOUNT;
                    }
                } else {
                    Yii::warning($this->transactionModel->errors);
                }
            } else {
                Yii::warning("\n\nUSER NOT FOUND\n");
                $params = [
                    'error' => ClickException::ERROR_USER_DOES_NOT_EXIST,
                ];
            }
        }
        return $params;
    }

    public function complete($request)
    {
        $params = [];
        if (!empty($request)) {
            $condition = [
                'order_id' => self::decodeTransactionId($request['merchant_trans_id']),
                'service_transaction_id' => $request['click_trans_id'],
                'merchant_prepare_id' => $request['merchant_prepare_id'],
            ];
            if ($this->checkTransaction($condition)) {
                $this->transactionModel->action = $request['action'];
                $this->transactionModel->sign_time = $request['sign_time'];
                if (!empty($request['error']) && ($request['error'] == ClickException::ERROR_CANCEL_PAYMENT)) {
                    $this->transactionModel->cancel_time = time();
                    $this->transactionModel->status = PaymentTransaction::STATUS_PAYMENT_CANCELED;
                } else {
                    $this->transactionModel->status = PaymentTransaction::STATUS_PAYMENT_PAID;
                }
                if ($this->transactionModel->save()) {
                    $params = [
                        'click_trans_id' => $this->transactionModel->service_transaction_id,
                        'merchant_trans_id' => $this->transactionModel->merchant_trans_id,
                        'merchant_confirm_id' => $this->transactionModel->merchant_prepare_id,
                    ];
                    $params['error'] = ($this->transactionModel->status == PaymentTransaction::STATUS_PAYMENT_CANCELED) ? ClickException::ERROR_TRANSACTION_CANCELLED : ClickException::ERROR_NO;
                } else {
                    Yii::warning("\n\nErrors\n");
                    Yii::warning($this->transactionModel->errors);
                }
            } else {
                $params = [
                    'error' => ClickException::ERROR_TRANSACTION_DOES_NOT_EXIST,
                ];
            }
        }
        return $params;
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


    public function generatePayButton($params = [])
    {
        $transAmount = $this->transactionModel->total ?: 1000;
        $transID = $this->transactionModel->order_id ?: 0;
        $config = $this->configs;
        $merchantID = $config['click']['merchant_id']; //Нужно заменить параметр на полученный ID
        $merchantUserID = $config['click']['user_id'];
        $serviceID = $config['click']['service_id'];
        $transID = self::generateTransactionId($transID);
        $transAmount = number_format($transAmount, 2, '.', '');
        $returnURL = Url::to(['/customer/thank-you', 'id' => $transID], true);
        $cardType = 'uzcard';
        $HTML = <<<CODE
<form action="https://my.click.uz/services/pay" id=”click_form” method="get" target="_blank">
                                    <input type="hidden" name="amount" value="$transAmount" />
                                    <input type="hidden" name="merchant_id" value="$merchantID"/>
                                    <input type="hidden" name="merchant_user_id" value="$merchantUserID"/>
                                    <input type="hidden" name="service_id" value="$serviceID"/>
                                    <input type="hidden" name="transaction_param" value="$transID"/>
                                    <input type="hidden" name="return_url" value="$returnURL"/>
                                    <input type="hidden" name="card_type" value="$cardType"/>
                                    <button type="submit" class="click_logo"><i></i>Оплатить через CLICK</button>                         
</form>
CODE;
        return $HTML;
    }

    public function generatePayLink($params = [])
    {
        $config = $this->configs;
        $transAmount = $this->transactionModel->total ?: 0;
        $transID = $this->transactionModel->order_id ?: 0;
        $merchantID = !empty($params['osgo']) ? $config['click-osgo']['merchant_id'] : $config['click']['merchant_id']; //Нужно заменить параметр на полученный ID
        $merchantUserID = !empty($params['osgo']) ? $config['click-osgo']['user_id'] : $config['click']['user_id'];
        $serviceID = !empty($params['osgo']) ? $config['click-osgo']['service_id'] : $config['click']['service_id'];
        $transID = self::generateTransactionId($transID);
        $transAmount = number_format($transAmount, 0, '.', '');
        $returnURL = Url::to(['/policy/check/status', 'hash' => $params['hash'] ?? null], true);
//        $returnURL = urlencode($returnURL);
        $cardType = 'uzcard';
        $result = $this->configs['endpoint'].'?'.http_build_query([
                'service_id' => $serviceID,
                'merchant_id' => $merchantID,
                'merchant_user_id' => $merchantUserID,
                'amount' => $transAmount,
                'transaction_param' => $transID,
                'return_url' => $returnURL,
                'card_type' => $cardType,
            ]);
        return $result;
    }


}