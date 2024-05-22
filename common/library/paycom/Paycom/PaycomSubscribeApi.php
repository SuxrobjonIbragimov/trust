<?php


namespace common\library\paycom\Paycom;


use common\library\octo\ResponseStatus;
use common\library\payment\models\RequestHistory;
use Yii;
use yii\httpclient\Client;

class PaycomSubscribeApi
{
    const RESPONSE_SUCCESS = 1;
    const RESPONSE_UN_SUCCESS = 0;

    const TOKEN_SAVE = false;

    // Методы для клиентской части приложения мерчанта:
    const METHOD_CARDS_CREATE = 'cards.create'; // Создание токена пластиковой карты.
    const METHOD_CARDS_GET_VERIFY_CODE = 'cards.get_verify_code'; // Запрос кода для верификации карты.
    const METHOD_CARDS_VERIFY = 'cards.verify'; // Верификация карты с помощью кода отправленного по СМС.

    // Методы для серверной части приложения мерчанта:
    const METHOD_CARDS_CHECK = 'cards.check'; // Проверка токена карты.
    const METHOD_CARDS_REMOVE = 'cards.remove'; // Удаление токена карты.

    // Для работы с чеком, в Subscribe API используются методы для серверной части приложения мерчанта:
    const METHOD_RECEIPTS_CREATE = 'receipts.create'; // Создание чека на оплату.
    const METHOD_RECEIPTS_PAY = 'receipts.pay'; // Оплата чека.
    const METHOD_RECEIPTS_SEND = 'receipts.send'; // Отправка инвойса.
    const METHOD_RECEIPTS_CANCEL = 'receipts.cancel'; // Установка оплаченного чека в очередь на отмену.
    const METHOD_RECEIPTS_CHECK = 'receipts.check'; // Проверка статуса чека.
    const METHOD_RECEIPTS_GET = 'receipts.get'; // Полная информация по чеку.

    public $config;
    public $token = '';
    private $baseUrl = "https://checkout.test.paycom.uz/api";
    private $language = 'ru';
//    private $id = '5c12721ba3c7f5753a8daa38';
    private $id = '5fc8b32bf00a7f9fefcb105e';
    private $password = 'jb5T4FkwgY?ZJkxRyYiZMgoOHkMSh7s4kf?%';

    private $method = "";
    private $params = [];
    public $order_id = 123;

    public function __construct($token=null)
    {
        $path_to_configs = __DIR__ . '//..//paycom.config.php';
        $this->config = require($path_to_configs);
        $this->id = $this->config['merchant_id'];
        $this->password = $this->config['key'];
        $this->token = $token;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;

        if ( ($this->method == self::METHOD_RECEIPTS_CREATE) || ($this->method == self::METHOD_RECEIPTS_PAY) || ($this->method == self::METHOD_RECEIPTS_SEND) || ($this->method == self::METHOD_RECEIPTS_CANCEL) || ($this->method == self::METHOD_RECEIPTS_CHECK) )
        {
            $this->id .= ':'.$this->password;
        }
    }

    /**
     * @param string $method
     */
    public function setParams($params)
    {
        $state_params = [
//            'jsonrpc' => '2.0',
            'id' => $this->order_id,
            'method' => $this->method,
            'params' => $params,
        ];
        $this->params = $state_params;
    }

    /**
     * @param $params
     * @return array
     */
    public function sendRequest($params = [], $resp = [])
    {
        $baseUrl = $this->baseUrl;
        $client = new Client(['baseUrl' => $baseUrl]);

        $content = $this->params;
        $request = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('POST')
            ->addHeaders([
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->setData($content);
        $request->headers->set('X-Auth', $this->id);

        // Set Request history
        try {
            $response = $request->send();
//            d($request);
//            dd($response);
            if (LOG_DEBUG_SITE) {
                set_history($request,$response->data,'paycom_subscribe_api_'.$this->method,$this->params);
            }
            if ($response->isOk) {
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
                Yii::t('slug','Пожалуйста попробуйте позже'),
            ];
            Yii::$app->session->setFlash('error', $message);
            return [
                'success' => self::RESPONSE_UN_SUCCESS,
                'code' => isset($response->data['error']['code']) ? $response->data['error']['code'] : -999,
            ];
        }

    }


}