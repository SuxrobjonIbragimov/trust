<?php

namespace common\library\paycom\Paycom;

use Yii;
use yii\web\Response;

class Merchant
{
    public $config;

    public function __construct($config)
    {
        $this->config = $config;

        // read key from key file
        if ($this->config['key']) {
            $this->config['key'] = trim(($this->config['key']));
        }
    }

    public function Authorize($request_id)
    {
        $headers = getallheaders();

        $return = true;
        if (!$headers || !isset($headers['Authorization']) ||
            !preg_match('/^\s*Basic\s+(\S+)\s*$/i', $headers['Authorization'], $matches) ||
            base64_decode($matches[1]) != $this->config['login'] . ":" . $this->config['key']
        ) {
            $return = new PaycomException(
                $request_id,
                'Insufficient privilege to perform this method.',
                PaycomException::ERROR_INSUFFICIENT_PRIVILEGE
            );
            $return = $return->send();
        }

        return $return;
    }
}
