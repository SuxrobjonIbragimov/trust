<?php

namespace backend\modules\handbook\models;

use common\components\behaviors\AuthorBehavior;
use Yii;
use yii\base\BaseObject;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\httpclient\Client;

/**
 * This is the model class for table "{{%handbook_currency}}".
 *
 * @property int $id
 * @property string $code
 * @property double $rate
 * @property string $date
 * @property string $date_time
 * @property int $created_at
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%handbook_currency}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
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
            [['rate'], 'number'],
            [['date', 'date_time'], 'safe'],
            [['created_at'], 'integer'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('slug','ID'),
            'code' => Yii::t('slug','Code'),
            'rate' => Yii::t('slug','Rate'),
            'date' => Yii::t('slug','Date'),
            'created_at' => Yii::t('slug','Created At'),
        ];
    }

    public static function getUsdRate($code='usd')
    {
        $code = mb_strtoupper($code);
        $currency = self::find()->where(['code' => $code, 'date' => date('Y-m-d')])->one();
        if($currency) {
            return $currency->rate;
        } else {
            $client = new Client();
            $response = $client->createRequest()
                ->setFormat(Client::FORMAT_JSON)
                ->setMethod('POST')
                ->setUrl('https://cbu.uz/oz/arkhiv-kursov-valyut/json/')
                ->send();
            $data = json_decode($response->getContent(), true);


            if($data) {

                $currency = new Currency();

                $key = array_search(mb_strtoupper($code), array_column($data, 'Ccy'));
                $usd = $data[$key]['Rate'];
                $date = date('Y-m-d',strtotime($data[$key]['Date']));
                $currency->code = mb_strtoupper($code);
                $currency->rate = $usd;
                $currency->date = $date;
                $currency->date_time = $data[$key]['Date'];
                if (!$currency->save()) {
                    if (LOG_DEBUG_SITE) {
                        Yii::warning($currency->errors);
                    }
                }
            }


            return !empty($currency->rate) ? $currency->rate : 1;
        }
    }

}
