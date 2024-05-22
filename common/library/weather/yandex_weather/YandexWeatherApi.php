<?php

namespace common\library\weather\yandex_weather;

use common\library\weather\yandex_weather\models\Format;
use common\library\weather\yandex_weather\models\YandexWeather;
use Yii;
use yii\httpclient\Client;

class YandexWeatherApi
{
    const RESPONSE_SUCCESS = 1;
    const RESPONSE_UN_SUCCESS = 0;
    const LATITUDE = 41.293158;
    const LONGITUDE = 69.295803;

    public $model = null;
    public $params = null;

    private $v = 'v2';
    private $method = "informers";
    private $key = 'd620d02d-faa9-4aca-8ee1-8dc40c47f22c';
    private $baseUrl = "https://api.weather.yandex.ru/v2/informers";
    private $yandexIconUrl = "https://yastatic.net/weather/i/icons/blueye/color/svg/";
    private $language = 'ru';

    public function __construct()
    {
        $this->language = _lang();
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param string $method
     */
    public function setParams($params = [])
    {
        $state_params = [
            'lat' => self::LATITUDE,
            'lon' => self::LONGITUDE,
            'lang' => $this->language,
        ];
        $this->params = $state_params;
    }

    /**
     * @param $params
     * @return array
     */
    public function sendRequest()
    {
        $content = $this->params;
        $baseUrl = $this->baseUrl .'?'.self::_generate_get_link($content);
        $client = new Client(['baseUrl' => $baseUrl]);

        $request = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('GET')
            ->addHeaders([
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
                'X-Yandex-API-Key' => $this->key,
            ])
            ->setData($content);

        // Set Request history
        try {
            $response = $request->send();
            set_history($content, $response->data, 'weather_yandex_api');
            if ($response->isOk) {
                $this->performModel($content, $response->data);
                return $response->data;
            }
            return [
                'success' => self::RESPONSE_UN_SUCCESS,
                'code' => !empty($response->data['error']['code']) ? $response->data['error']['code'] : -999,
                'message' => !empty($response->data['error']['message']) ? $response->data['error']['message'] : $response->data
            ];
        } catch (\Exception $e) {

            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);
            set_history([$title, $message], $e, 'weather_yandex_api_exception');

            $message = [
                Yii::t('app', 'Пожалуйста попробуйте позже'),
            ];
//            Yii::$app->session->setFlash('error', $message);
            return [
                'success' => self::RESPONSE_UN_SUCCESS,
                'code' => -999,
                'message' => 'Error',
            ];
        }

    }


    /**
     * @param $request
     * @param $response
     */
    private function checkModel($condition = 1)
    {
        try {
            $model = YandexWeather::find()->orderBy(['id' => SORT_DESC])->one();
            if ($condition) {
                if (!empty($model) && !$model->isExpired()) {
                    return $model;
                }
            } elseif (!empty($model)) {
                return $model;
            }
            return false;
        } catch (\Exception $e) {
            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);
            $message = [
                $title,
                Yii::t('app', 'Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
        }
    }

    /**
     * @param $content
     * @param $response
     * @return array|YandexWeather|false|\yii\db\ActiveRecord
     */
    private function performModel($content, $response)
    {
        try {
            $condition = 1;
            if ($model = $this->checkModel($condition)) {
                $this->model = $model;
            } else {
                $model = new YandexWeather();
            }
            if ($model->isNewRecord && !empty($content)) {
                $model->lat = !empty($content['lat']) ? $content['lat'] : null;
                $model->lon = !empty($content['lat']) ? $content['lon'] : null;
                $model->now = !empty($response['now']) ? $response['now'] : time();
                $model->now_dt = !empty($response['now']) ? Format::timestamp2datetime($response['now']) : _date_current();
                $model->location_name = !empty($response['info']['tzinfo']['name']) ? $response['info']['tzinfo']['name'] : '';
                $model->temp = !empty($response['fact']['temp']) ? $response['fact']['temp'] : 0;
                $model->feels_like = !empty($response['fact']['feels_like']) ? $response['fact']['feels_like'] : 0;
                $model->icon = !empty($response['fact']['icon']) ? $response['fact']['icon'] : '';
                $model->icon_swg = !empty($response['fact']['icon']) ? file_get_contents($this->yandexIconUrl.$response['fact']['icon'].'.svg') : null;
                $model->condition = !empty($response['fact']['condition']) ? $response['fact']['condition'] : 0;
                $model->wind_speed = !empty($response['fact']['wind_speed']) ? $response['fact']['wind_speed'] : 0;
                $model->wind_dir = !empty($response['fact']['wind_dir']) ? $response['fact']['wind_dir'] : '';
                $model->save();
            }
            return $model;
        } catch (\Exception $e) {
            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);
            $message = [
                $title,
                Yii::t('app', 'Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
        }
        return false;
    }

    public static function weatherWidget()
    {
        $response = '';
        $yandexWeatherApi = new YandexWeatherApi();
        if (!($model = $yandexWeatherApi->checkModel())) {
            $yandexWeatherApi->setParams();
            $yandexWeatherApi->sendRequest();
            $model = $yandexWeatherApi->checkModel();
            if (empty($model)) {
                $model = $yandexWeatherApi->checkModel(0);
            }
        }
        if (!empty($model)) {
            $temp = ($model->temp>0) ? '+'.$model->temp : $model->temp;
            $icon = $model->icon_swg;
            $response = '<a href="https://yandex.uz/pogoda/tashkent?lat=41.285632&lon=69.172105" target="_blank" title="Yandex.Weather" class="weather_info">Tashkent, UZ <span class="weather_icon">'.$icon.'</span> <span> '.$temp.'
                            <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="2" cy="2" r="1" stroke="white" stroke-width="0.5"/>
                            </svg>
                            </span></a>';
        }
        return $response;
    }

    public static function _generate_get_link($params = [])
    {
        $result = '';
        foreach ($params as $key => $value) {
            $result .= $key . '=' .$value .'&';
        }

        return $result;
    }
}