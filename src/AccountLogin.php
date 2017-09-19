<?php

namespace Aegeansea\KfSaas;

use Aegeansea\KfSaas\Traits\HasHttpRequest;

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
        $result = $this->post($url, $params);

        return $result;
    }
}
