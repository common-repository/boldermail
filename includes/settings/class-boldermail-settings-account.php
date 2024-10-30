<?php
/**
 * Boldermail settings - Account tab.
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
 * Boldermail_Settings_Account class.
 *
 * @since 1.7.0
 */
class Boldermail_Settings_Account extends Boldermail_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since  1.7.0
	 */
	public function __construct() {

		$this->id    = 'account';
		$this->label = __( 'Account', 'boldermail' );

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
			'boldermail_account_settings',
			array(
				array(
					'title' => __( 'Boldermail Credentials', 'boldermail' ),
					'type'  => 'title',
					'desc'  => $this->get_api_connectivity_message(),
					'id'    => 'account_options',
				),

				array(
					'title'             => __( 'Installation URL', 'boldermail' ),
					'desc'              => __( 'Write your Boldermail installation URL above (with <code>https://</code> and without the trailing slash). For example, <code>https://yourusername.boldermail.com</code>.', 'boldermail' ),
					'id'                => 'boldermail_url',
					'default'           => '',
					'type'              => 'url',
					'desc_tip'          => true,
					'custom_attributes' => array( 'required' => 'required' ),
				),

				array(
					'title'             => __( 'API key', 'boldermail' ),
					'desc'              => __( 'You can retrieve your API key from your Boldermail welcome email.', 'boldermail' ),
					'id'                => 'boldermail_api',
					'default'           => '',
					'type'              => 'password',
					'desc_tip'          => true,
					'custom_attributes' => array( 'required' => 'required' ),
				),

				array(
					'title'    => __( 'Access token', 'boldermail' ),
					'desc'     => __( 'You can retrieve your access token from your Boldermail welcome email.', 'boldermail' ),
					'id'       => 'boldermail_token',
					'default'  => '',
					'type'     => 'password',
					'desc_tip' => true,
				),

				array(
					'title'             => __( 'Application ID', 'boldermail' ),
					'desc'              => __( 'You can retrieve your application ID from your Boldermail welcome email.', 'boldermail' ),
					'id'                => 'boldermail_app',
					'default'           => '',
					'type'              => 'number',
					'desc_tip'          => true,
					'custom_attributes' => array( 'required' => 'required' ),
				),

				array(
					'type' => 'sectionend',
					'id'   => 'account_options',
				),
			)
		);

		return apply_filters( 'boldermail_get_settings_' . $this->id, $settings );

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
	 * @since 1.7.0
	 */
	public function save() {

		$settings = $this->get_settings();

		Boldermail_Settings::save_fields( $settings );

	}

	/**
	 * Validate the newly saved API settings, and display a message to the user.
	 *
	 * @since  1.7.0
	 * @return string
	 */
	private function get_api_connectivity_message() {

		// Verify the API credentials with the current saved settings.
		$message = __( 'Connect your WordPress site to your Boldermail account using your API credentials.', 'boldermail' );

		if ( ( $url = get_option( 'boldermail_url' ) ) && ( $key = get_option( 'boldermail_api' ) ) && ( $app = get_option( 'boldermail_app' ) ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */

			$api = new Boldermail_API( $url, $key, $app );

			$error = $api->verify_key();

			if ( is_wp_error( $error ) ) {
				$message = '<div class="notice notice-alt notice-error inline">' . $error->get_error_message() . '</div>';
			} else {
				$message = '<div class="notice notice-alt notice-success inline">' . wpautop( wp_kses_post( __( 'The connection to your Boldermail account is <strong>active</strong> and <strong>working</strong>.', 'boldermail' ) ) ) . '</div>';
			}

		}

		return $message;

	}

}

return new Boldermail_Settings_Account();
