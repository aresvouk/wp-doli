<?php
/**
 * Allows log files to be written to for debugging purposes.
 *
 * @class 		WC_Logger
 * @version		1.6.4
 * @package		WooCommerce/Classes
 * @category	Class
 * @author 		WooThemes
 */
if ( ! class_exists( 'WC_Logger' ) ) :

class WC_Logger {

	/**
	 * @var array Stores open file _handles.
	 * @access private
	 */
	private $_handles;

	/**
	 * Constructor for the logger.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->_handles = array();
		$this->define( 'WC_LOG_DIR',  plugin_basename( __FILE__ )  . '/wc-logs/' );
		
	}


	/**
	 * Destructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __destruct() {
		foreach ( $this->_handles as $handle ) {
			@fclose( $handle );
		}
	}


	/**
	 * Open log file for writing.
	 *
	 * @access private
	 * @param mixed $handle
	 * @return bool success
	 */
	private function open( $handle ) {
		if ( isset( $this->_handles[ $handle ] ) ) {
			return true;
		}

		if ( $this->_handles[ $handle ] = @fopen( wc_get_log_file_path( $handle ), 'a' ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Add a log entry to chosen file.
	 *
	 * @access public
	 * @param string $handle
	 * @param string $message
	 * @return void
	 */
	public function add( $handle, $message ) {
		if ( $this->open( $handle ) && is_resource( $this->_handles[ $handle ] ) ) {
			$time = date_i18n( 'm-d-Y @ H:i:s -' ); // Grab Time
			@fwrite( $this->_handles[ $handle ], $time . " " . $message . "\n" );
		}
	}


	/**
	 * Clear entries from chosen file.
	 *
	 * @access public
	 * @param mixed $handle
	 * @return void
	 */
	public function clear( $handle ) {
		if ( $this->open( $handle ) && is_resource( $this->_handles[ $handle ] ) ) {
			@ftruncate( $this->_handles[ $handle ], 0 );
		}
	}
	/**
	 * Get a log file path
	 *
	 * @since 2.2
	 * @param string $handle name
	 * @return string the log file path
	 */
	function wc_get_log_file_path( $handle ) {
		return trailingslashit( WC_LOG_DIR ) . $handle . '-' . sanitize_file_name( wp_hash( $handle ) ) . '.log';
	}
	/**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
	
	

}
endif;