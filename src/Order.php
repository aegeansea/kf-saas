<?php

namespace Aegeansea\KfSaas;

use Aegeansea\KfSaas\Traits\HasHttpRequest;

class Order
{
    use HasHttpRequest;

    public function order(
        $accountId,
        $id6d,
        $payid6d,
        $companyId,
        $paycompanyId,
        $facilitatorId,
        $productId,
        $mealKey,
        $orderType,
        $orderAmount,
        $productUnit,
        $onTrial
    )
    {
        $kfsaas = config('kfsaas');
        $host = $kfsaas['host'];
        $token = $kfsaas['token'];
        $cmd = $kfsaas['order']['cmd'];
        $uri = $kfsaas['order']['uri'];

        $params = [
            'cmd' => $cmd,
            'account_id' => $accountId,
            'id6d' => $id6d,
            'payid6d' => $payid6d,
            'company_id' => $companyId,
            'paycompany_id' => $paycompanyId,
            'facilitator_id' => $facilitatorId,
            'product_id' => $productId,
            'meal_key' => $mealKey,
            'order_type' => $orderType,
            'order_amount' => $orderAmount,
            'product_unit' => $productUnit,
            '53kf_token' => $token,
            'on_trial' => $onTrial,
        ];

        $url = $host . $uri;
        $result = $this->post($url, $params);

        return $result;
    }

    public function decryptOrder($order)
    {
        $kfsaas = config('kfsaas');
        $host = $kfsaas['host'];
        $token = $kfsaas['token'];
        $cmd = $kfsaas['order_crypto']['cmd'];
        $uri = $kfsaas['order_crypto']['uri'];

        $params = [
            'cmd' => $cmd,
            'order' => $order,
            '53kf_token' => $token,
        ];

        $url = $host . $uri;
        $result = $this->post($url, $params);

        return $result;
    }

    public function status($orderId, $decrypt = true)
    {
        $kfsaas = config('kfsaas');
        $host = $kfsaas['host'];
        $token = $kfsaas['token'];
        $cmd = $kfsaas['get_order']['cmd'];
        $uri = $kfsaas['get_order']['uri'];

        if ($decrypt) {
            $result = $this->decryptOrder($orderId);
            if (!empty($result['server_response']) && $result['server_response']['status_code'] != 201) {
                return $result;
            } else {
                $order = $result['server_response']['order'];
                $orderId = explode('=', explode(',', $order)[0])[1];
            }
        }

        $params = [
            'cmd' => $cmd,
            'order_id' => $orderId,
            '53kf_token' => $token,
        ];

        $url = $host . $uri;
        $result = $this->post($url, $params);

        return $result;
    }
}
