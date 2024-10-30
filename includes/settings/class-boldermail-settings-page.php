<?php
/**
 * Boldermail settings page/tab.
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
 * Boldermail_Settings_Page class.
 *
 * @since 1.7.0
 */
abstract class Boldermail_Settings_Page {

	/**
	 * Setting page id.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $id = '';

	/**
	 * Setting page label.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	protected $label = '';

	/**
	 * Constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {

		add_filter( 'boldermail_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'boldermail_sections_' . $this->id, array( $this, 'output_sections' ) );
		add_action( 'boldermail_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'boldermail_settings_save_' . $this->id, array( $this, 'save' ) );

	}

	/**
	 * Get settings page ID.
	 *
	 * @since  1.7.0
	 * @return string
	 */
	public function get_id() {

		return $this->id;

	}

	/**
	 * Get settings page label.
	 *
	 * @since  1.7.0
	 * @return string
	 */
	public function get_label() {

		return $this->label;

	}

	/**
	 * Add this page to settings.
	 *
	 * @since  1.7.0
	 * @param  array $pages Settings pages.
	 * @return array
	 */
	public function add_settings_page( $pages ) {

		$pages[ $this->id ] = $this->label;

		return $pages;

	}

	/**
	 * Get settings array.
	 *
	 * @since  1.7.0
	 * @return array
	 */
	public function get_settings() {

		return apply_filters( 'boldermail_get_settings_' . $this->id, array() );

	}

	/**
	 * Get sections.
	 *
	 * @since  1.7.0
	 * @return array
	 */
	public function get_sections() {

		return apply_filters( 'boldermail_get_sections_' . $this->id, array() );

	}

	/**
	 * Output sections.
	 *
	 * @since  1.7.0
	 */
	public function output_sections() {

		global $current_section;

		$sections = $this->get_sections();

		if ( empty( $sections ) || 1 === count( $sections ) ) {
			return;
		}

		echo '<ul class="subsubsub">';

		$array_keys = array_keys( $sections );

		foreach ( $sections as $id => $label ) {
			echo '<li><a href="' . esc_url( admin_url( 'edit.php?post_type=bm_newsletter&page=boldermail-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) ) . '" class="' . ( $current_section === $id ? 'current' : '' ) . '">' . esc_html( $label ) . '</a> ' . ( end( $array_keys ) === $id ? '' : '|' ) . ' </li>';
		}

		echo '</ul><br class="clear" />';

	}

	/**
	 * Output the settings.
	 *
	 * @since  1.7.0
	 */
	public function output() {

		$settings = $this->get_settings();

		Boldermail_Settings::output_fields( $settings );

	}

	/**
	 * Save settings.
	 *
	 * @since  1.7.0
	 */
	public function save() {

		global $current_section;

		$settings = $this->get_settings();
		Boldermail_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'boldermail_update_options_' . $this->id . '_' . $current_section );
		}

	}

}
