<?php
/**
 * Boldermail Error API.
 *
 * Contains the Boldermail_Error class.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.4.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Error class.
 *
 * Container for checking for WordPress errors and error messages. Return
 * Boldermail_Error and use is_wp_error() to check if this class is returned.
 *
 * @since   1.4.0
 */
class Boldermail_Error extends WP_Error {

	/**
	 * Initialize the error.
	 *
	 * @since   1.4.0
	 *
	 * @param   string|int  $code     Error code.
	 * @param   string      $message  Error message.
	 * @param   mixed       $data     Optional. Error data.
	 */
	public function __construct( $code = '', $message = '', $data = '' ) {

		if ( $code && empty( $message ) ) {
			parent::__construct( $code, boldermail_get_error_message( $code ), $data );
			return;
		}

		parent::__construct( $code, $message, $data );

	}

}
