<?php
/**
 * Gallery shortcode.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.4.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Shortcode_Gallery class.
 *
 * @since 2.4.0
 */
class Boldermail_Shortcode_Gallery {

	/**
	 * Builds the Gallery shortcode output.
	 *
	 * @since 2.4.0
	 * @param array $attr Shortcodes attributes.
	 * @return string
	 */
	public static function do_shortcode( $attr ) {

		$post = get_post();

		static $instance = 0;
		$instance++;

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$atts  = shortcode_atts(
			array(
				'order'   => 'ASC',
				'orderby' => 'menu_order ID',
				'id'      => $post ? $post->ID : 0,
				'size'    => 'thumbnail',
				'include' => '',
				'exclude' => '',
				'link'    => '',
			),
			$attr,
			'gallery'
		);

		$id = (int) $atts['id'];

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts(
				array(
					'include'        => $atts['include'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby'],
				)
			);

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children(
				array(
					'post_parent'    => $id,
					'exclude'        => $atts['exclude'],
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby'],
				)
			);
		} else {
			$attachments = get_children(
				array(
					'post_parent'    => $id,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => $atts['order'],
					'orderby'        => $atts['orderby'],
				)
			);
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		// From `is_feed` section in `wp-includes/media.php`.
		$output = "\n";

		foreach ( $attachments as $att_id => $attachment ) {
			if ( ! empty( $atts['link'] ) ) {
				if ( 'none' === $atts['link'] ) {
					$output .= wp_get_attachment_image( $att_id, $atts['size'], false, $attr );
				} else {
					$output .= wp_get_attachment_link( $att_id, $atts['size'], false );
				}
			} else {
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true );
			}
			$output .= "\n";
		}

		return $output;

	}

}
