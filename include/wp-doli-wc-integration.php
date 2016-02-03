<?php
/* Copyright (C) 2013-2014 Cédric Salvador <csalvador@gpcsolutions.fr>
 * Copyright (C) 2015 Maxime Lafourcade <mlafourcade@gpcsolutions.fr>
* Copyright (C) 2015 Raphaël Doursenaud <rdoursenaud@gpcsolutions.fr>
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Wpdoli settings.
 *

 *
 * @package Wpdoli
 */
require_once 'abstract-wc-integration.php';
require_once 'class-wc-logger.php';
require_once NUSOAP_PATH.'/nusoap.php';		// Include SOAP
require_once 'wpdoli-dolibarr-soap-elements.php';
require_once 'Drewm/MailChimp.php';

/**
 * Wpdoli settings WooCommerce integration
 *
 * @see WC_Integration
 */
class WPDoli_WC_Integration extends WC_Integration {
	/** @var string The Dolibarr webservice URL */
	public $wpdoli_settings_url;


	/** @var string The Dolibarr webservice key */
	public $wpdoli_settings_ws_key;

	/** @var string The application name declared when using the Dolibarr webservice */
	public $sourceapplication;

	/** @var string Username to connect to Dolibarr webservice */
	public $wpdoli_settings_user;

	/** @var string Password to connect to Dolibarr webservice */
	public $wpdoli_settings_password;

	/** @var string Dolibarr entity we want webservice responses from */
	public $wpdoli_settings_entity=1;
  

	/** @var int[] The distant Dolibarr version */
	private $dolibarr_version='3.7.0';
	
	/** @var string site name of wordpress */
	public $wpdoli_settings_site_name;
	
	/** @var string id of lis subscribers of mailchimp */
	private $wpdoli_settings_listid_mailchimp='';
	
	/** @var string key to connect of  mailchimp*/
	public $wpdoli_settings_key_mailchimp;
	/**
	 * Dolibarr webservices endpoints
	 */
	const ORDER_ENDPOINT      = 'server_order.php';
	const THIRDPARTY_ENDPOINT = 'server_thirdparty_abonnement.php';
	//const PRODUCT_ENDPOINT    = 'server_productorservice.php';
	const OTHER_ENDPOINT      = 'server_other.php';
	const ABON_ENDPOINT      = 'server_abonnement.php';
	const PRODUCT_ENDPOINT    = 'server_abonnement.php';
	const USER_ENDPOINT = 'server_user.php';
	
	const WSDL_MODE           = '?wsdl';
	const WPDOLI_NS ='http://www.dolibarr.org/ns/';

	public $logger;

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		$this->id                 = 'wpdoli';
		$this->method_title       = __( 'Wdoli', 'wpdoli' );
		$this->method_description = __( 'Dolibarr webservices access', 'wpdoli' );
		$this->logger = new WC_Logger();

		// Load the settings
			
		$this->init_settings();
		// Define user set variables
		$this->wpdoli_settings_url = get_option( 'wpdoli_settings_url' );
		$this->wpdoli_settings_ws_key         = get_option( 'wpdoli_settings_ws_key' );
		//$this->sourceapplication    = $this->get_option( 'sourceapplication' );
		$this->wpdoli_settings_user       = get_option( 'wpdoli_settings_user' );
		$this->wpdoli_settings_password    = get_option( 'wpdoli_settings_password' );
		$this->wpdoli_settings_site_name = get_option('wpdoli_settings_site_name');
		$this->wpdoli_settings_listid_mailchimp=get_option('wpdoli_settings_listid_mailchimp');
		
		$this->wpdoli_settings_key_mailchimp=get_option('wpdoli_settings_key_mailchimp');
		//$this->wpdoli_settings_entity      = $this->get_option( 'wpdoli_settings_entity' );
			
			
	}

	/**
	 *
	 */
	function getClientSoap($service,$wsdl_mode) {
		//$service = self::OTHER_ENDPOINT;
		$wsdl_mode ='';
		$urlService = $this->wpdoli_settings_url .$service ;
		try {
			$soap_client = new  nusoap_client($urlService);
			if ($soap_client)
			{
				$soap_client->soap_defencoding='UTF-8';
				$soap_client->decodeUTF8(false);
			}

		} catch ( SoapFault $exception ) {
			$this->logger->add( 'wpdoli', $exception->getMessage() );
			$this->errors[] = __( 'The webservice is not available. Please check the URL.'.$urlService, 'wpdoli' );
			//$this->display_errors();
			// Do nothing.
			return -1;
		}
		return $soap_client;
	}
	/**
	 *
	 */
	function getCurrentAuth() {
	 return	$ws_auth  = array(
	 		'dolibarrkey'       => $this->wpdoli_settings_ws_key,
	 		'sourceapplication' => $this->sourceapplication,
	 		'login'             => $this->wpdoli_settings_user,
	 		'password'          => $this->wpdoli_settings_password,
	 		'entity'            => $this->wpdoli_settings_entity,
	 );

	}
	function getAuth($username,$password) {
		return	$ws_auth  = array(
				'dolibarrkey'       => $this->wpdoli_settings_ws_key,
				'sourceapplication' => $this->sourceapplication,
				'login'             => $username,
				'password'          => $password,
				'entity'            => $this->wpdoli_settings_entity,
		);
	
	}


	/**
	 * Check that the webservice works.
	 * Tests endpoint, authentication and actual response
	 *
	 * @param string $webservice The webservice URL
	 * @param string[] $ws_auth The webservice authentication array
	 */
	public function test_webservice($webservice = '',$ws_auth = array()) {
		$soap_client = $this->getClientSoap(WPDoli_WC_Integration::OTHER_ENDPOINT, WPDoli_WC_Integration::WSDL_MODE);
		if(!is_object($soap_client))
			return $soap_client;
		try {
			$parameters = array('authentication'=>$this->getCurrentAuth());
			$WS_METHOD  = 'getVersions';
			$response = $soap_client->call($WS_METHOD,$parameters,$ns,'');
		} catch ( SoapFault $exc ) {

			$this->errors[] = 'Webservice error:' . $exc->getMessage();
			$this->display_errors();
			// No point in doing the next test
			return -1;
		}
		if ( 'OK' == $response['result']['result_code'] ) {
			$this->wpdoli_settings_version = explode( '.', $response['dolibarr'] );
			return 1;
		} else {
			$this->errors[] = 'Webservice error:' . $response['result']->result_label;
			$this->display_errors();
		}
	}

	/**
	 * liste des produits de dolibarr via le service web
	 *
	 * @return array liste des produit
	 */
	public function dolibarr_getProduit() {
	//	echo '<pre>';var_dump($this->dolibarr_getCountry());exit;
		$service = self::PRODUCT_ENDPOINT;
		$wsdl_mode = self::WSDL_MODE;
		$prod = array();
		$soap_client = $this->getClientSoap($service, $wsdl_mode);
		if(!is_object($soap_client))
			return $soap_client;
		//liste des produits de dolibarr
		try {
			//$result = $soap_client->getListOfProductsOrServices($this->getCurrentAuth());
			$parameters = array('authentication'=>$this->getCurrentAuth(),
					'id'=>$this->wpdoli_settings_site_name,
					'filterproduct'=>array('tosell'=>1));
			$WS_METHOD  = 'getListOfProductsOrServicesForCategory';
			
			$result = $soap_client->call($WS_METHOD,$parameters,self::WPDOLI_NS,'');
			//var_dump($result);exit;//$result = $soap_client->getProductsForCategory($ws_auth,1);
		} catch ( SoapFault $exception ) {
			$this->logger->add('wpdoli','getListOfProductsOrServicesForCategory request: ' . $exception->getMessage());
			$this->errors[] = 'Webservice error:' . $exc->getMessage();
			// Do nothing.
			return;
		}

		if ( ! ( 'OK' == $result['result']['result_code'] ) ) {
			$this->logger->add('wpdoli','getListOfProductsOrServicesForCategory response: ' . $result['result']['result_code'] . ': ' . $result['result']['result_label']);
			// Do nothing
			return -1;
		}
		/** @var Dolibarr_Product[] $dolibarr_products */
		$dolibarr_products = $result['products'];
		//var_dump($dolibarr_products);exit;
		//return $dolibarr_products;
		if ( ! empty( $dolibarr_products ) ) {
			foreach ( $dolibarr_products as $dolibarr_product ) {
				$prod [$dolibarr_product["id"]] =  $dolibarr_product["label"];
			}
		}
		return $prod;
	}
	
	/**
	 * liste des produits de dolibarr via le service web
	 *
	 * @return array liste des produit
	 */
	public function dolibarr_getCountry() {
		$service = self::PRODUCT_ENDPOINT;
		$wsdl_mode = self::WSDL_MODE;
		$prod = array();
		$soap_client = $this->getClientSoap($service, $wsdl_mode);
		if(!is_object($soap_client))
			return $soap_client;
		//liste des produits de dolibarr
		try {
			//$result = $soap_client->getListOfProductsOrServices($this->getCurrentAuth());
			$parameters = array('authentication'=>$this->getCurrentAuth());
			$WS_METHOD  = 'getListOfCountry';
			$result = $soap_client->call($WS_METHOD,$parameters,self::WPDOLI_NS,'');
	
			//$result = $soap_client->getProductsForCategory($ws_auth,1);
		} catch ( SoapFault $exception ) {
			$this->logger->add('wpdoli','getListOfProductsOrServices request: ' . $exception->getMessage());
			$this->errors[] = 'Webservice error:' . $exc->getMessage();
			// Do nothing.
			return;
		}
	
		if ( ! ( 'OK' == $result['result']['result_code'] ) ) {
			$this->logger->add('wpdoli','getListOfCountry response: ' . $result['result']['result_code'] . ': ' . $result['result']['result_label']);
			// Do nothing
			return -1;
		}
		/** @var Dolibarr_Product[] $dolibarr_products */
		$dolibarr_products = $result['products'];
		//var_dump($dolibarr_products);exit;
		//return $dolibarr_products;
		if ( ! empty( $dolibarr_products ) ) {
			foreach ( $dolibarr_products as $dolibarr_product ) {
				$prod [$dolibarr_product["id"]] =  $dolibarr_product["label"];
			}
		}
		return $prod;
	}
	
	/**
	 * authentification de l'utilisateur  dolibarr via le service web
	 *
	 * @return array liste des produit
	 */
	public function dolibarr_check_authentication($username,$password) {
		$service = self::PRODUCT_ENDPOINT;
		$wsdl_mode = self::WSDL_MODE;
		$prod = array();
		$soap_client = $this->getClientSoap($service, $wsdl_mode);
		if(!is_object($soap_client))
			return $soap_client;
		//liste des produits de dolibarr
		try {
			//$result = $soap_client->getListOfProductsOrServices($this->getCurrentAuth());
			$parameters = array('authentication'=>$this->getAuth($username, $password));
			$WS_METHOD  = 'getUser';
			$result = $soap_client->call($WS_METHOD,$parameters,self::WPDOLI_NS,'');
	
			//$result = $soap_client->getProductsForCategory($ws_auth,1);
		} catch ( SoapFault $exception ) {
			$this->logger->add('wpdoli','getUser request: ' . $exception->getMessage());
			$this->errors[] = 'Webservice error:' . $exc->getMessage();
			// Do nothing.
			return -1;
		}
		
		if ( ! ( 'OK' == $result['result']['result_code'] ) ) {//var_dump($result);exit;
			//$this->logger->add('wpdoli','getUser response: ' . $result['result']['result_code'] . ': ' . $result['result']['result_label']);
			// Do nothing
			return $result;
		}
		
		/** @var Dolibarr_Product[] $dolibarr_products */
		//var_dump($result);
		//$dolibarr_user = $result['user'];
		return $result	;
	}
	/**
	 * Creates a thirdparty in Dolibarr via webservice using WooCommerce user data
	 *
	 * @param int $user_id A Wordpress user ID
	 *
	 * @return array() $result The SOAP response
	 */
	public function dolibarr_create_thirdparty( $arrThirdparty ) {
		
          
		//$ref        = get_user_meta( $user_id, 'billing_company', true );
		$service = self::THIRDPARTY_ENDPOINT;
		$wsdl_mode = self::WSDL_MODE;
		$individual = $arrThirdparty['private'];
		$soap_client = $this->getClientSoap($service, $wsdl_mode);
		if(!is_object($soap_client))
			return $soap_client;
// 		include_once 'wpdoli-dolibarr-soap-elements.php';
// 		$new_thirdparty = new Dolibarr_Thirdparty1();
// 		$new_thirdparty->setAttributsValues($arrThirdparty);
// 		$new_thirdparty->status    = '1'; // Active
// 		$new_thirdparty->client    = '1'; // Is a client
// 		$new_thirdparty->supplier  = '0';
// 		$new_thirdparty->ref_ext = uniqid();
// 		$new_thirdparty->fk_user_author = 1;

		$new_thirdparty = array(
				'ref'=> ($individual)?$arrThirdparty['first_name'].' '.$arrThirdparty['last_name']:$arrThirdparty['last_name'], // Company name or individual last name
				'individual'=> $individual, // Individual
				'firstname'=> $arrThirdparty['first_name'],
				'lastname'=> $arrThirdparty['last_name'],
				'status   '=> '1', // Active
				'client   '=> '1', // Is a client
				'supplier '=> '0', // Is not a supplier
				'address'=> $arrThirdparty['fac_adresse'],
				'zip'=>$arrThirdparty['fac_code_postable'],
				'town'=> $arrThirdparty['fac_ville'],
				'country_code'=> $arrThirdparty['fac_pays'],
				'livr_adress'=> $arrThirdparty['livr_adresse'],
				'livr_zip'=>$arrThirdparty['livr_code_postable'],
				'livr_town'=> $arrThirdparty['livr_ville'],
				'livr_country'=> $arrThirdparty['livr_pays'],
				'phone'=>$arrThirdparty['tel'],
				'contactname'=>$arrThirdparty['contactname'],
				'contactfirstname'=>$arrThirdparty['contactfirstname'],
				'email'=>$arrThirdparty['email'],
				'ref_ext'=> uniqid(),
				'fk_user_author'=> 1,
				'customer_code'=> '',
				'customer_code_accountancy'=> null,
				'supplier_code_accountancy'=> null,
				'date_creation'=> date(),
				'date_modification'=> date(),
				'note_private'=> null,
				'note_public'=> $arrThirdparty['communication'],
				'province_id'=> null,
				'country_id'=> null,
		
				'country'=> null,
		
				'fax'=> null,
		
				'url'=> null,
				'profid1'=> null,
				'profid2'=> null,
				'profid3'=> null,
				'profid4'=> null,
				'profid5'=> null,
				'profid6'=> null,
				'capital'=> null,
				'vat_used'=> null,
				'vat_number'=> null,
				'communication'=>  $arrThirdparty['communication'],
				'docpapier'=>  $arrThirdparty['docpapier'],
				'isnewletter'=>  $arrThirdparty['isnewletter']
		);
		
	


		try {
			$parameters = array('authentication'=>$this->getCurrentAuth(),'thirdparty'=>$new_thirdparty,'idCmd'=>$arrThirdparty['produit_id']);
			$WS_METHOD  = 'createThirdParty';
			$result = $soap_client->call($WS_METHOD,$parameters,self::WPDOLI_NS,'');
          //var_dump($result);exit;
              //
			//$result = $soap_client->createThirdParty( $this->getCurrentAuth(), $new_thirdparty->getAttributsValues() ,$arrThirdparty['produit_id']);

		} catch ( SoapFault $exception ) {
			$this->logger->add(
					'wpdoli',
					'createThirdParty request: ' . $exception->getMessage()
			);

			// Do nothing.
			return -1;
		}
		
		if ( ! ( 'OK' == $result['result']['result_code'] ) || $result ==false) {
			/* $this->logger->add(
					'wpdoli',
					'createThirdParty response: ' . $result['result']['result_code'] . ': ' . $result['result']['result_label']
			); */
			//var_dump($result);exit;
			// Do nothing
			return -1;
		}
		// ajout au mailChimp
		if(isset($arrThirdparty['isnewletter']) && $arrThirdparty['isnewletter'] > 0) {
			$MailChimp = new \Drewm\MailChimp($this->wpdoli_settings_key_mailchimp);
			//print_r($MailChimp->call('lists/list'));
			$result = $MailChimp->call('lists/subscribe', array(
					'id'                => $this->wpdoli_settings_listid_mailchimp,
					'email'             => array('email'=>$arrThirdparty['email']),
					'merge_vars'        => array('FNAME'=>$arrThirdparty['first_name'], 'LNAME'=>$arrThirdparty['last_name']),
					'double_optin'      => false,
					'update_existing'   => true,
					'replace_interests' => false,
					'send_welcome'      => false,
			));
			//print_r($result);
		}
		return $result;
	}

	public function dolibarr_setPassword( $login,$new_password) {
		//$ref        = get_user_meta( $user_id, 'billing_company', true );
		$service = self::USER_ENDPOINT;
		$wsdl_mode = self::WSDL_MODE;
		$soap_client = $this->getClientSoap($service, $wsdl_mode);
		if(!is_object($soap_client))
			return $soap_client;
		
		$shortuser = array('login'=>$login,'password'=>$new_password	);
	
	
		try {//var_dump($this->getCurrentAuth());exit;
			$parameters = array('authentication'=>$this->getCurrentAuth(),'shortuser'=>$shortuser);
			$WS_METHOD  = 'setUserPassword';
			$result = $soap_client->call($WS_METHOD,$parameters,self::WPDOLI_NS,'');
			//var_dump($result);exit;
			//
	
		} catch ( SoapFault $exception ) {
			$this->logger->add(
					'wpdoli',
					'set_password request: ' . $exception->getMessage()
			);
	
			// Do nothing.
			return -1;
		}
	
		if ( ! ( 'OK' == $result['result']['result_code'] ) || $result ==false) {
			$this->logger->add(
					'wpdoli',
					'set_password response: ' . $result['result']['result_code'] . ': ' . $result['result']['result_label']
			);
			//var_dump($result);exit;
			// Do nothing
			return -1;
		}
		return $result;
	}
	
	/**
	 * Display HTTPS is needed
	 * @see WC_Integration::display_errors()
	 *
	 * @return void
	 */
	public function display_errors( ) {
		if ( empty( $this->errors ) ) {
			// Nothing to do
			return;
		}

		foreach ( $this->errors as $key => $value ) {
			?>
<div class="error">
	<p>
		<b> <?php
		esc_html_e( $value );
		?>
		</b>
	</p>
</div>
<?php
		}

		// Errors have been displayed. Let's clear them to avoid weird corner case.
		unset( $this->errors );
	}


}

