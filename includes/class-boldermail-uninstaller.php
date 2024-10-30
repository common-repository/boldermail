<?php
/**
 * Fired during plugin uninstallation.
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
 * Boldermail_Uninstaller class.
 *
 * @since 1.0.0
 */
class Boldermail_Uninstaller {

	/**
	 * Fired during plugin uninstallation.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function uninstall() {

		/**
		 * Delete CRON hooks.
		 *
		 * @since 1.0.0
		 */
		self::delete_rss_campaigns();

		/**
		 * Disconnect Instagram API.
		 *
		 * @since 2.3.0
		 */
		self::disconnect_instagram_api();

	}

	/**
	 * Clear RSS campaigns CRON jobs.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private static function delete_rss_campaigns() {

		$newsletter_ids = get_posts(
			array(
				'fields'         => 'ids',
				'posts_per_page' => -1,
				'post_type'      => 'bm_newsletter_rss',
			)
		);

		if ( $newsletter_ids && count( $newsletter_ids ) > 0 ) {

			foreach ( $newsletter_ids as $newsletter_id ) {

				$newsletter = boldermail_get_newsletter( $newsletter_id );

				if ( $newsletter ) {
					Boldermail_Cron::clear_scheduled_rss_campaign( $newsletter );
				}

			}

		}

	}

	/**
	 * Disconnect the Instagram API by deleting the access tokens.
	 *
	 * @since 2.3.0
	 */
	private static function disconnect_instagram_api() {

		$instagram_settings = boldermail_get_option( 'boldermail_instagram_integration' );

		if ( ! empty( $instagram_settings['user_id'] ) ) {
			foreach ( $instagram_settings['user_id'] as $user_id ) {

				wp_clear_scheduled_hook( 'boldermail_instagram_integration_refresh_token', array( $user_id ) );

				$instagram_settings['user_id'] = ( $instagram_settings['user_id'] ) ? array_diff( $instagram_settings['user_id'], [ $user_id ] ) : [];
				unset( $instagram_settings['connected_accounts'][ $user_id ] );

			}

		}

		update_option( 'boldermail_instagram_integration', $instagram_settings, 'no' );

	}

}
