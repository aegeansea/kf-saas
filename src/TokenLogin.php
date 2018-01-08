<?php

namespace Aegeansea\KfSaas;

use Aegeansea\KfSaas\Traits\HasHttpRequest;
use GuzzleHttp\Exception\ClientException;

class TokenLogin {
	use HasHttpRequest;

	public function login( $onceToken, $other = '' ) {
		$kfsaas = config( 'kfsaas' );
		$host   = $kfsaas['host'];
		$token  = $kfsaas['token'];
		$cmd    = $kfsaas['token_login']['cmd'];
		$uri    = $kfsaas['token_login']['uri'];

		$params = [
			'cmd'        => $cmd,
			'once_token' => $onceToken,
			'other'      => $other,
			'53kf_token' => $token,
		];

		$url = $host . $uri;
		try {
			$result = $this->post( $url, $params );
		} catch ( ClientException $e ) {
			$result = $this->unwrapResponse( $e->getResponse() );
		}

		return $result;
	}
}
