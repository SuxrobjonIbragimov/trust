<?php

namespace common\library\currency\currency_cbu\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%currency_cbu}}".
 *
 * @property int $id
 * @property int|null $cbu_id
 * @property string|null $code
 * @property string|null $ccy
 * @property string|null $ccy_nm
 * @property int|null $nominal
 * @property float|null $rate
 * @property float|null $diff
 * @property string|null $date
 * @property int|null $weight
 * @property string|null $created_at
 */
class CurrencyCbu extends ActiveRecord
{
    /** Transaction expiration time in milliseconds. 43 200 000 ms = 12 hours. */
    const TIMEOUT = 43200000;

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
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%currency_cbu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cbu_id', 'nominal', 'weight'], 'integer'],
            [['rate', 'diff'], 'number'],
            [['code', 'date', 'created_at'], 'safe'],
            [['ccy', 'ccy_nm'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('model', 'ID'),
            'cbu_id' => Yii::t('model', 'Cbu ID'),
            'code' => Yii::t('model', 'Code'),
            'ccy' => Yii::t('model', 'Ccy'),
            'ccy_nm' => Yii::t('model', 'Ccy Nm'),
            'nominal' => Yii::t('model', 'Nominal'),
            'rate' => Yii::t('model', 'Rate'),
            'diff' => Yii::t('model', 'Diff'),
            'date' => Yii::t('model', 'Date'),
            'weight' => Yii::t('model', 'Weight'),
            'created_at' => Yii::t('model', 'Created At'),
        ];
    }

    /**
     * Determines whether current transaction is expired or not.
     * @return bool true - if current instance of the transaction is expired, false - otherwise.
     */
    public function isExpired()
    {
        // todo: Implement model expiration check
        // for example, if transaction is active and passed TIMEOUT milliseconds after its creation, then it is expired
        return abs(Format::datetime2timestamp($this->created_at) - Format::timestamp(true)) > self::TIMEOUT;
    }

}
