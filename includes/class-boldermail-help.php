<?php
/**
 * Admin help.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Help class.
 *
 * @since 1.7.0
 */
class Boldermail_Help {

	/**
	 * Initialize the hooks.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function init() {

		add_action( 'current_screen', array( __CLASS__, 'add_tabs' ), 50 );

	}

	/**
	 * Add help tabs.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function add_tabs() {

		$screen = get_current_screen();

		if ( ! $screen || ! in_array( $screen->id, boldermail_get_screen_ids(), true ) ) {
			return;
		}

	}

}

Boldermail_Help::init();
