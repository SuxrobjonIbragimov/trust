<?php

namespace common\library\paycom\Paycom;

use common\library\payment\models\PaymentTransaction;
use backend\modules\policy\models\PolicyOrder;
use Yii;

/**
 * Class Transaction
 *
 * Example MySQL table might look like to the following:
 *
 * CREATE TABLE `transactions` (
 *   `id` INT(11) NOT NULL AUTO_INCREMENT,
 *   `paycom_transaction_id` VARCHAR(25) NOT NULL COLLATE 'utf8_unicode_ci',
 *   `paycom_time` VARCHAR(13) NOT NULL COLLATE 'utf8_unicode_ci',
 *   `paycom_time_datetime` DATETIME NOT NULL,
 *   `create_time` DATETIME NOT NULL,
 *   `perform_time` DATETIME NULL DEFAULT NULL,
 *   `cancel_time` DATETIME NULL DEFAULT NULL,
 *   `amount` INT(11) NOT NULL,
 *   `state` TINYINT(2) NOT NULL,
 *   `reason` TINYINT(2) NULL DEFAULT NULL,
 *   `receivers` VARCHAR(500) NULL DEFAULT NULL COMMENT 'JSON array of receivers' COLLATE 'utf8_unicode_ci',
 *   `order_id` INT(11) NOT NULL,
 *
 *   PRIMARY KEY (`id`)
 * )
 *   COLLATE='utf8_unicode_ci'
 *   ENGINE=InnoDB
 *   AUTO_INCREMENT=1;
 *
 */
class Transaction
{
    /** Transaction expiration time in milliseconds. 43 200 000 ms = 12 hours. */
    const TIMEOUT = 43200000;

    const STATE_CREATED                  = 1;
    const STATE_COMPLETED                = 2;
    const STATE_CANCELLED                = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND         = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED            = 3;
    const REASON_CANCELLED_BY_TIMEOUT        = 4;
    const REASON_FUND_RETURNED               = 5;
    const REASON_UNKNOWN                     = 10;

    /** @var string Paycom transaction id. */
    public $paycom_transaction_id;

    /** @var int Paycom transaction time as is without change. */
    public $paycom_time;

    /** @var string Paycom transaction time as date and time string. */
    public $paycom_time_datetime;

    /** @var int Transaction id in the merchant's system. */
    public $id;

    /** @var string Transaction create date and time in the merchant's system. */
    public $create_time;

    /** @var string Transaction perform date and time in the merchant's system. */
    public $perform_time;

    /** @var string Transaction cancel date and time in the merchant's system. */
    public $cancel_time;

    /** @var int Transaction state. */
    public $state;

    /** @var int Transaction cancelling reason. */
    public $reason;

    /** @var int Amount value in coins, this is service or product price. */
    public $amount;

    /** @var string Pay receivers. Null - owner is the only receiver. */
    public $receivers;

    // additional fields:
    // - to identify order or product, for example, code of the order
    // - to identify client, for example, account id or phone number

    /** @var string Code to identify the order or service for pay. */
    public $order_id;

    /**
     * Saves current transaction instance in a data store.
     * @return bool true - on success
     */
    public function save()
    {
        // todo: Implement creating/updating transaction into data store
        // todo: Populate $id property with newly created transaction id

        set_history($this,['Transaction_save'], 'paycom_Transaction_save');

        $is_success = false;

        if (!$this->id) {

            // Create a new transaction

            $this->create_time = Format::timestamp2datetime(Format::timestamp());
            $payment_transaction = new PaymentTransaction();

            $values = [
                'service_transaction_id'    => $this->paycom_transaction_id,
                'sign_timestamp'    => $this->paycom_time,
                'sign_time' => $this->paycom_time_datetime,
                'create_time'    => $this->create_time,
                'total'        => 1 * $this->amount,
                'amount'        => 1 * $this->amount,
                'type'         => 'payme',
                'status'         => $this->state,
                'receivers'     => $this->receivers ? json_encode($this->receivers, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null,
                'order_id'       => 1 * $this->order_id,
            ];

            set_history($values,[$this->create_time], 'paycom_Transaction_save_values');
            try {

                $payment_transaction->attributes = $values;
                if ($payment_transaction->save()) {
                    // set the newly inserted transaction id
                    $this->id = $payment_transaction->id;
                    if ($payment_transaction->status == PaymentTransaction::STATUS_PAYMENT_PAID) {
                        $payment_transaction->order->policyModel->confirmPayment();
                    }
                } else {
                    $title = Yii::t('slug','Пожалуйста попробуйте позже');
                    $message = _generate_error($payment_transaction->errors);
                    _send_error($title, $message, []);
                    set_history($message,[$title], 'paycom_Transaction_not_save');
//                    $message = [
//                        $title,
//                        Yii::t('slug','Пожалуйста попробуйте позже'),
//                    ];
//                    Yii::$app->session->setFlash('error', $message);
                }
            } catch (\Exception $e) {
                $title = $e->getMessage();
                $message = "Code: " . $e->getCode();
                $message .= "\nFile: " . $e->getFile();
                $message .= "\nLine: " . $e->getLine();
                _send_error($title, $message, $e);
                set_history($message,[$title], 'paycom_Transaction_exeption');
//                $message = [
//                    $title,
//                    Yii::t('slug','Пожалуйста попробуйте позже'),
//                ];
//                Yii::$app->session->setFlash('error', $message);
            }
        } else {

            $payment_transaction = PaymentTransaction::findOne(['id' => $this->id, 'service_transaction_id' => $this->paycom_transaction_id]);

            $perform_time = $this->perform_time ? $this->perform_time : null;
            $cancel_time  = $this->cancel_time ? $this->cancel_time : null;
            $reason       = $this->reason ? 1 * $this->reason : null;

            $values = [
                'perform_time'         => $perform_time,
                'cancel_time'         => $cancel_time,
                'status'         => $this->state,
                'reason'         => $reason,
            ];

            if ($this->amount) {
                $values['amount'] = 1 * $this->amount;
            }

            try {

                $payment_transaction->attributes = $values;
                if ($payment_transaction->save()) {
                    // set the newly inserted transaction id
                    $this->id = $payment_transaction->id;
                    $is_success = true;
                } else {
                    $title = Yii::t('slug','Пожалуйста попробуйте позже');
                    $message = _generate_error($payment_transaction->errors);
                    _send_error($title, $message, []);
                    set_history($message,[$title], 'paycom_Transaction_update_not_save');
//                    $message = [
//                        $title,
//                        Yii::t('slug','Пожалуйста попробуйте позже'),
//                    ];
//                    Yii::$app->session->setFlash('error', $message);
                }
            } catch (\Exception $e) {
                $title = $e->getMessage();
                $message = "Code: " . $e->getCode();
                $message .= "\nFile: " . $e->getFile();
                $message .= "\nLine: " . $e->getLine();
                _send_error($title, $message, $e);
                set_history($message,[$title], 'paycom_Transaction_update_Exeption');
//                $message = [
//                    $title,
//                    Yii::t('slug','Пожалуйста попробуйте позже'),
//                ];
//                Yii::$app->session->setFlash('error', $message);
            }
        }

        return $is_success;
    }

    /**
     * Cancels transaction with the specified reason.
     * @param int $reason cancelling reason.
     * @return void
     */
    public function cancel($reason)
    {
        // todo: Implement transaction cancelling on data store

        // todo: Populate $cancel_time with value
        $this->cancel_time = Format::timestamp2datetime(Format::timestamp());

        // todo: Change $state to cancelled (-1 or -2) according to the current state

        if ($this->state == self::STATE_COMPLETED) {
            // Scenario: CreateTransaction -> PerformTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED_AFTER_COMPLETE;
        } else {
            // Scenario: CreateTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED;
        }

        // set reason
        $this->reason = $reason;

        // todo: Update transaction on data store
        $this->save();
    }

    /**
     * Determines whether current transaction is expired or not.
     * @return bool true - if current instance of the transaction is expired, false - otherwise.
     */
    public function isExpired()
    {
        // todo: Implement transaction expiration check
        // for example, if transaction is active and passed TIMEOUT milliseconds after its creation, then it is expired
        return $this->state == self::STATE_CREATED && abs(Format::datetime2timestamp($this->create_time) - Format::timestamp(true)) > self::TIMEOUT;
    }

    /**
     * Find transaction by given parameters.
     * @param mixed $params parameters
     * @return Transaction|Transaction[]
     * @throws PaycomException invalid parameters specified
     */
    public function find($params)
    {
        $return = null;
        // todo: Implement searching transaction by id, populate current instance with data and return it
        if (isset($params['id'])) {
            $payment_transaction = PaymentTransaction::findOne(['service_transaction_id' => $params['id']]);
        } elseif (isset($params['account'], $params['account']['order_id'])) {
            // todo: Implement searching transactions by given parameters and return the list of transactions
            // search by order id active or completed transaction

            $order = PolicyOrder::findOne($params['account']['order_id']);
            $state = [1,2];
            $payment_transaction = PaymentTransaction::findOne(['order_id' => $params['account']['order_id'], 'status' => $state]);
        } else {
            $return = new PaycomException(
                $params['request_id'],
                'Parameter to find a transaction is not specified.',
                PaycomException::ERROR_INTERNAL_SYSTEM
            );
            $return = $return->send();
        }

        // if SQL operation succeeded, then try to populate instance properties with values
        if (!empty($payment_transaction)) {

            $row = $payment_transaction;

            if ($row) {

                $this->id                    = $row->id;
                $this->paycom_transaction_id = $row->service_transaction_id;
                $this->paycom_time           = 1 * $row->sign_timestamp;
                $this->paycom_time_datetime  = $row->sign_time;
                $this->create_time           = $row->create_time;
                $this->perform_time          = $row->perform_time;
                $this->cancel_time           = $row->cancel_time;
                $this->state                 = 1 * $row->status;
                $this->reason                = $row->reason ? 1 * $row->reason : null;
                $this->total                = 1 * $row->amount;
                $this->amount                = 1 * $row->amount;
                $this->receivers             = $row->receivers ? json_decode($row->receivers, true) : null;
                $this->order_id              = 1 * $row->order_id;

                return $this;
            }

        }

        // transaction not found, return null
        return $return;

        // Possible features:
        // Search transaction by product/order id that specified in $params
        // Search transactions for a given period of time that specified in $params
    }

    /**
     * Gets list of transactions for the given period including period boundaries.
     * @param int $from_date start of the period in timestamp.
     * @param int $to_date end of the period in timestamp.
     * @return array list of found transactions converted into report format for send as a response.
     */
    public function report($from_date, $to_date)
    {
        $from_date = Format::timestamp2datetime($from_date);
        $to_date   = Format::timestamp2datetime($to_date);

        // container to hold rows/document from data store
        $rows = [];

        // todo: Retrieve transactions for the specified period from data store

        // Example implementation

        $sql = "SELECT * FROM payment_transactions 
                WHERE sign_time BETWEEN :from_date AND :to_date
                ORDER BY sign_time";

        $sth        = Yii::$app->db->prepare($sql);
        $is_success = $sth->execute([':from_date' => $from_date, ':to_date' => $to_date]);
        if ($is_success) {
            $rows = $sth->fetchAll();
        }

        // assume, here we have $rows variable that is populated with transactions from data store
        // normalize data for response
        $result = [];
        foreach ($rows as $row) {
            $result[] = [
                'id'           => $row['service_transaction_id'], // paycom transaction id
                'time'         => 1 * $row['sign_timestamp'], // paycom transaction timestamp as is
                'amount'       => 1 * $row['amount'],
                'account'      => [
                    'order_id' => 1 * $row['order_id'], // account parameters to identify client/order/service
                    // ... additional parameters may be listed here, which are belongs to the account
                ],
                'create_time'  => Format::datetime2timestamp($row['create_time']),
                'perform_time' => Format::datetime2timestamp($row['perform_time']),
                'cancel_time'  => Format::datetime2timestamp($row['cancel_time']),
                'transaction'  => 1 * $row['id'],
                'state'        => 1 * $row['status'],
                'reason'       => isset($row['reason']) ? 1 * $row['reason'] : null,
                'receivers'    => isset($row['receivers']) ? json_decode($row['receivers'], true) : null,
            ];
        }

        return $result;

    }
}
