<?php
/**
 * Vimeo shortcode.
 *
 * Examples:
 * [vimeo 141358]
 * [vimeo http://vimeo.com/141358]
 * [vimeo id=141358]
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
 * Boldermail_Shortcode_Vimeo class.
 *
 * @since 1.2.0
 */
class Boldermail_Shortcode_Vimeo {

	/**
	 * Vimeo shortcode.
	 *
	 * @see    wp-content/plugins/jetpack/modules/shortcodes/vimeo.php
	 * @since  1.2.0
	 * @param  array $atts Shortcode attributes.
	 * @return string
	 */
	public static function do_shortcode( $atts ) {

		$attr = array_map( 'intval', shortcode_atts( [ 'id' => 0 ], $atts ) );

		if ( isset( $atts[0] ) ) {
			$attr['id'] = self::get_id( $atts );
		}

		if ( ! $attr['id'] ) {
			return '<!-- Vimeo error: not a Vimeo video -->';
		}

		$thumbnail = self::get_thumbnail( $attr['id'] );

		if ( $thumbnail ) {
			return '<span class="embed-vimeo" style="text-align: center; display: block;"><a href="' . esc_url( 'https://vimeo.com/' . $attr['id'] ) . '" target="_blank" rel="noreferrer noopener">' . boldermail_kses_post( $thumbnail ) . '</a></span>';
		}

		return '';

	}

	/**
	 * Extract Vimeo ID from shortcode.
	 *
	 * @since  2.3.0
	 * @param  array $atts Shortcode attributes.
	 * @return int         Vimeo ID.
	 */
	public static function get_id( $atts ) {

		if ( isset( $atts[0] ) ) {
			$atts[0] = trim( $atts[0], '=' );
			$id      = false;
			if ( is_numeric( $atts[0] ) ) {
				$id = (int) $atts[0];
			} elseif ( preg_match( '|vimeo\.com/(\d+)/?$|i', $atts[0], $match ) ) {
				$id = (int) $match[1];
			} elseif ( preg_match( '|player\.vimeo\.com/video/(\d+)/?$|i', $atts[0], $match ) ) {
				$id = (int) $match[1];
			}

			return $id;
		}

		return 0;

	}

	/**
	 * Get the Vimeo video thumbnail HTML from the Vimeo video ID.
	 *
	 * @since  1.0.0
	 * @param  string $id Vimeo video ID.
	 * @return string
	 */
	public static function get_thumbnail( $id ) {

		$thumbnail_html = '';
		$thumbnail_id   = get_transient( "boldermail_vimeo_embed_thumbnail_{$id}" );
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

				$attachment_id = Boldermail_Image_Editor::insert_base64_attachment( $thumbnail_img, BOLDERMAIL_PREFIX . "_vimeo_thumbnail_$id.jpeg" );

				if ( $attachment_id ) {
					set_transient( "boldermail_vimeo_embed_thumbnail_{$id}", $attachment_id );
					$thumbnail_src = wp_get_attachment_image_src( $attachment_id, 'full' )[0];
				}

			} else {
				$thumbnail_html .= '<!-- Unable to generate thumbnail image watermark -- using default Vimeo watermark -->';
			}

		}

		$thumbnail_html .= ( $thumbnail_src ) ? '<img src="' . esc_url( $thumbnail_src ) . '" alt="Vimeo video" width="' . esc_attr( $thumbnail_size['width'] ) . '" />' : false;

		return $thumbnail_html;

	}

	/**
	 * Get the Vimeo video thumbnail URL from the Vimeo video ID.
	 *
	 * @since  1.0.0
	 * @param  string $id Vimeo video ID.
	 * @return string
	 */
	public static function get_thumbnail_url( $id ) {

		$vimeo_json = json_decode( file_get_contents( "https://vimeo.com/api/oembed.json?url=https://vimeo.com/{$id}&height=720" ) ); /* phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents */

		return $vimeo_json->thumbnail_url;

	}

}
