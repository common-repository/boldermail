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

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-youtube.php';
require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcode-vimeo.php';

/**
 * Define the embed handlers with their regexes and their callbacks.
 *
 * @since 2.3.0
 */
define(
	'BOLDERMAIL_EMBED_HANDLERS',
	[
		'youtube'   => [
			'regex'    => '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/',
			'callback' => [ 'Boldermail_Shortcode_YouTube', 'do_shortcode' ],
		],
		'vimeo'     => [
			'regex'    => '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([‌​0-9]{6,11})[?]?.*/',
			'callback' => [ 'Boldermail_Shortcode_Vimeo', 'do_shortcode' ],
		],
		'facebook'  => [
			'regex' => '#^https?://(www.)?facebook\.com/([^/]+)/(posts|photos)/([^/]+)?#',
		],
		'instagram' => [
			'regex'    => '#http(s?)://(www\.)?instagr(\.am|am\.com)/p/([^/?]+)#i',
			'callback' => [ 'Boldermail_Shortcode_Instagram', 'do_shortcode' ],
		],
		'twitter'   => [
			'regex'    => '/^http(s|):\/\/twitter\.com(\/#!\/|\/)([a-zA-Z0-9_]{1,20})\/status(es)*\/(\d+)$/',
			'callback' => [ 'Boldermail_Shortcode_Tweet', 'do_shortcode' ],
		],
	]
);

/**
 * Boldermail_Shortcode_Embed class.
 *
 * @since 1.2.0
 */
class Boldermail_Shortcode_Embed {

	/**
	 * Do embed shortcode.
	 *
	 * @since  1.2.0
	 * @param  array  $attr Shortcodes attributes.
	 * @param  string $url  URL content.
	 * @return string
	 */
	public static function do_shortcode( $attr, $url = '' ) {

		if ( empty( $url ) && ! empty( $attr['url'] ) ) {
			$url = $attr['url'];
		}

		if ( empty( $url ) ) {
			return '<!-- Empty URL -->';
		}

		foreach ( BOLDERMAIL_EMBED_HANDLERS as $id => $handler ) {
			if ( preg_match( $handler['regex'], $url, $matches ) && is_callable( $handler['callback'] ) ) {
				return call_user_func( $handler['callback'], $matches, [ $url ] );
			}
		}

		/**
		 * Should we return the original URL or an empty string?
		 * Returning message for now.
		 *
		 * @since 1.2.0
		 */
		return '<!-- Boldermail embed error: Service not supported -->';

	}

}
