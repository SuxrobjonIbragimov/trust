<?php

namespace backend\models\comment;

use backend\behaviors\TranslateDatabaseBehavior;
use Yii;
use common\models\User;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $model
 * @property integer $model_id
 * @property integer $author_id
 * @property integer $parent_id
 * @property double $rating
 * @property string $text
 * @property string $author_name
 * @property string $author_position
 * @property string $author_image
 * @property integer $likes
 * @property integer $unlikes
 * @property string $sessions
 * @property integer $weight
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $author
 * @property Comments[] $comments
 * @property Comments[] $activeChilds
 */
class Comments extends ActiveRecord
{
    const HOME_LIMIT = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comments}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => TranslateDatabaseBehavior::className(),
                'translateAttributes' => ['text', 'author_name', 'author_position',],
                'tableName' => static::tableName(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model', 'model_id', 'author_id', 'text'], 'required'],
            [['model_id', 'author_id', 'parent_id', 'likes', 'unlikes', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['rating'], 'number'],
            [['text', 'sessions', 'author_name', 'author_position', 'author_image'], 'string'],
            [['model'], 'string', 'max' => 255],
            [['status'], 'default', 'value' => self::STATUS_NEW],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'model' => Yii::t('model', 'Model'),
            'model_id' => Yii::t('model', 'Model ID'),
            'author_id' => Yii::t('model', 'Author'),
            'parent_id' => Yii::t('model', 'Parent'),
            'rating' => Yii::t('model', 'Rating'),
            'text' => Yii::t('model', 'Text'),
            'likes' => Yii::t('model', 'Likes'),
            'unlikes' => Yii::t('model', 'Unlikes'),
            'sessions' => Yii::t('model', 'Sessions'),
            'author_name' => Yii::t('model', 'Author name'),
            'author_position' => Yii::t('model', 'Author position'),
            'author_image' => Yii::t('model', 'Author image'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return bool
     */
    public function getBtnDisabled()
    {
        if (empty($this->sessions))
            return false;

        $session = Yii::$app->session;
        if (!$session->isActive) $session->open();
        $sessionId = Yii::$app->user->isGuest ? $session->id : Yii::$app->user->id;
        return in_array($sessionId, explode(' ', $this->sessions));
    }

    /**
     * Gets query for [[NewsCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActiveChilds()
    {
        return $this->hasMany(Comments::className(), ['parent_id' => 'id'])
            ->andOnCondition(['status' => static::STATUS_ACTIVE]);
    }

    /**
     * Specialists to array
     * @return array
     */
    public static function getAuthorsList()
    {
        return ArrayHelper::map(User::find()->where(['!=', 'status', User::STATUS_DELETED])->asArray()->all(), 'id', 'email');
    }

    public static function _getHomeCommentItems()
    {
        return self::find()
            ->where(['status' => self::STATUS_ACTIVE])
            ->orderBy(['weight' => SORT_ASC, 'id' => SORT_DESC])
            ->limit(self::HOME_LIMIT)->all();
    }

    /**
     * Status
     */
    const STATUS_NEW = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = -1;

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getStatusArray($status = null)
    {
        $array = [
            self::STATUS_NEW => Yii::t('model', 'New'),
            self::STATUS_ACTIVE => Yii::t('model', 'Active'),
            self::STATUS_INACTIVE => Yii::t('model', 'Inactive'),
        ];

        return $status === null ? $array : $array[$status];
    }

    /**
     * Status Name
     * @return string
     */
    public function getStatusClass()
    {
        $array = [
            self::STATUS_NEW => 'new',
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive',
        ];

        return isset($array[$this->status]) ? $array[$this->status] : '';
    }
}
