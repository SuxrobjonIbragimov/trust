<?php

namespace backend\modules\telegram\models;

use backend\models\document\Contractor;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%bot_user_to_contractor}}".
 *
 * @property int $bot_user_id
 * @property int $contractor_id
 * @property int|null $created_at
 *
 * @property BotUser $botUser
 */
class BotUserLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bot_user_log}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bot_user_id', ], 'required'],
            [['data', ], 'string'],
            [['bot_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => BotUser::className(), 'targetAttribute' => ['bot_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bot_user_id' => Yii::t('telegram', 'Bot User ID'),
            'data' => Yii::t('telegram', 'Data'),
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

}
