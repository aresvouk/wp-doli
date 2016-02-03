<?php

class WPDoliIntegration {
	/** @var WC_Logger Logging */
	public $logger;

	/** @var string Webservice endpoint */
	private $ws_endpoint;
	
	/** @var array Webservice authentication parameters */
	private $ws_auth;
	
	/**
	 * Dolibarr webservices endpoints
	 */
	const ORDER_ENDPOINT      = 'server_order.php';
	const THIRDPARTY_ENDPOINT = 'server_thirdparty.php';
	const PRODUCT_ENDPOINT    = 'server_productorservice.php';
	const OTHER_ENDPOINT      = 'server_other.php';
	const ABON_ENDPOINT       = 'server_abonnement.php';
	const WSDL_MODE           = '?wsdl';
	
	/**
	 * Init parameters
	 */
	public function __construct() {
		require_once 'class-dolibarr-soap-elements.php';
	}
}
