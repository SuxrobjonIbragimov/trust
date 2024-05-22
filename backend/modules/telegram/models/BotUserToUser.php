<?php

namespace backend\modules\telegram\models;

use backend\models\document\Contractor;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%bot_user_to_contractor}}".
 *
 * @property int $bot_user_id
 * @property int $user_id
 * @property int|null $created_at
 *
 * @property BotUser $botUser
 * @property User $user
 */
class BotUserToUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bot_user_to_user}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                // if you're using datetime instead of UNIX timestamp:
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => false,
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bot_user_id', 'user_id'], 'required'],
            [['bot_user_id', 'user_id', 'created_at'], 'default', 'value' => null],
            [['bot_user_id', 'user_id', 'created_at'], 'integer'],
            [['bot_user_id', 'user_id'], 'unique', 'targetAttribute' => ['bot_user_id', 'user_id']],
            [['bot_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => BotUser::className(), 'targetAttribute' => ['bot_user_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bot_user_id' => Yii::t('telegram', 'Bot User ID'),
            'user_id' => Yii::t('telegram', 'Contractor ID'),
            'created_at' => Yii::t('telegram', 'Created At'),
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
     * Gets query for [[Contractor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
