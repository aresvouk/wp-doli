<?php
/*
 Plugin Name: WPDoli
Plugin URI: https://github.com/tonin/abonibarr
Description: Integration de wordpress dans dolibrr
Version: 0.1
Author: cassiopea
Author URI: http://www.cassiopea.org/
License: GPL-3.0+
Text Domain: WPDoli
Domain Path: /languages
*/
// Initialize constants.
define( 'WPDOLI_VERSION', '0.1' );
define( 'WPDOLI_DEBUG', false );
if ( false === extension_loaded( 'soap' ) ) {
	esc_html_e( __( 'This plugin needs SOAP PHP extension.', 'doliwoo' ) );
	exit;
}
define( 'WPDOLI_DIR',  plugin_dir_url ( __FILE__ ) );
define( 'WPDOLI_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPDOLI_PATH_INC', plugin_dir_path( __FILE__ ).'include/');
define( 'NUSOAP_PATH', plugin_dir_path( __FILE__ ).'include/nusoap/lib/');
define( 'GOOGLE_PATH', plugin_dir_path( __FILE__ ).'include/google/');


include_once WPDOLI_PATH_INC.'wp-doli-wc-integration.php';

Class Wpdoli {
	public $dolibarr;
	function __construct(){
		$this->init();
	}
	function init() {
		$this->dolibarr =  new WPDoli_WC_Integration();
		// Localization.
		//add_action( 'plugins_loaded', 'wpdoli_load_textdomain' );
		register_activation_hook( __FILE__, array($this,'wpdoli_install' ));
		register_uninstall_hook(__FILE__, array($this,'wpdoli_uninstall'));
		
		add_action('admin_menu', array($this, 'add_admin_menu'));
		//add_submenu_page('wpdoli', 'Apercu', 'Apercu', 'manage_options', 'wpdoli', array($this, 'menu_html'));
		//echo WPDOLI_PATH_INC.'wp-doli-admin.php';exit;
		include_once WPDOLI_PATH_INC.'wp-doli-admin.php';
		include_once WPDOLI_PATH_INC.'wp-doli-shortcode.php';
		new WPDoliAdmin($this->dolibarr);
		new WPDoliShortcode($this->dolibarr);
		
		add_action ('wp_authenticate' , array($this,'check_custom_authentication'));
		
	}
	/**
	 * Filter translation file.
	 *
	 * @param string $file The translation file to load.
	 */
	function wpmem_load_textdomain() {


		$file = apply_filters( 'wpdoli_localization_file', dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		// Load the localization file.
		load_plugin_textdomain( 'wpdoli', false, $file );

		return;
	}
	public  function wpdoli_install()
	{
		global $wpdb;
	
		$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}zero_newsletter_email (id INT AUTO_INCREMENT PRIMARY KEY, email VARCHAR(255) NOT NULL);");
	}
	
	public  function wpdoli_uninstall()
	{
		global $wpdb;
	
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}zero_newsletter_email;");
	}
	public function add_admin_menu()
	{
		add_menu_page('Plugin d\'integration de dolibarr', 'wpdoli', 'manage_options', 'wpdoli', array($this, 'menu_html'));
	}
	
	
	public function menu_html()
	{
		echo '<h1>'.get_admin_page_title().'</h1>';
		//echo '<p>Bienvenue sur la page d\'accueil du plugin</p>';
	}
	function check_custom_authentication ($username) {
		global $wpdb;
		
		if (!username_exists($username)) {
			echo 'bye';exit;
			//wp_redirect( get_permalink( $post->post_parent )); exit;
		}
	}
}
new Wpdoli();