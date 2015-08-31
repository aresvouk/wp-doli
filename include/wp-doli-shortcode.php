<?php
include_once 'wp-doli-form.php';
class WPDoliShortcode {
	public $dolibarr;
	function __construct($dolibarr) {
		// Register a new shortcode: [cr_custom_registration]
		add_shortcode( 'cr_custom_registration', array($this,'custom_registration_shortcode' ));
		$this->dolibarr = $dolibarr;
		
	}
	
	// The callback function that will replace [book]
	function custom_registration_shortcode() {
		
		//var_dump($prod);exit;
		$form = new WPDoliFormAbonnement($this->dolibarr);
		ob_start();
		$form->custom_registration_function();
		return ob_get_clean();
	}
}