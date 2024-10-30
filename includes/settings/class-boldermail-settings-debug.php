<?php
/**
 * Boldermail settings - Debug tab.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.7.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Settings_Debug class.
 *
 * @since 1.7.0
 */
class Boldermail_Settings_Debug extends Boldermail_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since  1.7.0
	 */
	public function __construct() {

		$this->id    = 'debug';
		$this->label = __( 'Debug', 'boldermail' );

		parent::__construct();

	}

	/**
	 * Get settings array.
	 *
	 * @since  1.7.0
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters(
			'boldermail_debug_settings',
			array(
				array(
					'title' => 'Debugging Options',
					'type'  => 'title',
					'desc'  => __( 'Display troubleshooting information on Boldermail admin pages.', 'boldermail' ),
					'id'    => 'debug_options',
				),

				array(
					'title'         => __( 'Debug', 'boldermail' ),
					'desc'          => __( 'Show additional information in admin notices and error messages', 'boldermail' ),
					'id'            => 'boldermail_show_debug',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
					'autoload'      => false,
				),

				array(
					'desc'          => __( 'Show hidden meta data', 'boldermail' ),
					'id'            => 'boldermail_show_hidden_meta',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'end',
					'autoload'      => false,
				),

				array(
					'type' => 'sectionend',
					'id'   => 'debug_options',
				),
			)
		);

		return apply_filters( 'boldermail_get_settings_' . $this->id, $settings );

	}

	/**
	 * Output the settings.
	 *
	 * @since 1.7.0
	 */
	public function output() {

		$settings = $this->get_settings();

		Boldermail_Settings::output_fields( $settings );

	}

	/**
	 * Save settings.
	 *
	 * @since 1.7.0
	 */
	public function save() {

		$settings = $this->get_settings();

		Boldermail_Settings::save_fields( $settings );

	}

}

return new Boldermail_Settings_Debug();
