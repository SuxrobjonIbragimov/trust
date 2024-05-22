<?php

namespace common\library\payment\models;

use backend\modules\policy\models\PolicyOrder;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%payment_transactions}}".
 *
 * @property int $id
 * @property int $order_id
 * @property string $currency
 * @property string $total
 * @property string $amount
 * @property string $action
 * @property int $service_transaction_id
 * @property string $merchant_trans_id
 * @property int $merchant_prepare_id
 * @property int $merchant_confirm_id
 * @property int $service_id
 * @property int $click_paydoc_id
 * @property string $sign_timestamp
 * @property string $sign_time
 * @property string $create_time
 * @property string $perform_time
 * @property string $cancel_time
 * @property string $sign_string
 * @property int $error
 * @property string $error_note
 * @property int $user_id
 * @property string $note
 * @property int $auto_capture
 * @property string $type
 * @property string $status
 * @property string $status_note
 * @property string $reason
 * @property string $receivers
 * @property string $payment_methods
 * @property string $request
 * @property string $response
 * @property int $created
 * @property int $modified
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PolicyOrder $order
 * @property User $user
 */
class PaymentTransaction extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                // if you're using datetime instead of UNIX timestamp:
                'value' => _date_current(),
            ],
        ];
    }

    const NO_ERROR = 0;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment_transactions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'merchant_prepare_id', 'merchant_confirm_id', 'service_id', 'click_paydoc_id', 'user_id', 'created', 'modified'], 'integer'],
            [['total', 'amount'], 'number'],
            [['service_transaction_id', 'sign_string', 'error_note', 'note', 'payment_methods', 'receivers'], 'string'],
            [['sign_timestamp', 'status', 'auto_capture', 'create_time', 'cancel_time', 'perform_time', 'error', 'request', 'response', 'created_at', 'updated_at', 'reason'], 'safe'],
            [['currency'], 'string', 'max' => 3],
            [['merchant_trans_id', 'sign_time', 'status_note'], 'string', 'max' => 255],
            [['type', ], 'string', 'max' => 50],
            [['perform_time', ], 'default', 'value' => 0],
            [['cancel_time', ], 'default', 'value' => 0],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => PolicyOrder::className(), 'targetAttribute' => ['order_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('payment', 'ID'),
            'order_id' => Yii::t('payment', 'Order ID'),
            'currency' => Yii::t('payment', 'Currency'),
            'total' => Yii::t('payment', 'Total'),
            'amount' => Yii::t('payment', 'Amount'),
            'service_transaction_id' => Yii::t('payment', 'Service Transaction ID'),
            'merchant_trans_id' => Yii::t('payment', 'Merchant Trans ID'),
            'merchant_prepare_id' => Yii::t('payment', 'Merchant Prepare ID'),
            'merchant_confirm_id' => Yii::t('payment', 'Merchant Confirm ID'),
            'service_id' => Yii::t('payment', 'Service ID'),
            'click_paydoc_id' => Yii::t('payment', 'Click Paydoc ID'),
            'sign_time' => Yii::t('payment', 'Sign Time'),
            'sign_string' => Yii::t('payment', 'Sign String'),
            'error' => Yii::t('payment', 'Error'),
            'error_note' => Yii::t('payment', 'Error Note'),
            'user_id' => Yii::t('payment', 'User ID'),
            'note' => Yii::t('payment', 'Note'),
            'type' => Yii::t('payment', 'Type'),
            'status' => Yii::t('payment', 'Status'),
            'status_note' => Yii::t('payment', 'Status Note'),
            'payment_methods' => Yii::t('payment', 'Payment methods'),
            'created' => Yii::t('payment', 'Created'),
            'modified' => Yii::t('payment', 'Modified'),
            'created_at' => Yii::t('payment', 'Created At'),
            'updated_at' => Yii::t('payment', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(PolicyOrder::className(), ['id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    const PAYMENT_TYPE_INS_MASTER_ACCOUNT = 1; // 1  Основной счет
    const PAYMENT_TYPE_INS_SECONDARY_ACCOUNT = 2; // 2  Вторичный счет
    const PAYMENT_TYPE_INS_AGENT_ACCOUNT = 3; // 3  Счет агента
    const PAYMENT_TYPE_INS_CLICK = 4; // CLICK
    const PAYMENT_TYPE_INS_OSON = 5; // OSON
    const PAYMENT_TYPE_INS_UZCARD_TERMINAL = 6; //TERMINAL
    const PAYMENT_TYPE_INS_FROM_TERMINATED_CONTRACT = 7; //7  С расторгнутого договора
    const PAYMENT_TYPE_INS_PAYME = 8; // PAYME
    const PAYMENT_TYPE_INS_APELSIN = 9; // APELSIN
    const PAYMENT_TYPE_INS_PAYNET = 10;

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getPaymentTypeInsArray($status = null)
    {
        $array = [
            self::PAYMENT_TYPE_INS_MASTER_ACCOUNT => self::PAYMENT_TYPE_INS_MASTER_ACCOUNT,
            self::PAYMENT_TYPE_INS_SECONDARY_ACCOUNT => self::PAYMENT_TYPE_INS_SECONDARY_ACCOUNT,
            self::PAYMENT_TYPE_INS_AGENT_ACCOUNT => self::PAYMENT_TYPE_INS_AGENT_ACCOUNT,
            self::PAYMENT_TYPE_INS_PAYME => self::PAYMENT_TYPE_INS_PAYME,
            self::PAYMENT_TYPE_INS_CLICK => self::PAYMENT_TYPE_INS_CLICK,
            self::PAYMENT_TYPE_INS_OSON => self::PAYMENT_TYPE_INS_OSON,
            self::PAYMENT_TYPE_INS_FROM_TERMINATED_CONTRACT => self::PAYMENT_TYPE_INS_FROM_TERMINATED_CONTRACT,
            self::PAYMENT_TYPE_INS_UZCARD_TERMINAL => self::PAYMENT_TYPE_INS_UZCARD_TERMINAL,
            self::PAYMENT_TYPE_INS_APELSIN => self::PAYMENT_TYPE_INS_APELSIN,
            self::PAYMENT_TYPE_INS_PAYNET => self::PAYMENT_TYPE_INS_PAYNET,
        ];

        return $status === null ? $array : $array[$status];
    }

    const PAYMENT_TYPE_PAYME = 'payme';
    const PAYMENT_TYPE_CLICK = 'click';
    const PAYMENT_TYPE_APELSIN = 'apelsin';
    const PAYMENT_TYPE_OSON = 'oson';
    const PAYMENT_TYPE_TEST = 'test';
    const PAYMENT_TYPE_TEST_1 = 'test-1';

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getPaymentTypeArray($status = null)
    {
        $array = [
            self::PAYMENT_TYPE_PAYME => Yii::t('slug','Payme'),
            self::PAYMENT_TYPE_CLICK => Yii::t('slug','Click'),
            self::PAYMENT_TYPE_APELSIN => Yii::t('slug','APELSIN'),
            self::PAYMENT_TYPE_OSON => Yii::t('slug','OSON'),
            self::PAYMENT_TYPE_TEST => Yii::t('slug','TEST'),
            self::PAYMENT_TYPE_TEST_1 => Yii::t('slug','TEST'),
        ];

        return $status === null ? $array : $array[$status];
    }

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getPaymentTypeImgArray($status = null)
    {
        $array = [
            self::PAYMENT_TYPE_PAYME => Html::img('@web/payment/payme.png', ['class' => 'w-100 payment-type-img']),
            self::PAYMENT_TYPE_CLICK => Html::img('@web/payment/click.png', ['class' => 'w-100 payment-type-img']),
        ];

        return $status === null ? $array : $array[$status];
    }

    /**
     * PaymentType Name
     * @return string
     */
    public function getPaymentTypeImgName()
    {
        $array = [
            self::PAYMENT_TYPE_PAYME => self::getPaymentTypeImgArray(self::PAYMENT_TYPE_PAYME),
            self::PAYMENT_TYPE_CLICK => self::getPaymentTypeImgArray(self::PAYMENT_TYPE_CLICK),
            self::PAYMENT_TYPE_APELSIN => self::getPaymentTypeImgArray(self::PAYMENT_TYPE_APELSIN),
            self::PAYMENT_TYPE_OSON => self::getPaymentTypeImgArray(self::PAYMENT_TYPE_OSON),
            self::PAYMENT_TYPE_TEST => self::getPaymentTypeImgArray(self::PAYMENT_TYPE_TEST),
            self::PAYMENT_TYPE_TEST_1 => self::getPaymentTypeImgArray(self::PAYMENT_TYPE_TEST_1),
        ];

        return isset($array[$this->type]) ? $array[$this->type] : $this->type;
    }


    /**
     * Payment Statuses
     */
    const STATUS_PAYMENT_NOT = 0;
    const STATUS_PAYMENT_WAIT = 1;
    const STATUS_PAYMENT_PAID = 2;
    const STATUS_PAYMENT_CANCELED = -1;
    const STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE = -2;
    const STATUS_PAYMENT_ERROR = -3;

    /**
     * Payment List
     * @param integer|null $payment
     * @return array|string
     */
    public static function getPaymentStatusArray($payment = null)
    {
        $array = [
            self::STATUS_PAYMENT_NOT => Yii::t('slug','Не оплачено'),
            self::STATUS_PAYMENT_WAIT => Yii::t('slug','Ожидается оплата'),
            self::STATUS_PAYMENT_PAID => Yii::t('slug','Оплачено'),
            self::STATUS_PAYMENT_CANCELED => Yii::t('slug','Платеж отменен'),
            self::STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE => Yii::t('slug','Платеж отменена после завершения'),
            self::STATUS_PAYMENT_ERROR => Yii::t('slug','PAY_ERROR'),
        ];

        return $payment === null ? $array : $array[$payment];
    }

    /**
     * Payment Name
     * @return string
     */
    public function getPaymentStatusName()
    {
        $array = [
            self::STATUS_PAYMENT_NOT => '<span class="text-bold text-red">' . self::getPaymentStatusArray(self::STATUS_PAYMENT_NOT) . '</span>',
            self::STATUS_PAYMENT_WAIT => '<span class="text-bold text-yellow">' . self::getPaymentStatusArray(self::STATUS_PAYMENT_WAIT) . '</span>',
            self::STATUS_PAYMENT_PAID => '<span class="text-bold text-green">' . self::getPaymentStatusArray(self::STATUS_PAYMENT_PAID) . '</span>',
            self::STATUS_PAYMENT_CANCELED => '<span class="text-bold text-orange">' . self::getPaymentStatusArray(self::STATUS_PAYMENT_CANCELED) . '</span>',
            self::STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE => '<span class="text-bold text-orange">' . self::getPaymentStatusArray(self::STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE) . '</span>',
            self::STATUS_PAYMENT_ERROR => '<span class="text-bold text-orange">' . self::getPaymentStatusArray(self::STATUS_PAYMENT_ERROR) . '</span>',
        ];

        return isset($array[$this->status]) ? $array[$this->status] : $this->status;
    }


    /**
     * @param $type
     * @return int|null
     */
    public static function getInsPaymentType($type)
    {
        $payment_type_ins = $type;
        switch ($type) {
            case self::PAYMENT_TYPE_PAYME :
                $payment_type_ins = self::PAYMENT_TYPE_INS_PAYME;
                break;
            case self::PAYMENT_TYPE_CLICK :
                $payment_type_ins = self::PAYMENT_TYPE_INS_CLICK;
                break;
            case self::PAYMENT_TYPE_APELSIN :
                $payment_type_ins = self::PAYMENT_TYPE_INS_APELSIN;
                break;
            case self::PAYMENT_TYPE_OSON :
                $payment_type_ins = self::PAYMENT_TYPE_INS_OSON;
                break;
            case self::PAYMENT_TYPE_TEST :
            case self::PAYMENT_TYPE_TEST_1 :
                $payment_type_ins = self::PAYMENT_TYPE_INS_AGENT_ACCOUNT;
        }
        return $payment_type_ins;
    }

    /**
     * @param $type
     * @return int|null
     */
    public static function getPaymentTypeFromInsType($type)
    {
        $payment_type = $type;
        switch ($type) {
            case self::PAYMENT_TYPE_INS_PAYME :
                $payment_type = self::PAYMENT_TYPE_PAYME;
                break;
            case self::PAYMENT_TYPE_INS_CLICK :
                $payment_type = self::PAYMENT_TYPE_CLICK;
                break;
            case self::PAYMENT_TYPE_INS_APELSIN :
                $payment_type = self::PAYMENT_TYPE_APELSIN;
                break;
            case self::PAYMENT_TYPE_INS_OSON :
                $payment_type = self::PAYMENT_TYPE_OSON;
                break;
            case self::PAYMENT_TYPE_TEST :
            case self::PAYMENT_TYPE_TEST_1 :
                $payment_type = self::PAYMENT_TYPE_INS_AGENT_ACCOUNT;
        }
        return $payment_type;
    }


    /**
     * @return bool
     */
    public function cancelPayment()
    {
        if ($this->isTransactionCancelable()) {
            $this->status = self::STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE;
            $this->cancel_time = time();
            return $this->save();
        }
        return false;
    }

    public static function cancelableMethods()
    {
        return [
            self::PAYMENT_TYPE_OSON,
        ];
    }
    /**
     * @return bool
     */
    public function isTransactionCancelable ()
    {
        if (in_array($this->type,self::cancelableMethods())) {
            return true;
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        return parent::beforeSave($insert);
    }


    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (array_key_exists('status',$changedAttributes)) {
            $this->order->payment_status = $this->status;
            $this->order->payment_type = $this->type;
            if (!$this->order->save()) {
                $title = "PaymentTransactionOrder not saved";
                Yii::warning("\n\n\n{$title}");
                Yii::warning($this->order->errors);
                _send_error($title,json_encode($this->order->errors, JSON_UNESCAPED_UNICODE));
            }
        }
        return true;
    }



}
