<?php
/**
 * Boldermail settings - Integrations tab.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.3.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Settings_Integrations class.
 *
 * @since 2.3.0
 */
class Boldermail_Settings_Integrations extends Boldermail_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since 2.3.0
	 */
	public function __construct() {

		$this->id    = 'integrations';
		$this->label = __( 'Integrations', 'boldermail' );

		add_action( 'boldermail_admin_field_social_integrations', [ $this, 'output_social_integrations_setting' ] );
		add_action( 'boldermail_settings_start', [ $this, 'save_integration_tokens' ] );

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
			'boldermail_integrations_settings',
			array(
				array(
					'title' => __( 'Integrations', 'boldermail' ),
					'type'  => 'title',
					'desc'  => __( 'Connect Boldermail to other platforms to display your content in your newsletters.', 'boldermail' ),
					'id'    => 'integrations_options',
				),
				array(
					'type' => 'social_integrations',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'account_options',
				),
			)
		);

		$GLOBALS['hide_save_button'] = true; /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound */

		return apply_filters( 'boldermail_get_settings_' . $this->id, $settings );

	}

	/**
	 * Save integration tokens after OAuth access.
	 *
	 * @since 2.3.0
	 */
	public function save_integration_tokens() {

		if ( ! isset( $_GET['bm_integrations_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['bm_integrations_nonce'] ), 'instagram' ) ) {
			return;
		}

		$integration = isset( $_GET['integration'] ) ? boldermail_sanitize_key( $_GET['integration'] ) : null;

		switch ( $integration ) {

			case 'instagram':
				$this->manage_instagram_integration();
				break;

		}

	}

	/**
	 * Manage the Instagram connect and disconnect actions.
	 *
	 * @since 2.3.0
	 */
	private function manage_instagram_integration() {

		if ( ! isset( $_GET['bm_integrations_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['bm_integrations_nonce'] ), 'instagram' ) ) {
			return;
		}

		$action  = isset( $_GET['action'] ) ? boldermail_sanitize_key( $_GET['action'] ) : null;
		$user_id = isset( $_GET['user_id'] ) ? boldermail_sanitize_int( $_GET['user_id'] ) : null;

		wp_clear_scheduled_hook( 'boldermail_instagram_integration_refresh_token', array( $user_id ) );

		switch ( $action ) {

			case 'connect':
				$access_token = isset( $_GET['access_token'] ) ? boldermail_sanitize_text( $_GET['access_token'] ) : null;
				$token_type   = isset( $_GET['token_type'] ) ? boldermail_sanitize_text( $_GET['token_type'] ) : null;
				$expires_in   = isset( $_GET['expires_in'] ) ? boldermail_sanitize_int( $_GET['expires_in'] ) : null;

				$instagram_settings = [
					'user_id'            => [
						$user_id,
					],
					'connected_accounts' => [
						$user_id => [
							'access_token' => $access_token,
							'token_type'   => $token_type,
							'user_id'      => $user_id,
							'expires_in'   => $expires_in,
						],
					],
				];

				update_option( 'boldermail_instagram_integration', $instagram_settings, 'no' );

				wp_schedule_single_event(
					time() + $expires_in - WEEK_IN_SECONDS,
					'boldermail_instagram_integration_refresh_token',
					array( $user_id )
				);

				break;

			case 'disconnect':
				$instagram_settings = boldermail_get_option( 'boldermail_instagram_integration' );

				$instagram_settings['user_id'] = ( $instagram_settings['user_id'] ) ? array_diff( $instagram_settings['user_id'], [ $user_id ] ) : [];
				unset( $instagram_settings['connected_accounts'][ $user_id ] );

				update_option( 'boldermail_instagram_integration', $instagram_settings, 'no' );

				break;

		}

	}

	/**
	 * Output the integrations options.
	 *
	 * @since 2.3.0
	 */
	public function output_social_integrations_setting() {

		?>
		<tr valign="top">
			<td class="boldermail-integrations-wrapper" colspan="2">
				<table class="boldermail-integrations widefat" cellspacing="0" aria-describedby="integrations_options-description">
					<tbody>
						<?php load_template( BOLDERMAIL_PLUGIN_DIR . 'partials/social/html-boldermail-social-blocks-api-integration-twitter.php', false ); ?>
						<?php load_template( BOLDERMAIL_PLUGIN_DIR . 'partials/social/html-boldermail-social-blocks-api-integration-instagram.php', false ); ?>
					</tbody>
				</table>
			</td>
		</tr>
		<?php

	}

}

return new Boldermail_Settings_Integrations();
