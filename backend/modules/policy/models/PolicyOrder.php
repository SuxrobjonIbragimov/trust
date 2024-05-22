<?php

namespace backend\modules\policy\models;

use common\library\payment\models\PaymentTransaction;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "{{%policy_order}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $session_id
 * @property int $revision_id
 * @property string $revision_model
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property double $total_amount
 * @property int $payment_type
 * @property int $payment_status
 * @property int $status
 * @property string $comment
 * @property int $created_at
 * @property int $updated_at
 *
 * @property string $productName
 *
 * @property PaymentTransaction[] $paymentTransactions
 * @property PaymentTransaction $paymentTransaction
 * @property User $user
 * @property PolicyOsgo $policyModel
 */
class PolicyOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%policy_order}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'revision_id', 'payment_status', 'status', 'created_at', 'updated_at'], 'integer'],
            [['total_amount'], 'number'],
            [['comment'], 'string'],
            [['payment_type'], 'safe'],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['session_id', 'revision_model', 'first_name', 'last_name', 'phone'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('policy','ID'),
            'user_id' => Yii::t('policy','User ID'),
            'session_id' => Yii::t('policy','Session ID'),
            'revision_id' => Yii::t('policy','Revision ID'),
            'revision_model' => Yii::t('policy','Revision Model'),
            'first_name' => Yii::t('policy','First Name'),
            'last_name' => Yii::t('policy','Last Name'),
            'phone' => Yii::t('policy','Phone'),
            'total_amount' => Yii::t('policy','Total Amount'),
            'payment_type' => Yii::t('policy','Payment Type'),
            'payment_status' => Yii::t('policy','Payment Status'),
            'status' => Yii::t('policy','Status'),
            'comment' => Yii::t('policy','Comment'),
            'created_at' => Yii::t('policy','Created At'),
            'updated_at' => Yii::t('policy','Updated At'),
        ];
    }

    const STATUS_NEW = 0;
    const STATUS_DELETED = -1;
    const STATUS_FALSE = -10;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyModel()
    {
        $model = null;
        $modelId = $this->revision_id;
        $modelClassName = $this->revision_model;

        if (!empty($modelId) && !empty($modelClassName)) {
            $modelClassName = 'backend\modules\policy\models\\'.$modelClassName;
            $model = $modelClassName::findOne($modelId);
        }
        return $model;
    }


    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->user_id = Yii::$app->user->getId();
            $this->session_id = Yii::$app->session->getId();
        }
        return parent::beforeSave($insert);
    }


    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        Yii::warning("\n\n POLICY ORDER UPDATED");
        Yii::warning($changedAttributes);
        Yii::warning("\n\n POLICY ORDER PAYMENT STATUS - $this->payment_status");
        if (array_key_exists('payment_status',$changedAttributes) && ($this->payment_status == PaymentTransaction::STATUS_PAYMENT_PAID)) {
            $this->policyModel->confirmPayment();
        } elseif (array_key_exists('payment_status',$changedAttributes) && ($this->payment_status == PaymentTransaction::STATUS_PAYMENT_CANCELED || $this->payment_status == PaymentTransaction::STATUS_PAYMENT_CANCELLED_AFTER_COMPLETE)) {
            $this->policyModel->cancelPayment();
        }
        return true;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        $product_name = 'TRAVEL';
        if ($this->revision_model) {
            $product_name = mb_strtoupper(mb_substr($this->revision_model,6));
        }
        return $product_name;
    }
}
