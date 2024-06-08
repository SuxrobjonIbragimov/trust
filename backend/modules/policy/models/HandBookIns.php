<?php
namespace backend\modules\policy\models;

use Yii;
use yii\base\Model;
use yii\helpers\BaseUrl;
use yii\helpers\Url;
use yii\httpclient\Client;

/**
 * Password reset form
 *
 * @property string $policy_series
 * @property string $policy_number
 *
 */
class HandBookIns extends Model
{
    const API_ON_MAINTENANCE = false;
    const APP_VERSION = '1.0';
    const APP_VERSION_UPDATED = false;
    const APP_VERSION_UPDATE_REQUIRED = false;

    const TRAVEL_DEFAULT_PURPOSE = 0;
    const TRAVEL_DEFAULT_PROGRAM = 1;

    const SIGNIFICANCE = 1000;

    const HTTP_CLIENT_TIMEOUT = 5;
    const METHOD_REQUEST_GET = 'GET';
    const METHOD_REQUEST_POST = 'POST';

    public $policy_series;
    public $policy_number;

    public $lang = 'ru';

    private $username = 'inssite';
    private $password = 'in$On1';

    private $url = '';

    public $baseUrl = BASE_URL_INS;
    public $method = 'online/ins/site/countries';
    public $methodRequest = self::METHOD_REQUEST_GET;
    public $params = null;
    public $headers = null;

    // TRAVEL METHODS
    const METHOD_GET_POLICY_INFO = 'check/polis';
    const METHOD_GET_TRAVEL_MULTI_DAYS = 'reference/multi-days';
    const METHOD_GET_TRAVEL_ABROAD_TYPE = 'reference/abroad-type';
    const METHOD_GET_TRAVEL_ABROAD_ACTIVITY = 'reference/abroad-activity';
    const METHOD_GET_TRAVEL_ABROAD_GROUP = 'travel/reference/abroad-group';
    const METHOD_GET_TRAVEL_ABROAD_RATES = 'travel/reference/abroad-rates';
    const METHOD_GET_TRAVEL_COUNTRIES = 'reference/abroad-country';
    const METHOD_POST_TRAVEL_PROGRAMS = 'reference/abroad-program';

    const METHOD_POST_TRAVEL_CALCULATE_INITIAL_PRICE = 'travel/price/initial';
    const METHOD_POST_TRAVEL_CALCULATE_FULL_PRICE = 'travel/price/total';

    const METHOD_POST_TRAVEL_SAVE = 'travel/save/create';
    const METHOD_GET_TRAVEL_POLIS = 'docs/print';
    const METHOD_POST_TRAVEL_PROVIDER_PASSPORT = 'travel/provider/passport-birth-date';

    // OSGO METHODS
    const METHOD_OSGO_GET_VEHICLE_TYPES = 'osgo/reference/vehicle-types';
    const METHOD_OSGO_GET_USE_TERRITORY_REGIONS = 'osgo/reference/use-territory-regions';
    const METHOD_OSGO_GET_DISCOUNTS = 'osgo/reference/discounts';
    const METHOD_OSGO_GET_RESIDENT = 'osgo/reference/resident';
    const METHOD_OSGO_GET_RELATIVES = 'osgo/reference/relatives';
    const METHOD_OSGO_GET_REGIONS = 'osgo/reference/regions';
    const METHOD_OSGO_GET_DISTRICTS = 'osgo/reference/districts';

    const METHOD_OSGO_POST_CALC_PREM = 'osgo/calc-prem';
    const METHOD_OSGO_POST_VEHICLE = 'provider/vehicle';
    const METHOD_OSGO_POST_PASSPORT_BIRTH_DATE = 'provider/passport-birth-date';
    const METHOD_OSGO_POST_PASSPORT_PERSONAL_ID = 'provider/passport-pinfl';
    const METHOD_OSGO_POST_IS_PENSIONER = 'provider/v2/is-pensioner';
    const METHOD_OSGO_POST_PROVIDED_DISCOUNTS = 'provider/provided-discounts';
    const METHOD_OSGO_POST_DRIVER_SUMMARY = 'provider/driver-summary';
    const METHOD_OSGO_POST_DRIVER_LICENSE = 'provider/v2/driver-license';

    const METHOD_OSGO_POST_CREATE_ANKETA = 'osgo/create/create';

    const METHOD_OSGO_POST_CHECK_ANKETA_STATUS = 'osgo/check/payment';

    // GNK PINFL/INN
    const METHOD_DIDOX_GET_PROFILE = 'v1/profile/';

    const METHOD_OSGO_POST_CREATE_ANKETA_KS_V2 = 'osgo/create';

    const METHOD_OSGO_POST_VEHICLE_KS_V2 = 'provider/v2/vehicle';
    const METHOD_OSGO_POST_PASSPORT_BIRTH_DATE_KS_V2 = 'provider/v2/passport-birth-date';
    const METHOD_OSGO_POST_PASSPORT_PERSONAL_ID_KS_V2 = 'provider/v2/passport-pinfl';
    const METHOD_OSGO_POST_IS_PENSIONER_KS_V2 = 'provider/v2/is-pensioner';
    const METHOD_OSGO_POST_PROVIDED_DISCOUNTS_KS_V2 = 'provider/v2/provided-discounts';
    const METHOD_OSGO_POST_DRIVER_SUMMARY_KS_V2 = 'provider/v2/driver-summary';
    const METHOD_OSGO_POST_DRIVER_LICENSE_KS_V2 = 'provider/v2/driver-license';

    // OSGOR METHODS
    const METHOD_OSGOR_GET_OKED_LIST = 'product/osgor/oked';
    const METHOD_OSGOR_GET_OKONX_LIST = 'product/osgor/okonx';
    const METHOD_OSGOR_POST_CALC_PREM = 'osgor/calculate';

    const METHOD_OSGOR_POST_CREATE_ANKETA = 'osgor/contract';

    const METHOD_OSGOR_POST_CONFIRMPAYMENT = 'eosgouz/osgor/confirmpayment';
    const METHOD_OSGOR_POST_CANCELPOLICY = 'eosgouz/osgor/cancelpolicy';
    const METHOD_OSGOR_POST_CHECK_ANKETA_STATUS = 'eosgouz/check/payment';

    const METHOD_OSGO_POST_CONFIRMPAYMENT = 'payments/check';

    // OSGOR METHODS

    const METHOD_OSGOP_POST_CALC_PREM = 'osgop/calculate';
    const METHOD_OSGOP_GET_VEHICLE_TYPES = 'osgop/vehicle-types';
    const METHOD_OSGOP_POST_VEHICLE = 'provider/vehicle-ersp';

    const METHOD_OSGOP_POST_CREATE_ANKETA = 'osgop/contract';

    public function __construct($token = null, $config = [])
    {
        $this->lang = _lang();
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['policy_series', 'policy_number'], 'required', 'message' => Yii::t('validation','Необходимо заполнить')],
            ['policy_series', 'in', 'range' => self::getPolicySeriesList()],
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

    const POLICY_SERIES_EKSL = 'EKSL';
    const POLICY_SERIES_EKSU = 'EKSU';
    const POLICY_SERIES_KSL = 'KSL';
    const POLICY_SERIES_KSU = 'KSU';

    /**
     * @return string[]
     */
    public static function getPolicySeriesList ()
    {
        $array = [
            self::POLICY_SERIES_EKSL,
        ];
        return $array;
    }

    /**
     * @return array
     */
    public static function getApiInfo ()
    {
        $array = [
            'API_ON_MAINTENANCE' => self::API_ON_MAINTENANCE,
            'APP_VERSION_UPDATE_REQUIRED' => self::APP_VERSION_UPDATE_REQUIRED,
            'APP_VERSION' => self::APP_VERSION,
        ];
        return $array;
    }

    /**
     * @return array
     */
    public static function getWebviewLinks ()
    {
        $array = [
            'products' => [
                'travel' => null,
                'osago' => null,
                'kasko' => Url::to(['/policy/kasko/calculate']),
                'health' => null,
                'house' => null,
            ],
            'pages' => [
                'news' => Url::to(['/site/newslist']),
                'online-consultant' => Url::to(['/site/online-consultant']),
            ]
        ];
        return $array;
    }

    const FOND_ERROR_DEFAULT = 503;
    const FOND_ERROR_503 = 503;
    const FOND_ERROR_404 = 404;
    const FOND_ERROR_422 = 422;
    const FOND_ERROR_423 = 423;

    /**
     * @return array
     */
    public static function getFondErrorList()
    {
        $array = [
            self::FOND_ERROR_503 => Yii::t('policy', 'У провайдера данных случилась непредвиденная ошибка'),
            self::FOND_ERROR_404 => Yii::t('policy', 'Данных по предоставленным параметрам не найдено'),
            self::FOND_ERROR_422 => Yii::t('policy', 'Государственный номер автомобиля не соответствует выбранному региону!'),
            self::FOND_ERROR_423 => Yii::t('policy', 'Вид транспортного средства не соответствует выбранному типу!'),
        ];
        return $array;
    }

    /**
     * @param $error
     * @return mixed
     */
    public static function getFondError($error=null)
    {
        $error = !empty(self::getFondErrorList()[$error]) ? self::getFondErrorList()[$error] : self::getFondErrorList()[self::FOND_ERROR_DEFAULT];
        return $error;
    }

    /**
     * @param $base_url
     */
    public function setBaseUrl($base_url)
    {
        $this->baseUrl = $base_url;
    }

    /**
     * @param $lang
     */
    public function setLanguage($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @param $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param $method
     */
    public function setMethodRequest($methodRequest)
    {
        $this->methodRequest = $methodRequest;
    }

    /**
     * @param $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @param $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param $headers
     */
    public function setLogin($login)
    {
        $this->username = $login;
    }

    /**
     * @param $headers
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function sendRequestIns($suffix='')
    {
        $responseReturn = [
            'status' => false,
            'response' => null,
        ];

        $baseUrl = $this->baseUrl.$this->method.$suffix;
        $client = new Client(['baseUrl' => $baseUrl]);

        $lang = $this->lang;
        if ($this->methodRequest == HandBookIns::METHOD_REQUEST_POST) {

            $request = $client->createRequest()
                ->setMethod($this->methodRequest)
                ->setOptions([
                    'timeout' => self::HTTP_CLIENT_TIMEOUT,
                ])
                ->addHeaders(['Accept' => 'application/json'])
                ->addHeaders(['Content-type' => 'application/json'])
                ->addHeaders(['Accept-Language' => $lang])
                ->setData($this->params)
                ->setContent(json_encode($this->params));

        } else {
            $request = $client->createRequest()
                ->setOptions([
                    'timeout' => self::HTTP_CLIENT_TIMEOUT,
                ])
                ->addHeaders(['Accept' => 'application/json'])
                ->addHeaders(['Content-type' => 'application/json'])
                ->addHeaders(['Accept-Language' => $lang]);

        }
        if ($this->baseUrl == EBASE_URL_INS) {
            $request->addHeaders([
                'Authorization' => 'Bearer ' . EBASE_URL_INS_TOKEN,
            ]);
        } else {
            $request->addHeaders([
                'Authorization' => 'Basic ' . base64_encode("$this->username:$this->password")
            ]);
        }
        if (!empty($this->headers)) {
            foreach ($this->headers as $header) {
                $request->addHeaders([
                    $header['key'] => $header['value'],
                ]);
            }
        }
        // Set Request history
        try {
            $response = $request->send();
            if (LOG_DEBUG_SITE) {
                set_history($request, $response->data,'Handbook_sendRequestIns_try_'.$this->method,['headers' => $this->headers,'params' => $this->params,]);
            }
            if ($response->isOk) {
                $responseReturn = $response->data;
            } else {
                $responseReturn = $response->data;
            }
        } catch (\Exception $e) {

            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);
            if (LOG_DEBUG_SITE) {
                set_history($this->params, [$title, $message],'Handbook_sendRequestIns_catch_'.$this->method,['headers' => $this->headers,'params' => $this->params,]);
            }

//            d($this->headers);
//            d($this->params);
//            d($response);
//            d($response->getContent());
//            dd($e);
            $responseReturn['headers'] = $this->headers;
            $responseReturn['ERROR'] = 504; // 504 Gateway Timeout
            $responseReturn['ERROR_MESSAGE'] = Yii::t('policy','Пожалуйста попробуйте позже');

//            $message = [
//                Yii::t('policy','Пожалуйста попробуйте позже'),
//            ];
//            Yii::$app->session->setFlash('error', $message);

        }

        return $responseReturn;
    }


    /**
     * @param $number
     * @param int $significance
     * @return float|int
     */
    public static function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number/$significance)*$significance) : 0;
    }

}
