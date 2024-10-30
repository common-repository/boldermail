<?php
/**
 * Template class.
 *
 * The Boldermail template class.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-post.php';

/**
 * Boldermail_Template class.
 *
 * @since 1.0.0
 */
class Boldermail_Template extends Boldermail_Post {

	/**
	 * Get the post content.
	 * No need to `do_blocks` because these will be used in newsletters.
	 *
	 * @since 2.2.0
	 */
	public function get_content() {

		$post = get_post( $this->get_post_id() );

		return $post->post_content;

	}

	/**
	 * Get the HTML content of the template.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_html() {

		if ( $this->use_block_editor() ) {

			// Check for empty block comments in case there was an error saving (i.e. `<!-- boldermail:template /-->`).
			// This simple comment regex should work if there is a single comment and is only used to check for an error.
			$template_content = preg_replace( '/<!--(.*?)-->/', '', $this->get_content() );

			/**
			 * The function `do_blocks` parses dynamic blocks out of `post_content`
			 * and re-renders them. As of version Boldermail 2.0.0, the only dynamic
			 * block is `core/block` which is used to enable reusable blocks.
			 *
			 * The `core/block` has its own row in the `wp_posts` table, and the
			 * `bm_template` post only saves `<!-- wp:block {"ref":3708} /-->`
			 * in `post_content`. This functions return the content of the post
			 * ID 3708, for example.
			 *
			 * @since 2.0.0
			 */
			$template_content = do_blocks( $template_content );

			// Return empty HTML template if block editor is empty.
			if ( ! $template_content ) {
				return '';
			}

			// Get template HTML.
			ob_start();
			include BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-block-template.php';
			$html = str_replace( array( "\n", "\r" ), '', ob_get_clean() );

			// Apply Boldermail shortcodes to template HTML.
			$html = boldermail()->shortcodes->apply_shortcodes( $html, 'raw' );

			return str_replace( array( "\n", "\r" ), '', $html );

		}

		return html_entity_decode( esc_html( $this->get_meta( 'html' ) ) );

	}

	/**
	 * Get the HTML content of the template with shortcodes applied and CSS inlined.
	 *
	 * @since  1.0.0
	 * @param  string $filter Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return string
	 */
	public function get_filtered_html( $filter = 'raw' ) {

		return $this->get_filtered_meta( 'html', 'inline-css', $filter );

	}

	/**
	 * Get template style.
	 *
	 * @since  2.2.0
	 * @return string Template style meta data.
	 */
	public function get_template_style() {

		return $this->get_meta( 'template_style' );

	}

	/**
	 * Get template preheader style.
	 *
	 * @since  2.2.0
	 * @return string Template preheader style meta data.
	 */
	public function get_preheader_style() {

		return $this->get_meta( 'preheader_style' );

	}

	/**
	 * Get template header style.
	 *
	 * @since  2.2.0
	 * @return string Template header style meta data.
	 */
	public function get_header_style() {

		return $this->get_meta( 'header_style' );

	}

	/**
	 * Get template body style.
	 *
	 * @since  2.2.0
	 * @return string Template body style meta data.
	 */
	public function get_body_style() {

		return $this->get_meta( 'body_style' );

	}

	/**
	 * Get template footer style.
	 *
	 * @since  2.2.0
	 * @return string Template footer style meta data.
	 */
	public function get_footer_style() {

		return $this->get_meta( 'footer_style' );

	}

	/**
	 * Get the WP Block post ID.
	 *
	 * @since  2.2.0
	 * @return int WP Block post ID.
	 */
	public function get_wp_block_post_id() {

		return absint( $this->get_meta( 'wp_block_post_id' ) );

	}

	/**
	 * Get WP Block post object.
	 *
	 * @since  2.2.0
	 * @return WP_Post|null Post object on success, null on failure.
	 */
	public function get_wp_block_post() {

		return get_post( $this->get_wp_block_post_id() );

	}

	/**
	 * Use the block editor?
	 *
	 * @since  2.2.0
	 * @return bool
	 */
	public function use_block_editor() {

		return $this->get_meta( 'use_block_editor' ) ? true : false;

	}

}
