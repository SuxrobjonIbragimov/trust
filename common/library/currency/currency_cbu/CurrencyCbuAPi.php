<?php


namespace common\library\currency\currency_cbu;


use common\library\currency\currency_cbu\models\CurrencyCbu;
use common\library\currency\currency_cbu\models\Format;
use common\library\weather\yandex_weather\YandexWeatherApi;
use Yii;
use yii\helpers\Html;
use yii\httpclient\Client;

class CurrencyCbuAPi
{
    const RESPONSE_SUCCESS = 1;
    const RESPONSE_UN_SUCCESS = 0;

    public $model = null;
    public $params = null;

    public $currencies = [];

    private $baseUrl = "https://cbu.uz/oz/arkhiv-kursov-valyut/json/";
    protected $language = 'ru';

    public function __construct()
    {
        $this->language = _lang();
        $this->currencies = [
            '840',
            '978',
            '643',
            '826',
            '392',
            '398',
        ];
    }

    /**
     * @param $params
     * @return array
     */
    public function sendRequest()
    {
        $baseUrl = $this->baseUrl;
        $client = new Client(['baseUrl' => $baseUrl]);

        $content = $this->params;
        $request = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('GET')
            ->addHeaders([
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->setData($content);

        // Set Request history
        try {
            $response = $request->send();
            if ($response->isOk) {
                $this->performModel($content, $response->data);
                set_history($content, $response->data, 'currency_cbu_api');
                return $response->data;
            }
            return [
                'success' => self::RESPONSE_UN_SUCCESS,
                'code' => isset($response->data['error']['code']) ? $response->data['error']['code'] : -999,
                'message' => isset($response->data['error']['message']) ? $response->data['error']['message'] : $response->data
            ];
        } catch (\Exception $e) {

            $title = $e->getMessage();
            $message = "Code: " . $e->getCode();
            $message .= "\nFile: " . $e->getFile();
            $message .= "\nLine: " . $e->getLine();
            _send_error($title, $message, $e);

            $message = [
                Yii::t('app', 'Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
            return [
                'success' => self::RESPONSE_UN_SUCCESS,
                'code' => isset($response->data['error']['code']) ? $response->data['error']['code'] : -999,
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
            $model = CurrencyCbu::find()->orderBy(['id' => SORT_DESC])->one();
            if ($condition) {
                if (!empty($model) && !$model->isExpired()) {
                    return CurrencyCbu::find()->orderBy(['id' => SORT_DESC])->limit(count($this->currencies))->asArray()->all();
                }
            } elseif (!empty($model)) {
                return CurrencyCbu::find()->orderBy(['id' => SORT_DESC])->limit(count($this->currencies))->asArray()->all();
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

    private function performModel($content, $response)
    {
        try {
            $condition = 1;
            if ($model = $this->checkModel($condition)) {
                $this->model = $model;
            } elseif (!empty($response)) {
                foreach ($response as $index => $item) {
                    if (in_array($item['Code'],$this->currencies)) {
                        $model = new CurrencyCbu();
                        $model->cbu_id = !empty($item['id']) ? $item['id'] : null;
                        $model->code = !empty($item['Code']) ? $item['Code'] : null;
                        $model->ccy = !empty($item['Ccy']) ? $item['Ccy'] : null;
                        $model->ccy_nm = !empty($item['CcyNm_RU']) ? $item['CcyNm_RU'] : null;
                        $model->nominal = !empty($item['Nominal']) ? $item['Nominal'] : 1;
                        $model->rate = !empty($item['Rate']) ? $item['Rate'] : null;
                        $model->diff = !empty($item['Diff']) ? $item['Diff'] : 0;
                        $model->date = !empty($item['Date']) ? $item['Date'] : _date_current();
                        $model->weight = array_search($item['Code'], $this->currencies);
                        if (!$model->save()) {
                            Yii::$app->session->setFlash('error', $model->errors);
                        }
                    }
                }
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

    public static function currencyWidget()
    {
        $response = '<ul class="currency-list">';
        $modelApi = new CurrencyCbuAPi();
        if (!($model = $modelApi->checkModel())) {
            $modelApi->sendRequest();
            $model = $modelApi->checkModel();
            if (empty($model)) {
                $model = $modelApi->checkModel(0);
            }
        }
        if (!empty($model)) {
            asort($model);
            foreach ($model as $item) {
                $diff = ($item['diff']>0) ? 'up' : 'down';
                $item_diff = ($item['diff']>0) ? '+'.$item['diff'] : $item['diff'];
                $response .= '<li class="'.$item['ccy'].'">';
                $response .= ' <span class="currency-icon"> ';
                $response .= Html::img('@web/themes/ao/images/currency/'.$item['ccy'].'.svg', ['class' => 'img-responsive']);
                $response .= ' </span> ';
                $response .= ' <span class="rate diff-'.$diff.'"> ';
                $response .= $item['ccy'];
                $response .= ' '.$item['rate'];
                $response .= ' <small class="diff-'.$diff.'"> ';
                $response .= ' '.$item_diff;
                $response .= ' </small> ';
                $response .= ' </span> ';
                $response .= ' <span class="rate-icon diff-'.$diff.'"> ';
                $response .= Html::img('@web/themes/ao/images/currency/'.$diff.'.svg', ['class' => 'img-responsive']);
                $response .= ' </span> ';
                $response .= '</li>';
            }
        }
        $response .= '</ul>';
        return $response;
    }

}