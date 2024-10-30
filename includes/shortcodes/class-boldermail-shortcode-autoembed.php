<?php
/**
 * Embed shortcode.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.2.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-embed.php';

/**
 * Boldermail_Shortcode_AutoEmbed class.
 *
 * @since 1.2.0
 */
class Boldermail_Shortcode_AutoEmbed {

	/**
	 * Passes any unlinked URLs that are on their own line for potential embedding.
	 *
	 * @since  1.2.0
	 * @param  string $content Shortcode content.
	 * @return string
	 */
	public static function do_shortcode( $content ) {

		// Replace line breaks from all HTML elements with placeholders.
		$content = wp_replace_in_html_tags( $content, array( "\n" => '<!-- wp-line-break -->' ) );

		if ( preg_match( '#(^|\s|>)https?://#i', $content ) ) {
			// Find URLs on their own line.
			$content = preg_replace_callback( '|^(\s*)(https?://[^\s<>"]+)(\s*)$|im', array( __CLASS__, 'autoembed_callback' ), $content );
			// Find URLs in their own paragraph.
			$content = preg_replace_callback( '|(<p(?: [^>]*)?>\s*)(https?://[^\s<>"]+)(\s*</p>)|i', array( __CLASS__, 'autoembed_callback' ), $content );
		}

		// Put the line breaks back.
		return str_replace( '<!-- wp-line-break -->', "\n", $content );

	}

	/**
	 * Callback function for Boldermail_Shortcode_AutoEmbed::do_shortcode().
	 *
	 * @since  1.2.0
	 * @param  array $match A regex match array. Example:
	 *                      `Array
	 *                      (
	 *                          [0] => <p>https://www.youtube.com/watch?v=rU8FLDajM8Q</p>
	 *                          [1] => <p>
	 *                          [2] => https://www.youtube.com/watch?v=rU8FLDajM8Q
	 *                          [3] => </p>
	 *                      )`
	 *                      Used to extract the URL.
	 * @return string       The embed HTML on success, otherwise the original URL.
	 */
	public static function autoembed_callback( $match ) {
		return Boldermail_Shortcode_Embed::do_shortcode( array(), $match[2] );
	}

}
