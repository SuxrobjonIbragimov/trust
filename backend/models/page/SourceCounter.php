<?php

namespace backend\models\page;

use backend\modules\translatemanager\models\Language;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;
use yii\web\Cookie;

/**
 * This is the model class for table "{{%source_counter}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $lang
 * @property string|null $redirect_url
 * @property int|null $count
 * @property int|null $weight
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class SourceCounter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%source_counter}}';
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
            [['name', 'code', 'lang', 'redirect_url'], 'required',],
            [['weight', 'status', ], 'default', 'value' => null],
            [['count', ], 'default', 'value' => 0],
            [['count', 'weight', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code', 'lang', 'redirect_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'name' => Yii::t('model', 'Name'),
            'code' => Yii::t('model', 'Code'),
            'lang' => Yii::t('model', 'Lang'),
            'redirect_url' => Yii::t('model', 'Redirect Url'),
            'count' => Yii::t('model', 'Count'),
            'weight' => Yii::t('model', 'Weight'),
            'status' => Yii::t('model', 'Status'),
            'created_at' => Yii::t('model', 'Created At'),
            'updated_at' => Yii::t('model', 'Updated At'),
        ];
    }

    /**
     * @return false
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        $this->status = self::STATUS_DELETED;
        $this->save(false);

        return false;
    }

    /**
     * Status
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_DELETED = -1;

    /**
     * Status Array
     * @param integer|null $status
     * @return array|string
     */
    public static function getStatusArray($status = null)
    {
        $array = [
            self::STATUS_INACTIVE => Yii::t('model', 'Inactive'),
            self::STATUS_ACTIVE => Yii::t('model', 'Active'),
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
            self::STATUS_ACTIVE => '<span class="text-bold text-green">' . self::getStatusArray(self::STATUS_ACTIVE) . '</span>',
            self::STATUS_INACTIVE => '<span class="text-bold text-red">' . self::getStatusArray(self::STATUS_INACTIVE) . '</span>',
        ];

        return isset($array[$this->status]) ? $array[$this->status] : '';
    }

    /**
     * @param $code
     * @return array
     */
    public static function counterSource($code)
    {
        $sourceModel = (is_numeric($code)) ? self::findOne(['id' => $code]) : self::findOne(['code' => $code]);
        $url = '/';
        $sid = null;
        $lang = Yii::$app->language;
        if (!empty($sourceModel)) {
            $session = Yii::$app->session;
            $sourceModel->updateCounters(['count' => 1]);
            $langModel = Language::findOne(['language' => $sourceModel->lang]);
            $lang = !empty($langModel->language_id) ? $langModel->language_id : $lang;
            $url = $sourceModel->redirect_url;
            Yii::$app->language = $lang;
            $sid = $sourceModel->id;
            Yii::$app->session->set('source_id', $sid);

            $session['source_id_sess'] = [
                'number' => $sid,
                'lifetime' => 3600*24*365,
            ];
            $cookie = new Cookie([
                'name' => 'source_id',
                'domain' => '',
                'value' => $sid,
                'expire' => time() + 86400 * 365
            ]);
            Yii::$app->response->cookies->add($cookie);

            $cookie = new Cookie([
                'name' => 'language',
                'domain' => '',
                'value' => $lang,
                'expire' => time() + 86400 * 30
            ]);
            Yii::$app->response->cookies->add($cookie);
        }
        $response = [
            $url,
            's_id' => $sid,
        ];
        return $response;
    }

}
