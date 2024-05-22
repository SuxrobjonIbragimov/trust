<?php

namespace common\library\click\exceptions;

/**
 * @name ClickException class
 * @example
 *      throw new ClickException(
 *          'THE_DESCRIPTION_OF_EXCEPTION',
 *          THE_CODE_OF_EXCEPTION
 *      );
 */
class ClickException extends \Exception{

    const ERROR_CANCEL_PAYMENT = -5017;

    /** @var ERROR_NO
     * Успешный запрос
     */
    const ERROR_NO = 0;

    /** @var ERROR_MINUS_ONE */
    const ERROR_MINUS_ONE = -1;

    /** @var ERROR_SIGN_CHECK_FAILED
     * Ошибка проверки подписи
     */
    const ERROR_SIGN_CHECK_FAILED = -1;

    /** @var ERROR_INCORRECT_AMOUNT
     * Неверная сумма оплаты
     */
    const ERROR_INCORRECT_AMOUNT = -2;

    /** @var ERROR_ACTION_NOT_FOUND
     * Запрашиваемое действие не найдено
     */
    const ERROR_ACTION_NOT_FOUND = -3;

    /** @var ERROR_ALREADY_PAID
     * Транзакция ранее была подтверждена (при попытке подтвердить или отменить ранее подтвержденную транзакцию)
     */
    const ERROR_ALREADY_PAID = -4;

    /** @var ERROR_USER_DOES_NOT_EXIST
     * Не найдет пользователь/заказ (проверка параметра merchant_trans_id)
     */
    const ERROR_USER_DOES_NOT_EXIST = -5;

    /** @var ERROR_TRANSACTION_DOES_NOT_EXIST
     * Не найдена транзакция (проверка параметра merchant_prepare_id)
     */
    const ERROR_TRANSACTION_DOES_NOT_EXIST = -6;

    /** @var ERROR_FAILED_TO_UPDATE_USER
     * Ошибка при изменении данных пользователя (изменение баланса счета и т.п.)
     */
    const ERROR_FAILED_TO_UPDATE_USER = -7;

    /** @var ERROR_IN_REQUEST_FROM_CLICK
     * Ошибка в запросе от CLICK (переданы не все параметры и т.п.)
     */
    const ERROR_IN_REQUEST_FROM_CLICK = -8;

    /** @var ERROR_TRANSACTION_CANCELLED
     * Транзакция ранее была отменена (При попытке подтвердить или отменить ранее отмененную транзакцию)
     */
    const ERROR_TRANSACTION_CANCELLED = -9;

    /** @var ERROR_CANCEL_TRANSACTION */
    const ERROR_CANCEL_TRANSACTION = -5017;
    /** @var ERROR_INTERNAL_SYSTEM */
    const ERROR_INTERNAL_SYSTEM = -32400;
    /** @var ERROR_INSUFFICIENT_PRIVILEGE */
    const ERROR_INSUFFICIENT_PRIVILEGE = -32504;
    /** @var ERROR_INVALID_JSON_RPC_OBJECT */
    const ERROR_INVALID_JSON_RPC_OBJECT = -32600;
    /** @var ERROR_METHOD_NOT_FOUND */
    const ERROR_METHOD_NOT_FOUND = -32601;
    /** @var ERROR_INVALID_AMOUNT */
    const ERROR_INVALID_AMOUNT = -31001;
    /** @var ERROR_TRANSACTION_NOT_FOUND */
    const ERROR_TRANSACTION_NOT_FOUND = -31003;
    /** @var ERROR_INVALID_ACCOUNT */
    const ERROR_INVALID_ACCOUNT = -31050;
    /** @var ERROR_COULD_NOT_CANCEL */
    const ERROR_COULD_NOT_CANCEL = -31007;
    /** @var ERROR_COULD_NOT_PERFORM */
    const ERROR_COULD_NOT_PERFORM = -31008;
    /** @var error array-like */
    public $error;

    /**
     * ClickException contructor
     * @param error_note string
     * @param error_code integer
     */
    public function __construct($error_note, $error_code)
    {
        $this->error_note = $error_note;
        $this->error_code = $error_code;

        $this->error = ['error_code' => $this->error_code];

        if ($this->error_note) {
            $this->error['error_note'] = $this->error_note;
        }
    }

    /**
     * @name error method
     * @return error array-like
     */
    public function error()
    {
        return $this->error;
    }
}