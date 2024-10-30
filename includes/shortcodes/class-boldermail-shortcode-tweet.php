<?php
/**
 * Tweet shortcode.
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
 * Boldermail_Shortcode_Tweet class.
 *
 * @since 2.3.0
 */
class Boldermail_Shortcode_Tweet {

	/**
	 * Tweet shortcode.
	 *
	 * @see    https://developer.twitter.com/en/docs/labs/tweets-and-users/api-reference/get-tweets-id
	 * @see    https://developer.twitter.com/en/docs/twitter-api/data-dictionary/object-model/tweet
	 * @see    https://stackoverflow.com/a/27213969/1991500 Convert Date to Day and Month (3 Letters)
	 * @see    https://stackoverflow.com/a/24605606/1991500 PHP date() returning format yyyy-mm-ddThh:mm:ss.uZ
	 * @see    https://stackoverflow.com/a/23087239/1991500 Convert one date format into another in PHP
	 * @see    https://stackoverflow.com/a/45201739/1991500 URL for a link to Twitter for a specific tweet
	 * @since  2.3.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public static function do_shortcode( $atts ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found */

		$tweet_id = isset( $atts[0] ) ? self::get_id( $atts[0] ) : '';

		if ( ! $tweet_id ) {
			return '<!-- Twitter error: invalid tweet ID -->';
		}

		$twitter_api = new Boldermail_Twitter_API( boldermail_get_username(), boldermail_get_option( 'boldermail_token' ) );

		$tweet_data = $twitter_api->get_tweet( $tweet_id );

		if ( is_wp_error( $tweet_data ) ) {
			return '<!-- Twitter API error -->';
		}

		ob_start();
		load_template( BOLDERMAIL_PLUGIN_DIR . 'partials/social/html-boldermail-social-blocks-api-block-tweet.php', false, [ 'tweet_data' => $tweet_data ] );
		return str_replace( array( "\n", "\r" ), '', ob_get_clean() );

	}

	/**
	 * Get the Twitter tweet ID from a URL.
	 *
	 * @see    Jetpack_Tweet::jetpack_tweet_shortcode /wp-content/plugins/jetpack/modules/shortcodes/tweet.php
	 * @since  2.3.0
	 * @param  string $url Tweet URL.
	 * @return int|null    The tweet ID.
	 */
	public static function get_id( $url ) {

		preg_match( '/^http(s|):\/\/twitter\.com(\/#!\/|\/)([a-zA-Z0-9_]{1,20})\/status(es)*\/(\d+)$/', $url, $urlbits );

		if ( isset( $urlbits[5] ) && intval( $urlbits[5] ) ) {
			return intval( $urlbits[5] );
		} else {
			return null;
		}

	}

}
