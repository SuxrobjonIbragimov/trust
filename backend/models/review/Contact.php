<?php

namespace backend\models\review;

use backend\modules\policy\models\HandBookIns;
use common\components\behaviors\AuthorBehavior;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "{{%contact}}".
 *
 * @property int $id
 * @property string $full_name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $policy_series
 * @property string|null $policy_number
 * @property string|null $policy_issue_date
 * @property string $subject
 * @property string $message
 * @property int|null $user_id
 * @property int|null $type
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property string $bot_group_chat_id
 * @property string $fullPolicyNumber
 *
 * @property User $createdBy
 * @property User $deletedBy
 * @property User $updatedBy
 * @property User $user
 */
class Contact extends \yii\db\ActiveRecord
{
    const TYPE_CONTACT = 0;
    const TYPE_CLAIM = 1;
    const TYPE_FEEDBACK = 2;

    const STATUS_NEW = 1;
    const STATUS_DELETED = -1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_DONE = 3;
    const STATUS_CANCEL = -2;


    const SCENARIO_CONTACT = 'contact';
    const SCENARIO_CLAIM = 'claim';
    const SCENARIO_FEEDBACK = 'feedback';

    public $verifyCode;
//    public $bot_group_chat_id = "-1001552601477";
    public $bot_group_chat_id = CHAT_ID_ME;

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
        return '{{%contact}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'phone', 'policy_series', 'policy_number', 'message'], 'required', 'on' => self::SCENARIO_CLAIM],
            [['full_name', 'phone', 'email', 'message'], 'required', 'on' => self::SCENARIO_CONTACT],
            [['full_name', 'phone', 'message', ], 'required', 'on' => self::SCENARIO_FEEDBACK],
            [['id', ], 'safe'],
            [['message', ], 'string'],
            [['user_id', 'weight', 'status', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['type', 'created_at', 'updated_at', 'deleted_at', ], 'safe'],
            [['full_name', 'phone', 'policy_series', 'policy_number', 'policy_issue_date','email'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],

            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'full_name' => Yii::t('model', 'Full Name'),
            'phone' => Yii::t('model', 'Phone'),
            'email' => Yii::t('model', 'Email'),
            'policy_series' => Yii::t('model', 'Policy series'),
            'policy_number' => Yii::t('model', 'Policy number'),
            'policy_issue_date' => Yii::t('model', 'Policy issue date'),
            'message' => Yii::t('model', 'Message'),
            'user_id' => Yii::t('model', 'User ID'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'verifyCode' => Yii::t('model', 'Verify Code'),
            'created_by' => Yii::t('model', 'Created By'),
            'updated_by' => Yii::t('model', 'Updated By'),
            'deleted_by' => Yii::t('model', 'Deleted By'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
            'deleted_at' => Yii::t('model', 'Deleted At'),
        ];
    }

    /**
     * @return string
     */
    public function getFullPolicyNumber()
    {
        $policy_number = $this->policy_series ?: null;
        $policy_number .= (!empty($this->policy_number)) ? ' '.$this->policy_number : null;
        return $policy_number;
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[DeletedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'deleted_by']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    /**
     * @return string[]
     */
    public static function getPolicySeriesList ()
    {
        $result = [];
        $array = HandBookIns::getPolicySeriesList();
        if (!empty($array)) {
            foreach ($array as $item) {
                $result[$item] = $item;
            }
        } else {
            $title = Yii::t('slug','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            _send_error($title, json_encode($result,JSON_UNESCAPED_UNICODE));
            throw new BadRequestHttpException($title);
        }
        return $result;
    }


    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getContactTypeArray($status = null)
    {
        $array = [
            self::TYPE_CONTACT => Yii::t('frontend', 'Contact'),
            self::TYPE_CLAIM => Yii::t('frontend', 'Claim'),
            self::TYPE_FEEDBACK => Yii::t('frontend', 'Feedback'),
        ];
        return $status === null ? $array : $array[$status];
    }

    /**
     * Status Name
     * @return string
     */
    public function getContactTypeName()
    {
        $array = [
            self::TYPE_CONTACT => '<b>' . self::getContactTypeArray(self::TYPE_CONTACT) . '</b>',
            self::TYPE_CLAIM => '<b>' . self::getContactTypeArray(self::TYPE_CLAIM) . '</b>',
            self::TYPE_FEEDBACK => '<b>' . self::getContactTypeArray(self::TYPE_FEEDBACK) . '</b>',
        ];

        return isset($array[$this->type]) ? $array[$this->type] : $this->type;
    }

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getStatusArray($status = null)
    {
        $array = [
            self::STATUS_NEW => Yii::t('frontend', 'New'),
            self::STATUS_IN_PROGRESS => Yii::t('frontend', 'In progress'),
            self::STATUS_DONE => Yii::t('frontend', 'Done'),
            self::STATUS_CANCEL => Yii::t('frontend', 'Canceled'),
        ];
        return $status === null ? $array : $array[$status];
    }

    /**
     * Status Name
     * @return string
     */
    public function getStatusName()
    {
        $array = [
            self::STATUS_NEW => '<span class="text-bold text-red">' . self::getStatusArray(self::STATUS_NEW) . '</span>',
            self::STATUS_IN_PROGRESS => '<span class="text-bold text-aqua">' . self::getStatusArray(self::STATUS_IN_PROGRESS) . '</span>',
            self::STATUS_DONE => '<span class="text-bold text-success">' . self::getStatusArray(self::STATUS_DONE) . '</span>',
            self::STATUS_CANCEL => '<span class="text-bold text-warning">' . self::getStatusArray(self::STATUS_CANCEL) . '</span>',
        ];

        return isset($array[$this->status]) ? $array[$this->status] : $this->status;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->user_id = Yii::$app->user->getId();
        }
        if (!empty($this->phone)) {
            $this->phone = clear_phone_full($this->phone);
        }
        if (!Yii::$app->user->can('accessDashboard')) {
            $blackWords = _blackWords();
            $noNeedError = false;
            foreach ($blackWords as $blackWord) {
                $str_tmp = array($this->full_name, $this->subject, $this->message);
                $str_tmp = implode(',', $str_tmp);
                $blackWord_ar = explode(mb_strtoupper($blackWord),mb_strtoupper($str_tmp));
                if (count($blackWord_ar)>=2) {
                    $noNeedError = true;
                }
            }
            if ($noNeedError) {
                return false;
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if (!empty($changedAttributes) && !isset($changedAttributes['status'])) {
            $this->sendNewContactMessage();
        }
        return true;
    }

    /**
     *
     */
    public function sendNewContactMessage()
    {
        $groupID = FEEDBACK_GROUP_CHAT_ID !== null ? FEEDBACK_GROUP_CHAT_ID : CHAT_ID_ME;
        $text = Yii::t('frontend',"📩 {type} qoldirildi\n🆔 {id}\n👤 {full_name}\n📞 {phone}",[
            'id' => $this->id,
            'type' => $this->getContactTypeName(),
            'full_name' => $this->full_name,
            'phone' => mask_to_phone_format($this->phone),
        ]);


        if ($this->type == self::TYPE_CLAIM) {
            $full_str = $this->fullPolicyNumber;
            $text .= "\n📃 {$full_str}";
        }

        if ($this->subject) {
            $full_str = $this->subject;
            $text .= "\nℹ️ {$full_str}";
        }

        if ($this->message) {
            $full_str = $this->message;
            $text .= "\n✍ {$full_str}";
        }

        Yii::warning("\n\n$text\n");

        $res = sendTelegramData('sendMessage', [
            'chat_id' => $groupID,
            'text' => $text,
            'parse_mode' => 'HTML'
        ],BOT_TOKENT_SALE,'contact_'.$this->type);

    }

    public function getAppInfo()
    {

        $result = [
            'status' => false,
            'response' => null,
        ];
        $model = self::findOne(['id' => $this->id,]);

        if (!empty($model)) {
            $result['response'] = [
                'id' => $model->id,
                'full_name' => $model->full_name,
                'message' => $model->message,
                'status' => $model->getStatusName(),
                'created_at' => date('d.m.Y', strtotime($model->created_at)),
                'updated_at' => date('d.m.Y', strtotime($model->updated_at)),
            ];
            $result['status'] = true;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getStatistics()
    {
        $response = null;
        $response[self::STATUS_NEW] = self::find()->where(['status' => self::STATUS_NEW])->count();
        $response[self::STATUS_IN_PROGRESS] = self::find()->where(['status' => self::STATUS_IN_PROGRESS])->count();
        $response[self::STATUS_DONE] = self::find()->where(['status' => self::STATUS_DONE])->count();
        $response[self::STATUS_DONE] += self::find()->where(['status' => self::STATUS_CANCEL])->count();
        $response['all'] = ($response[self::STATUS_NEW]+$response[self::STATUS_IN_PROGRESS]+$response[self::STATUS_DONE]);

        return $response;
    }


}
