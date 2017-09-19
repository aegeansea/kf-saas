<?php

namespace Aegeansea\KfSaas;

use Aegeansea\KfSaas\Traits\HasHttpRequest;

class Worker
{
    use HasHttpRequest;

    public function worker($id6d, $companyId)
    {
        $kfsaas = config('kfsaas');
        $host = $kfsaas['host'];
        $token = $kfsaas['token'];
        $cmd = $kfsaas['worker']['cmd'];
        $uri = $kfsaas['worker']['uri'];

        $params = [
            'cmd' => $cmd,
            'id6d' => $id6d,
            '53kf_token' => $token,
            'company_id' => $companyId,
        ];

        $url = $host . $uri;
        $result = $this->post($url, $params);

        return $result;
    }
}
