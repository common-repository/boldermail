<?php
/**
 * YouTube shortcode.
 *
 * Examples:
 * [youtube https://www.youtube.com/watch?v=WVbQ-oro7FQ]
 * http://www.youtube.com/v/9FhMMmqzbD8
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.2.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/tools/class-boldermail-image-editor.php';

/**
 * Boldermail_Shortcode_YouTube class.
 *
 * @since 1.2.0
 */
class Boldermail_Shortcode_YouTube {

	/**
	 * YouTube shortcode.
	 *
	 * @since  1.2.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public static function do_shortcode( $atts ) {

		$url = isset( $atts[0] ) ? self::sanitize_url( $atts[0] ) : '';
		$id  = self::get_id( $url );

		if ( ! $id ) {
			return '<!-- YouTube error: bad URL entered -->';
		}

		$thumbnail = self::get_thumbnail( $id );

		if ( $thumbnail ) {
			return '<span class="embed-youtube" style="text-align: center; display: block;"><a href="' . esc_url( $url ) . '" target="_blank" rel="noreferrer noopener">' . boldermail_kses_post( $thumbnail ) . '</a></span>';
		}

		return '';

	}

	/**
	 * Get the YouTube video ID from a YouTube URL.
	 *
	 * @see    jetpack_shortcode_get_youtube_id()  /wp-content/plugins/jetpack/functions.compat.php:12
	 * @since  1.2.0
	 * @param  string $url Can be just the URL or the whole $atts array.
	 * @return bool|mixed  The YouTube video ID.
	 */
	public static function get_id( $url ) {

		// Do we have an $atts array? get first attribute.
		if ( is_array( $url ) ) {
			$url = reset( $url );
		}

		$url = self::sanitize_url( $url );
		$url = wp_parse_url( $url );
		$id  = false;

		if ( ! isset( $url['query'] ) ) {
			return false;
		}

		parse_str( $url['query'], $qargs );

		if ( ! isset( $qargs['v'] ) && ! isset( $qargs['list'] ) ) {
			return false;
		}

		if ( isset( $qargs['list'] ) ) {
			$id = preg_replace( '|[^_a-z0-9-]|i', '', $qargs['list'] );
		}

		if ( empty( $id ) ) {
			$id = preg_replace( '|[^_a-z0-9-]|i', '', $qargs['v'] );
		}

		return $id;

	}

	/**
	 * Get the YouTube video thumbnail HTML from the YouTube video ID.
	 *
	 * @since  1.0.0
	 * @param  string $id YouTube video ID.
	 * @return string
	 */
	public static function get_thumbnail( $id ) {

		$thumbnail_html = '';
		$thumbnail_id   = get_transient( "boldermail_youtube_embed_thumbnail_{$id}" );
		$thumbnail_size = boldermail_get_image_sizes( 'boldermail_newsletter' );
		$thumbnail_src  = $thumbnail_id ? $thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'full' )[0] : '';

		// If the image was never created before, or if it vanished...
		if ( ! $thumbnail_id || ! $thumbnail_src ) {

			$thumbnail_src = self::get_thumbnail_url( $id );
			$thumbnail_img = Boldermail_Image_Editor::add_video_watermark( $thumbnail_src );

			/**
			 * Insert base64 image into WordPress uploads directory.
			 *
			 * @see   Boldermail_Image_Editor::add_video_watermark The function returns a base64 encoded JPEG image.
			 * @since 1.2.0
			 */
			if ( $thumbnail_img ) {

				$attachment_id = Boldermail_Image_Editor::insert_base64_attachment( $thumbnail_img, BOLDERMAIL_PREFIX . "_youtube_thumbnail_$id.jpeg" );

				if ( $attachment_id ) {
					set_transient( "boldermail_youtube_embed_thumbnail_{$id}", $attachment_id );
					$thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'full' )[0];
				}

			} else {
				$thumbnail_html .= '<!-- Unable to generate thumbnail image watermark -- using default YouTube watermark -->';
			}

		}

		$thumbnail_html .= ( $thumbnail_src ) ? '<img src="' . esc_url( $thumbnail_src ) . '" alt="YouTube video" width="' . esc_attr( $thumbnail_size['width'] ) . '" />' : false;

		return $thumbnail_html;

	}

	/**
	 * Get the YouTube video thumbnail URL from the YouTube video ID.
	 *
	 * @since  1.0.0
	 * @param  string $id YouTube video ID.
	 * @return string
	 */
	public static function get_thumbnail_url( $id ) {

		return esc_url( 'http://img.youtube.com/vi/' . $id . '/maxresdefault.jpg' );

	}

	/**
	 * Normalizes a YouTube URL to include a v= parameter
	 * and a query string free of encoded ampersands.
	 *
	 * @since  1.2.0
	 * @param  string $url The video URL.
	 * @return string      The normalized URL.
	 */
	public static function sanitize_url( $url ) {

		$url = trim( $url, ' "' );
		$url = trim( $url );
		$url = str_replace( array( 'youtu.be/', '/v/', '#!v=', '&amp;', '&#038;', 'playlist' ), array( 'youtu.be/?v=', '/?v=', '?v=', '&', '&', 'videoseries' ), $url );

		// Replace any extra question marks with ampersands - the result of a URL like "http://www.youtube.com/v/9FhMMmqzbD8?fs=1&hl=en_US" being passed in.
		$query_string_start = strpos( $url, '?' );

		if ( false !== $query_string_start ) {
			$url = substr( $url, 0, $query_string_start + 1 ) . str_replace( '?', '&', substr( $url, $query_string_start + 1 ) );
		}

		return $url;

	}

}
