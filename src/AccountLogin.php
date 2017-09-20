<?php

namespace Aegeansea\KfSaas;

use Aegeansea\KfSaas\Traits\HasHttpRequest;
use GuzzleHttp\Exception\ClientException;

class AccountLogin
{
    use HasHttpRequest;

    public function login($account, $password)
    {
        $kfsaas = config('kfsaas');
        $host = $kfsaas['host'];
        $token = $kfsaas['token'];
        $cmd = $kfsaas['login']['cmd'];
        $uri = $kfsaas['login']['uri'];

        $params = [
            'cmd' => $cmd,
            'account' => $account,
            'password' => $password,
            '53kf_token' => $token,
        ];

        $url = $host . $uri;
        try {
            $result = $this->post($url, $params);
        } catch (ClientException $e) {
            $result = $this->unwrapResponse($e->getResponse());
        }

        return $result;
    }
}
