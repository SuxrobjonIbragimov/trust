<?php

namespace common\library\paycom\Paycom;

use Yii;

class PaycomApplication
{
    public $config;
    public $request;
    public $response;
    public $merchant;

    /**
     * Application constructor.
     * @param array $config configuration array with <em>merchant_id</em>, <em>login</em>, <em>keyFile</em> keys.
     */
    public function __construct()
    {
        $path_to_configs = __DIR__ . '//..//paycom.config.php';
        $this->config = require($path_to_configs);

        $this->request  = new Request();
        $this->response = new Response($this->request);
        $this->merchant = new Merchant($this->config);
    }

    /**
     * Authorizes session and handles requests.
     */
    public function run()
    {
        $return = null;
        try {
            // authorize session
            if (($auth = $this->merchant->Authorize($this->request->id)) !== true || $this->request->return !== true) {
                return $auth;
            }

            // handle request
            switch ($this->request->method) {
                case 'CheckPerformTransaction':
                    $return = $this->CheckPerformTransaction();
                    break;
                case 'CheckTransaction':
                    $return = $this->CheckTransaction();
                    break;
                case 'CreateTransaction':
                    $return = $this->CreateTransaction();
                    break;
                case 'PerformTransaction':
                    $return = $this->PerformTransaction();
                    break;
                case 'CancelTransaction':
                    $return = $this->CancelTransaction();
                    break;
                case 'ChangePassword':
                    $return = $this->ChangePassword();
                    break;
                case 'GetStatement':
                    $return = $this->GetStatement();
                    break;
                default:
                    $this->response->error(
                        PaycomException::ERROR_METHOD_NOT_FOUND,
                        'Method not found.',
                        $this->request->method
                    );
                    break;
            }
            return $return;
        } catch (\Exception $e) {

            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);

            set_history($message,[$title], 'paycom_run_exeption');
            $message = [
                Yii::t('slug','Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
            return [
                'success' => false,
                'code' => isset($response->data['message']['code']) ? $response->data['message']['code'] : -999,
            ];
        }

    }

    private function CheckPerformTransaction()
    {
        $return = null;
        $order = new Order($this->request->id);

        set_history($this,[$this->request], 'CheckPerformTransaction');
        $order->find($this->request->params['account']);

        // validate parameters
        if (($validated = $order->validate($this->request->params)) !== true) {
            return $validated;
        }

        // todo: Check is there another active or completed transaction for this order
        $transaction = new Transaction();
        $found       = $transaction->find($this->request->params);
        if ($found && ($found->state == Transaction::STATE_CREATED || $found->state == Transaction::STATE_COMPLETED)) {
            return $this->response->error(
                PaycomException::ERROR_COULD_NOT_PERFORM,
                'There is other active/completed transaction for this order.'
            );
        }

        // if control is here, then we pass all validations and checks
        // send response, that order is ready to be paid.
        return $this->response->send(['allow' => true]);
    }

    private function CheckTransaction()
    {
        // todo: Find transaction by id
        $transaction = new Transaction();
        $found       = $transaction->find($this->request->params);
        if (!$found || (empty($found->paycom_transaction_id)) ) {
            return $this->response->error(
                PaycomException::ERROR_TRANSACTION_NOT_FOUND,
                'Transaction not found.'
            );
        }

        // todo: Prepare and send found transaction
        return $this->response->send([
            'create_time' => $found->paycom_time,
            'perform_time'  => !empty($found->perform_time) ? Format::datetime2timestamp($found->perform_time) : 0,
            'cancel_time'  => !empty($found->cancel_time) ? Format::datetime2timestamp($found->cancel_time) : 0,
            'transaction' => "$found->id",
            'state'        => $found->state,
            'reason'       => isset($found->reason) ? 1 * $found->reason : null,
        ]);
    }

    private function CreateTransaction()
    {
        $order = new Order($this->request->id);
        $order->find($this->request->params['account']);

        // validate parameters
        if (($validated = $order->validate($this->request->params)) !== true) {
            return $validated;
        }


        // todo: Check, is there any other transaction for this order/service
        $transaction = new Transaction();
        $params = [
            'account' => $this->request->params['account']
        ];
        set_history($params,[$this->request->method], 'found 1 params');
        $found       = $transaction->find($params);
        set_history($found,[$this->request->method], 'found 1 result');
        if ($found && !empty($found->paycom_transaction_id)) {
            if (($found->state == Transaction::STATE_CREATED || $found->state == Transaction::STATE_COMPLETED)
                && $found->paycom_transaction_id !== $this->request->params['id']) {
                return $this->response->error(
                    PaycomException::ERROR_INVALID_ACCOUNT,
                    'There is other active/completed transaction for this order.'
                );
            }
        }

        // todo: Find transaction by id
        $transaction = new Transaction();
        $found       = $transaction->find($this->request->params);

        set_history($this->request->params,[$this->request->method], 'found 2 params');
        set_history($found,[$this->request->method], 'found 2 result');
        if ($found && (!empty($found->paycom_transaction_id))) {
            if ($found->state != Transaction::STATE_CREATED) { // validate transaction state
                return $this->response->error(
                    PaycomException::ERROR_COULD_NOT_PERFORM,
                    'Transaction found, but is not active.'
                );
            } elseif ($found->isExpired()) { // if transaction timed out, cancel it and send error
                $found->cancel(Transaction::REASON_CANCELLED_BY_TIMEOUT);
                return $this->response->error(
                    PaycomException::ERROR_COULD_NOT_PERFORM,
                    'Transaction is expired.'
                );
            } else { // if transaction found and active, send it as response
                return $this->response->send([
                    'create_time' => $found->paycom_time,
                    'transaction' => "$found->id",
                    'state'       => $found->state,
                    'receivers'   => $found->receivers,
                ]);
            }
        } else { // transaction not found, create new one

            // validate new transaction time
            if (Format::timestamp2milliseconds(1 * $this->request->params['time']) - Format::timestamp(true) >= Transaction::TIMEOUT) {
                return $this->response->error(
                    PaycomException::ERROR_INVALID_ACCOUNT,
                    PaycomException::message(
                        'С даты создания транзакции прошло ' . Transaction::TIMEOUT . 'мс',
                        'Tranzaksiya yaratilgan sanadan ' . Transaction::TIMEOUT . 'ms o`tgan',
                        'Since create time of the transaction passed ' . Transaction::TIMEOUT . 'ms'
                    ),
                    'time'
                );
            }

            // create new transaction
            // keep create_time as timestamp, it is necessary in response
            $create_time                        = $this->request->params['time'];
            $perform_time                        = Format::timestamp(true);
            $transaction->paycom_transaction_id = $this->request->params['id'];
            $transaction->paycom_time           = $this->request->params['time'];
            $transaction->paycom_time_datetime  = Format::timestamp2datetime($this->request->params['time']);
            $transaction->create_time           = Format::timestamp2datetime($create_time);
            $transaction->state                 = Transaction::STATE_CREATED;
            $transaction->amount                = Format::toSom($this->request->amount);
            $transaction->order_id              = $this->request->account('order_id');
            $transaction->save(); // after save $transaction->id will be populated with the newly created transaction's id.

            // send response
            return $this->response->send([
                'create_time' => $create_time,
                'transaction' => "$transaction->id",
                'state'       => $transaction->state,
                'receivers'   => null,
            ]);
        }
    }

    private function PerformTransaction()
    {
        $transaction = new Transaction();
        // search transaction by id
        $found = $transaction->find($this->request->params);

        // if transaction not found, send error
        if (!$found) {
            return $this->response->error(PaycomException::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }

        switch ($found->state) {
            case Transaction::STATE_CREATED: // handle active transaction
                if ($found->isExpired()) { // if transaction is expired, then cancel it and send error
                    $found->cancel(Transaction::REASON_CANCELLED_BY_TIMEOUT);
                    return $this->response->error(
                        PaycomException::ERROR_COULD_NOT_PERFORM,
                        'Transaction is expired.'
                    );
                } else { // perform active transaction
                    // todo: Mark order/service as completed
                    $params = ['order_id' => $found->order_id];
                    $order  = new Order($this->request->id);
                    $order->find($params);
                    $order->changeState(Order::STATE_PAY_ACCEPTED);

                    // todo: Mark transaction as completed
                    $perform_time        = Format::timestamp(true);
                    $found->state        = Transaction::STATE_COMPLETED;
                    $found->perform_time = Format::timestamp2datetime($perform_time);
                    $found->save();

                    return $this->response->send([
                        'transaction'  => "$found->id",
                        'perform_time' => $perform_time,
                        'state'        => $found->state,
                    ]);
                }
                break;

            case Transaction::STATE_COMPLETED: // handle complete transaction
                // todo: If transaction completed, just return it
                return $this->response->send([
                    'transaction'  => "$found->id",
                    'perform_time' => Format::datetime2timestamp($found->perform_time),
                    'state'        => $found->state,
                ]);
                break;

            default:
                // unknown situation
                return $this->response->error(
                    PaycomException::ERROR_COULD_NOT_PERFORM,
                    'Could not perform this operation.'
                );
                break;
        }
    }

    private function CancelTransaction()
    {
        $transaction = new Transaction();

        // search transaction by id
        $found = $transaction->find($this->request->params);

        // if transaction not found, send error
        if (!$found) {
            return $this->response->error(PaycomException::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }

        $return = null;
        switch ($found->state) {
            // if already cancelled, just send it
            case Transaction::STATE_CANCELLED:
            case Transaction::STATE_CANCELLED_AFTER_COMPLETE:
                $return = $this->response->send([
                    'transaction'  => "$found->id",
                    'cancel_time' => Format::datetime2timestamp($found->cancel_time),
                    'state'       => $found->state,
                ]);
                break;

            // cancel active transaction
            case Transaction::STATE_CREATED:
                // cancel transaction with given reason
                $found->cancel(1 * $this->request->params['reason']);
                // after $found->cancel(), cancel_time and state properties populated with data

                // change order state to cancelled
                $order = new Order($this->request->id);
                $order->find($this->request->params);
                $order->changeState(Order::STATE_CANCELLED);

                // send response
                $return = $this->response->send([
                    'transaction'  => "$found->id",
                    'cancel_time' => Format::datetime2timestamp($found->cancel_time),
                    'state'       => $found->state,
                ]);
                break;

            case Transaction::STATE_COMPLETED:
                // find order and check, whether cancelling is possible this order
                $order = new Order($this->request->id);

                $params = ['order_id' => $found->order_id];
                $order->find($params);
                if ($order->allowCancel()) {
                    // cancel and change state to cancelled
                    $found->cancel(1 * $this->request->params['reason']);
                    // after $found->cancel(), cancel_time and state properties populated with data

                    $order->changeState(Order::STATE_CANCELLED);

                    // send response
                    $return =  $this->response->send([
                        'transaction'  => "$found->id",
                        'cancel_time' => Format::datetime2timestamp($found->cancel_time),
                        'state'       => $found->state,
                    ]);
                } else {
                    // todo: If cancelling after performing transaction is not possible, then return error -31007
                    $return =  $this->response->error(
                        PaycomException::ERROR_COULD_NOT_CANCEL,
                        'Could not cancel transaction. Order is delivered/Service is completed.'
                    );
                }
                break;
        }
        return  $return;
    }

    private function ChangePassword()
    {
        // if current password specified as new, then send error
        if ($this->merchant->config['key'] == $this->request->params['password']) {
            return $this->response->error(PaycomException::ERROR_INSUFFICIENT_PRIVILEGE, 'Insufficient privilege. Incorrect new password.');
        }

        // validate, password is specified, otherwise send error
        if (!isset($this->request->params['password']) || !trim($this->request->params['password'])) {
            return $this->response->error(PaycomException::ERROR_INVALID_ACCOUNT, 'New password not specified.', 'password');
        }

        // todo: Implement saving password into data store or file
        // example implementation, that saves new password into file specified in the configuration
//        if (!file_put_contents($this->config['keyFile'], $this->request->params['password'])) {
//            $this->response->error(PaycomException::ERROR_INTERNAL_SYSTEM, 'Internal System Error.');
//        }

        // if control is here, then password is saved into data store
        // send success response
        return $this->response->send(['success' => true]);
    }

    private function GetStatement()
    {
        // validate 'from'
        if (!isset($this->request->params['from'])) {
            return $this->response->error(PaycomException::ERROR_INVALID_ACCOUNT, 'Incorrect period.', 'from');
        }

        // validate 'to'
        if (!isset($this->request->params['to'])) {
            return $this->response->error(PaycomException::ERROR_INVALID_ACCOUNT, 'Incorrect period.', 'to');
        }

        // validate period
        if (1 * $this->request->params['from'] >= 1 * $this->request->params['to']) {
            return $this->response->error(PaycomException::ERROR_INVALID_ACCOUNT, 'Incorrect period. (from >= to)', 'from');
        }

        // get list of transactions for specified period
        $transaction  = new Transaction();
        $transactions = $transaction->report($this->request->params['from'], $this->request->params['to']);

        // send results back
        return $this->response->send(['transactions' => $transactions]);
    }
}
