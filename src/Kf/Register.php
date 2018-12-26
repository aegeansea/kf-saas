<?php

namespace Aegeansea\KfSaas\Kf;

use Aegeansea\KfSaas\Traits\HasHttpRequest;
use GuzzleHttp\Exception\ClientException;

class Register {
	use HasHttpRequest;

	public function register( $email, $password, $company_name, $mobile, $fromour ) {
		$kfsaas = config( 'kfsaas' );
		$host   = $kfsaas['kf_host'];
		$token  = $kfsaas['kf_token'];
		$cmd    = $kfsaas['kf_register']['cmd'];
		$uri    = $kfsaas['kf_register']['uri'];

		$params = [
			'cmd'          => $cmd,
			'email'        => $email,
			'password'     => $password,
			'company_name' => $company_name,
			'mobile'       => $mobile,
			'fromour'      => $fromour,
		];

		ksort($params);
		$key = md5($token.implode($params,''));
		$url = $host . $uri . '?key=' . $key;
		try {
			$result = $this->post( $url, $params );
		} catch ( ClientException $e ) {
			$result = $this->unwrapResponse( $e->getResponse() );
		}

		return $result;
	}
}
