<?php

namespace common\library\request_logger\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%request_history}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $method
 * @property resource $params
 * @property resource $request
 * @property resource $response
 * @property integer $created_by
 * @property integer $updated_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $user
 */
class RequestHistory extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                // if you're using datetime instead of UNIX timestamp:
                'value' => _date_current(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => false,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%request_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', ], 'integer'],
            [['params', 'request', 'response'], 'string'],
            [['created_at', ], 'safe'],
            [['method'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('request', 'ID'),
            'user_id' => Yii::t('request', 'User ID'),
            'method' => Yii::t('request', 'Method'),
            'params' => Yii::t('request', 'Params'),
            'request' => Yii::t('request', 'Request'),
            'response' => Yii::t('request', 'Response'),
            'created_at' => Yii::t('request', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
