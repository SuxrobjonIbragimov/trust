<?php

namespace common\library\sms\models;

use app\common\components\behaviors\AuthorBehavior;
use common\library\sms\SMSApiPlayMobile;
use app\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%sms}}".
 *
 * @property int $id
 * @property string $recipient
 * @property string $message_id
 * @property string $code
 * @property string $text
 * @property string $priority
 * @property string $type
 * @property string $status
 * @property string $error_code
 * @property string $error_description
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Sms extends \yii\db\ActiveRecord
{
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_REALTIME = 'realtime';

    const TYPE_OPT = 'otp';
    const TYPE_ADMIN = 'admin';

    const STATUS_VERIFIED = 1;
    const STATUS_NOT_VERIFIED = 0;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                // if you're using datetime instead of UNIX timestamp:
                'value' => _date_current(),
            ],
            AuthorBehavior::className(),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recipient', ], 'required'],
            [['text', 'error_description'], 'string'],
            [['created_at', 'updated_at', 'code', 'status', 'type', ], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
            [['status', ], 'default', 'value' => self::STATUS_NOT_VERIFIED],
            [['type', ], 'default', 'value' => self::TYPE_OPT],
            [['recipient', 'message_id', 'priority', 'error_code'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('slug','ID'),
            'recipient' => Yii::t('slug','Номер телефона'),
            'message_id' => Yii::t('slug','Message ID'),
            'text' => Yii::t('slug','Text'),
            'priority' => Yii::t('slug','Priority'),
            'status' => Yii::t('slug','Status'),
            'error_code' => Yii::t('slug','Error Code'),
            'error_description' => Yii::t('slug','Error Description'),
            'created_at' => Yii::t('slug','Created At'),
            'updated_at' => Yii::t('slug','Updated At'),
            'created_by' => Yii::t('slug','Created By'),
            'updated_by' => Yii::t('slug','Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }


    /**
     * @param $phone
     * @param null $message
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function sendSMS($phone, $message=null)
    {
        $sms = new SMSApiPlayMobile();
        $data = [
            [
                'recipient' => clear_phone_full($phone),
                'priority' => Sms::PRIORITY_REALTIME,
                'text' => $message,
            ],
        ];
        $sms->prepareSMS($data);
        if ($sms->sendRequest()) {
            return true;
        }

        return false;
    }
}
