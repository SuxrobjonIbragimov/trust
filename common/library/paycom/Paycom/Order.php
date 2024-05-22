<?php

namespace common\library\paycom\Paycom;

use backend\modules\policy\models\PolicyOrder;

/**
 * Class Order
 *
 * Example MySQL table might look like to the following:
 *
 * CREATE TABLE orders
 * (
 *     id          INT AUTO_INCREMENT PRIMARY KEY,
 *     product_ids VARCHAR(255)   NOT NULL,
 *     amount      DECIMAL(18, 2) NOT NULL,
 *     state       TINYINT(1)     NOT NULL,
 *     user_id     INT            NOT NULL,
 *     phone       VARCHAR(15)    NOT NULL
 * ) ENGINE = InnoDB;
 *
 */
class Order
{
    /** Order is available for sell, anyone can buy it. */
    const STATE_AVAILABLE = 0;

    /** Pay in progress, order must not be changed. */
    const STATE_WAITING_PAY = 1;

    /** Order completed and not available for sell. */
    const STATE_PAY_ACCEPTED = 2;

    /** Order is cancelled. */
    const STATE_CANCELLED = -1;

    public $request_id;
    public $params;

    // todo: Adjust Order specific fields for your needs

    /**
     * Order ID
     */
    public $id;

    /**
     * IDs of the selected products/services
     */
    public $product_ids;

    /**
     * Total price of the selected products/services
     */
    public $amount;

    /**
     * State of the order
     */
    public $state;

    /**
     * ID of the customer created the order
     */
    public $user_id;

    /**
     * Phone number of the user
     */
    public $phone;

    public function __construct($request_id)
    {
        $this->request_id = $request_id;
    }

    /**
     * Validates amount and account values.
     * @param array $params amount and account parameters to validate.
     * @return bool true - if validation passes
     * @throws PaycomException - if validation fails
     */
    public function validate(array $params)
    {
        $return = true;
        set_history($params,['chek_amount','amount' => (1 * $params['amount']) ,'amount_req' =>  $params['amount']],'order_before_validate', [$this->request_id]);

        // todo: Validate amount, if failed throw error
        // for example, check amount is numeric
        if (!is_numeric($params['amount'])) {
            $return = new PaycomException(
                $this->request_id,
                'Incorrect amount.',
                PaycomException::ERROR_INVALID_AMOUNT
            );
            return $return->send();
        }

        // todo: Validate account, if failed throw error
        // assume, we should have order_id
        if (!isset($params['account']['order_id']) || !$params['account']['order_id']) {
            $return = new PaycomException(
                $this->request_id,
                PaycomException::message(
                    'Неверный код заказа.',
                    'Harid kodida xatolik.',
                    'Incorrect order code.'
                ),
                PaycomException::ERROR_INVALID_ACCOUNT,
                'order_id'
            );
            return $return->send();
        }

        // todo: Check is order available

        // assume, after find() $this will be populated with Order data
        $order = PolicyOrder::findOne($params['account']['order_id']);

        // Check, is order found by specified order_id
        if (!$order || !$order->id) {
            $return = new PaycomException(
                $this->request_id,
                PaycomException::message(
                    'Неверный код заказа.',
                    'Harid kodida xatolik.',
                    'Incorrect order code.'
                ),
                PaycomException::ERROR_INVALID_ACCOUNT,
                'order_id'
            );
            return $return->send();
        }

        // validate amount
        // convert $this->amount to coins
        // $params['amount'] already in coins
        $order_amount = $order->total_amount;
        set_history($params,['chek_amount','order_total' => (100 * $order_amount ), 'amount' => (1 * $params['amount']) ,'amount_req' =>  $params['amount']],'order_before_validate', [$this->request_id]);
        if ((100 * $order_amount ) != (1 * $params['amount'])) {
            set_history($params,['chek_amount'],'order_validate', [$this->request_id]);
            $return = new PaycomException(
                $this->request_id,
                'Incorrect amount.',
                PaycomException::ERROR_INVALID_AMOUNT
            );
            return $return->send();
        }

        // for example, order state before payment should be 'waiting pay'
        if ($order->payment_status != self::STATE_WAITING_PAY) {
            $return = new PaycomException(
                $this->request_id,
                'Order state is invalid.',
                PaycomException::ERROR_COULD_NOT_PERFORM
            );
            return $return->send();
        }

        // keep params for further use
        $this->params = $params;

        return $return;
    }

    /**
     * Find order by given parameters.
     * @param mixed $params parameters.
     * @return Order|Order[] found order or array of orders.
     */
    public function find($params)
    {
        // todo: Implement searching order(s) by given parameters, populate current instance with data

        // Example implementation to load order by id
        if (isset($params['order_id'])) {

            $order = PolicyOrder::findOne($params['order_id']);

            if ($order) {

                $order_amount = $order->total_amount;

                $this->id          = 1 * $order->id;
                $this->amount      = 1 * $order_amount;
                $this->state       = 1 * $order->payment_status;
                $this->user_id     = 1 * $order->user_id;
                $this->phone       = clear_phone($order->phone);


                return $this;

            }

        }

        return null;
    }

    /**
     * Change order's state to specified one.
     * @param int $state new state of the order
     * @return void
     */
    public function changeState($state)
    {
        // todo: Implement changing order state (reserve order after create transaction or free order after cancel)

        // Example implementation
        $this->state = 1 * $state;
        $this->save();
    }

    /**
     * Check, whether order can be cancelled or not.
     * @return bool true - order is cancellable, otherwise false.
     */
    public function allowCancel()
    {
        // todo: Implement order cancelling allowance check

        // Example implementation
        return ($this->state == self::STATE_PAY_ACCEPTED); // do not allow cancellation
    }

    /**
     * Saves this order.
     * @throws PaycomException
     */
    public function save()
    {
        $return = true;
        $is_success = false;
        if ($this->id) {
            $order = PolicyOrder::findOne($this->id);
            $order->payment_status = $this->state;
            $is_success = $order->save();
            set_history($this,['ORDER SAVE state - '.$this->state], 'paycom_ORDER_update_saved');
        }

        if (!$is_success) {

            $return = new PaycomException(
                $this->request_id,
                'Could not found order.',
                PaycomException::ERROR_INTERNAL_SYSTEM
            );
            return $return->send();
        }
        return $return;
    }
}