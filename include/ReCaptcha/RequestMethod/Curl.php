<?php

namespace ReCaptcha1\RequestMethod;

use ReCaptcha1\RequestMethod;
use ReCaptcha1\RequestParameters;

/**
 * Sends cURL request to the reCAPTCHA service.
 */
class Curl implements RequestMethod
{
	/**
	 * URL to which requests are sent via cURL.
	 * @const string
	 */
	const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

	/**
	 * Submit the cURL request with the specified parameters.
	 *
	 * @param RequestParameters $params Request parameters
	 * @return string Body of the reCAPTCHA response
	 */
	public function submit(RequestParameters $params)
	{
		 
		$url = self::SITE_VERIFY_URL.'?'.$params->toQueryString();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
}
