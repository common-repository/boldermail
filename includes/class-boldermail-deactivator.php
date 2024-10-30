<?php
/**
 * Fired during plugin deactivation.
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
 * Boldermail_Deactivator class.
 *
 * @since   1.0.0
 */
class Boldermail_Deactivator {

	/**
	 * Fired during plugin deactivation.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public static function deactivate() {

		/**
		 * Delete option to flush the rewrite rules.
		 *
		 * @see     https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
		 * @since   1.0.0
		 */
		delete_option( 'boldermail_queue_flush_rewrite_rules' );

		/**
		 * Flush rewrite rules.
		 *
		 * @see     https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/
		 * @since   1.0.0
		 */
		flush_rewrite_rules();

	}

}
