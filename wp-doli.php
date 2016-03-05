<?php
/*
Plugin Name: WPDoli
Plugin URI: https://github.com/tonin/abonibarr
Description: Création de client Dolibarr par formulaire WordPress
Version: 0.3
Author: Cassiopea asbl
Author URI: http://www.cassiopea.org/
License: GPL-3.0+
Text Domain: WPDoli
Domain Path: /languages
*/
// Initialize constants.
define('WPDOLI_VERSION', '0.3');
define('WPDOLI_DEBUG', false);
if (false === extension_loaded('soap')) {
	esc_html_e( __( 'This plugin needs SOAP PHP extension.', 'doliwoo' ) );
	exit;
}
define( 'WPDOLI_DIR',  plugin_dir_url ( __FILE__ ) );
define( 'WPDOLI_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPDOLI_PATH_INC', plugin_dir_path( __FILE__ ).'include/');
define( 'NUSOAP_PATH', plugin_dir_path( __FILE__ ).'include/nusoap/lib/');
require(plugin_dir_path( __FILE__ ).'include/autoload.php');

include_once WPDOLI_PATH_INC.'wp-doli-wc-integration.php';

Class Wpdoli {
	const DOLIBARR_ROLE ='subscriber_dolibarr';
	//const DOLIBARR_ROLE_LABEL =__( 'subscriber of dolibarr' );
	public $dolibarr;
	function __construct() {
		$this->init();
	}
	function init() {
		$this->dolibarr =  new WPDoli_WC_Integration();
		// Localization.
		//add_action( 'plugins_loaded', 'wpdoli_load_textdomain' );
		register_activation_hook( __FILE__, array($this,'wpdoli_install'));
		register_uninstall_hook(__FILE__, array($this,'wpdoli_uninstall'));
		wp_enqueue_script('recaptcha-js', 'https://www.google.com/recaptcha/api.js', null, \ReCaptcha\ReCaptcha::VERSION);
		add_action('admin_menu', array($this, 'add_admin_menu'));
		//add_action ('wp_authenticate' , array($this,'check_custom_authentication'));
		remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
		add_filter('authenticate', array($this, 'check_custom_authentication'), 20, 3);
		//add_action( 'profile_update', array($this,'my_profile_update' ), 20, 3);
		add_action('password_reset', array($this, 'my_password_reset'), 10, 2);
		include_once WPDOLI_PATH_INC.'wp-doli-admin.php';
		include_once WPDOLI_PATH_INC.'wp-doli-shortcode.php';
		new WPDoliAdmin($this->dolibarr);
		new WPDoliShortcode($this->dolibarr);
		$this->createRole();
	}
	/**
	 * Filter translation file.
	 *
	 * @param string $file The translation file to load.
	 */
	function wpmem_load_textdomain() {


		$file = apply_filters('wpdoli_localization_file', dirname( plugin_basename( __FILE__ ) ) . '/lang/');

		// Load the localization file.
		load_plugin_textdomain('wpdoli', false, $file);

		return;
	}
	public function wpdoli_install()
	{
		global $wpdb;
	}
	
	public function wpdoli_uninstall()
	{
		global $wpdb;
	}
	public function add_admin_menu()
	{
		add_menu_page('Plugin d\'intégration à Dolibarr', 'WPDoli', 'manage_options', 'WPDoli', array($this, 'menu_html'));
	}
	
	public function menu_html()
	{
		echo '<h1>'.get_admin_page_title().'</h1>';
	}
	function check_custom_authentication ($user, $username, $password) {
		global $wpdb;
		$error = new WP_Error();
		if (is_a($user, 'WP_User')) {
			return $user;
		}
// 		$rolename ='subscriber';
// 		var_dump($rolename);
// 		$role = get_role($rolename);
// 		var_dump($role);exit();
		
		
		if (empty($username) || empty ($password)) {
			//create new error object and add errors to it.
			

			if (empty($username)) { //No email
				$error->add('empty_username', __('<strong>ERROR</strong>: Email field is empty.'));
			}

			if (empty($password)) { //No password
				$error->add('empty_password', __('<strong>ERROR</strong>: Password field is empty.'));
			}
			return $error;
		}
		
		
		$role_allow = false;
		$user_obj = get_user_by('login', $username);
		if(is_object($user_obj)) {
			//$error->add('empty_username', __("<strong>ERROR</strong>: The user doesn't exist."));
			//return $error;
			$role = implode(', ', $user_obj->roles);
			if($role <> self::DOLIBARR_ROLE) $role_allow = true;
		}
		//svar_dump($user_obj->ID);exit;
		//$resp = $this->createTransaction($user_obj->ID, 8);
		//var_dump($resp,'response');exit;
		// Si c'est pas le profile de lecteur de dolibarr
		// authentication normale de wp
		if ($role_allow) {
			//if ($role_allow) {
			return wp_authenticate_username_password($user, $username, $password);
			 
		} else { // verifier dans dolibarr
			$rep =  $this->dolibarr->dolibarr_check_authentication($username, $password);
			if (isset($rep ["result"]["result_code"]) && $rep ["result"]["result_code"]=='OK') {
				if (username_exists($username)) {
					$user = get_userdatabylogin($username);
					return $user;

				} else {
					
					$userdata = array(
							'user_login'  =>  $username,
							'user_pass'   =>  $password ,
							'user_email' => is_email($username)?$username:null,
							'role' => self::DOLIBARR_ROLE
					);
					$user_id = wp_insert_user( $userdata ) ;
					
					//On success
					if (!is_wp_error($user_id)) {
						$resp = $this->createTransaction($user_id, 8);
						//var_dump($resp,'response');exit;
						$user = get_userdatabylogin($username);
						return $user;
					} else {
						$error = new WP_Error();
						$error->add('registration_error', __('<strong>ERROR</strong>: There was an error registering your account. Please try again.'));
						return $error;
					}
				}
				 
			} else {
				$error = new WP_Error();
				$error->add('incorrect_credentials', __('<strong>ERROR</strong>:'.$rep ["result"]["result_label"]));
				return $error;
			}
			 
		}
	}
	 function my_password_reset( $user, $new_pass) {
	 	$local_admin = false;
	 	if (user_can($user, 'update_core')) $local_admin = true;
	 	
	 	if (!$local_admin) {
             //       $this->dolibarr->dolibarr_setPassword($user->user_login,$new_pass);
                    // password changed...
                }
	}
	function createRole() {
		$result = add_role(
				self::DOLIBARR_ROLE,
				__( 'subscriber of dolibarr' ),
				array(
						'read'         => true,  // true allows this capability
						'level_0' => true, // Use false to explicitly deny
				)
		);
	}
	
public function createTransaction($user_id,$product_id) {
		global $wpdb;
		$table = "{$wpdb->prefix}mepr_transactions";
		
		$data = array(
				//'id'              => 0,
				'amount'          => 0.00,
				'total'           => 0.00,
				'tax_amount'      => 0.00,
				'tax_rate'        => 0.00,
				'tax_desc'        => '',
				'tax_class'       => 'standard',
				'user_id'         => $user_id,
				'product_id'      => $product_id,
				'coupon_id'       => 0,
				'trans_num'       => 'mp-txn-'.uniqid(),
				'status'          => "confirmed",
				'txn_type'        => "payment",
				'response'        => '',
				'gateway'         => 'MeprPayPalGateway',
				'prorated'        => null,
				'ip_addr'         => $_SERVER['REMOTE_ADDR'],
				'created_at'      => null,
				'expires_at'      => null, // 0 = lifetime, null = default expiration for membership
				'subscription_id' => 0
		);
		return $wpdb->insert( $table,$data );
		
	
	}
}

new Wpdoli();
