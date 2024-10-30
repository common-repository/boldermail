<?php
/**
 * Instagram shortcode.
 *
 * Examples:
 * [instagram https://www.instagram.com/p/CGu-p4ihOVK/]
 * https://www.instagram.com/p/CGu-p4ihOVK/
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
 * Boldermail_Shortcode_Instagram class.
 *
 * @since 2.3.0
 */
class Boldermail_Shortcode_Instagram {

	/**
	 * Instagram shortcode.
	 *
	 * @since  2.3.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public static function do_shortcode( $atts ) {

		$shortcode_id = isset( $atts[0] ) ? self::get_id( $atts[0] ) : '';

		if ( ! $shortcode_id ) {
			return '<!-- Instagram error: not a post -->';
		}

		$instagram_settings = boldermail_get_option( 'boldermail_instagram_integration' );

		if ( ! $instagram_settings || ! isset( $instagram_settings['user_id'] ) || empty( $instagram_settings['user_id'] ) ) {
			return '<!-- No Instagram connection -->';
		}

		$user_id = $instagram_settings['user_id'][0];

		$instagram_api = new Boldermail_Instagram_API( $user_id, $instagram_settings['connected_accounts'][ $user_id ]['access_token'] );

		$media_data = $instagram_api->get_media_by_shortcode( $shortcode_id );

		if ( is_wp_error( $media_data ) ) {
			return '<!-- Instagram API error -->';
		}

		ob_start();
		load_template( BOLDERMAIL_PLUGIN_DIR . 'partials/social/html-boldermail-social-blocks-api-block-instagram.php', false, [ 'media_data' => $media_data ] );
		return ob_get_clean(); // Do not strip newline characters to keep spacing in Instagram blocks.

	}

	/**
	 * Get the Instagram media ID from a URL.
	 *
	 * @see    jetpack_shortcode_instagram /wp-content/plugins/jetpack/modules/shortcodes/instagram.php
	 * @since  2.3.0
	 * @param  string $url Instagram URL.
	 * @return int|null    The media ID.
	 */
	public static function get_id( $url ) {

		preg_match( '#http(s?)://(www\.)?instagr(\.am|am\.com)/p/([^/?]+)#i', $url, $matches );
		$shortcode_id = end( $matches );

		return $shortcode_id ?? null;

	}

}
