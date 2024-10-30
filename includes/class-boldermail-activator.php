<?php
/**
 * Fired during plugin activation.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Activator class.
 *
 * @since 1.0.0
 */
class Boldermail_Activator {

	/**
	 * Fired during plugin activation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function activate() {

		/**
		 * Add option to flush the rewrite rules after custom post types are registered.
		 *
		 * @since 1.0.0
		 */
		if ( ! get_option( 'boldermail_queue_flush_rewrite_rules' ) ) {
			add_option( 'boldermail_queue_flush_rewrite_rules', 'yes' );
		}

	}

}
