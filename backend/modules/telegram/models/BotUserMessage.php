<?php

namespace backend\modules\telegram\models;

use backend\modules\policy\models\PolicyOrder;
use common\components\behaviors\AuthorBehavior;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "{{%bot_user_message}}".
 *
 * @property int $id
 * @property int|null $bot_user_id
 * @property string|null $title
 * @property string|null $type
 * @property string|null $message
 * @property int|null $parent_id
 * @property string|null $image
 * @property int|null $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BotUser $botUser
 * @property BotUserMessage $parent
 * @property BotUserMessage[] $botUserMessages
 * @property User $createdBy
 * @property User $updatedBy
 */
class BotUserMessage extends \yii\db\ActiveRecord
{
    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';
    const TYPE_REPLY = 'reply';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bot_user_message}}';
    }

    /**
     * @inheritdoc
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
    public function rules()
    {
        return [
            [['bot_user_id', 'parent_id', 'status', 'created_by', 'updated_by'], 'default', 'value' => null],
            [['bot_user_id', 'parent_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['message'], 'string'],
            [['created_at', 'updated_at', 'type', ], 'safe'],
            [['title', 'type', 'image'], 'string', 'max' => 255],
            [['bot_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => BotUser::className(), 'targetAttribute' => ['bot_user_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => BotUserMessage::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'id' => Yii::t('telegram', 'ID'),
            'bot_user_id' => Yii::t('telegram', 'Bot User ID'),
            'title' => Yii::t('telegram', 'Title'),
            'type' => Yii::t('telegram', 'Type'),
            'message' => Yii::t('telegram', 'Message'),
            'parent_id' => Yii::t('telegram', 'Parent ID'),
            'image' => Yii::t('telegram', 'Image'),
            'status' => Yii::t('telegram', 'Status'),
            'created_by' => Yii::t('telegram', 'Created By'),
            'updated_by' => Yii::t('telegram', 'Updated By'),
            'created_at' => Yii::t('telegram', 'Created At'),
            'updated_at' => Yii::t('telegram', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[BotUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBotUser()
    {
        return $this->hasOne(BotUser::className(), ['id' => 'bot_user_id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(BotUserMessage::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[BotUserMessages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBotUserMessages()
    {
        return $this->hasMany(BotUserMessage::className(), ['parent_id' => 'id']);
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
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
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
        if (!empty($this->message) && $this->type == self::TYPE_OUT) {
            $this->sendMessageToUser();
        }
        return true;
    }

    public function sendMessageToUser()
    {
        $text = ($this->message);
        $bot_token = !empty(Yii::$app->params['tg.botTokenRobot']) ? Yii::$app->params['tg.botTokenRobot'] : BOT_TOKENT_SALE;
        $res = sendTelegramData('sendMessage', [
            'chat_id' => $this->botUser->t_id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ],$bot_token,'bot_admin_message');

        return true;
    }
}
