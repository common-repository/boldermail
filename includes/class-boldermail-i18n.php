<?php
/**
 * Define the internationalization functionality.
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
 * Boldermail_I18n class.
 *
 * @since 1.7.0
 */
class Boldermail_I18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function load_plugin_textdomain() {

		load_plugin_textdomain(
			'boldermail',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/includes/i18n/'
		);

	}

}

Boldermail_I18n::load_plugin_textdomain();
