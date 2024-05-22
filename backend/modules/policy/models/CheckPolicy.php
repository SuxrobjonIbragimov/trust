<?php
namespace backend\modules\policy\models;

use backend\modules\policy\models\HandBookIns;
use backend\modules\policy\models\PolicyOsgo;
use Yii;
use yii\base\Model;
use yii\helpers\BaseUrl;
use yii\httpclient\Client;
use yii\web\BadRequestHttpException;

/**
 * Password reset form
 *
 * @property string $policy_series
 * @property string $policy_number
 *
 */
class CheckPolicy extends Model
{
    const SCENARIO_SITE = 'scenario_site';

    public $policy_series;
    public $policy_number;

    public $verifyCode;
    public $reCaptcha;

    public function __construct($token = null, $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['policy_series', 'policy_number'], 'required', 'message' => Yii::t('validation','Необходимо заполнить')],
//            ['policy_series', 'in', 'range' => self::getPolicySeriesList()],
            [
                ['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator3::className(),
                'secret' => '6LciwvQcAAAAAGwkAIfZLdXzHYwPmkwIhf5pjHOp', // unnecessary if reСaptcha is already configured
                'threshold' => 0.5,
                'action' => 'homepage',
                'on' => self::SCENARIO_SITE,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'policy_series' => Yii::t('policy','Policy series'),
            'policy_number' => Yii::t('policy','Policy number'),
        ];
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
            $title = Yii::t('policy','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            _send_error($title, json_encode($result,JSON_UNESCAPED_UNICODE));
            throw new BadRequestHttpException($title);
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getPolicyInfo()
    {
        $this->policy_series = mb_strtoupper($this->policy_series);

        $handBookService = new HandBookIns();
        $handBookService->setMethodRequest(HandBookIns::METHOD_REQUEST_POST);
        $handBookService->setMethod(HandBookIns::METHOD_GET_POLICY_INFO);
        $handBookService->setBaseUrl(EBASE_URL_INS);

        $handBookService->setParams([
            'sery' => $this->policy_series,
            'number' => intval($this->policy_number),
        ]);
        $responseReturn = $handBookService->sendRequestIns();
        if (empty($responseReturn)) {
            $title = Yii::t('policy','Хатолик юз берди биз оздан сўнг қайта уриниб кўринг');
            _send_error($title, json_encode([$responseReturn],JSON_UNESCAPED_UNICODE));
            throw new BadRequestHttpException($title);
        }

        return $responseReturn;
    }


}
