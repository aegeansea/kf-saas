<?php

namespace Aegeansea\KfSaas;

use Aegeansea\KfSaas\Traits\HasHttpRequest;

class Token
{
    use HasHttpRequest;

    public function onceToken($commonToken)
    {
        $kfsaas = config('kfsaas');
        $host = $kfsaas['host'];
        $token = $kfsaas['token'];
        $cmd = $kfsaas['get_token']['cmd'];
        $uri = $kfsaas['get_token']['uri'];

        $params = [
            'cmd' => $cmd,
            'token' => $commonToken,
            '53kf_token' => $token,
        ];

        $url = $host . $uri;
        $result = $this->post($url, $params);

        return $result;
    }
}
