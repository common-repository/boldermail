<?php
/**
 * Block Template class.
 *
 * The Boldermail block template class.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-post.php';

/**
 * Boldermail_Block_Template class.
 *
 * @since 2.0.0
 */
class Boldermail_Block_Template extends Boldermail_Post {

	/**
	 * Get the post content.
	 *
	 * @since 2.0.0
	 */
	public function get_content() {

		$post = get_post( $this->get_post_id() );

		/**
		 * The function `do_blocks` parses dynamic blocks out of `post_content`
		 * and re-renders them. As of version Boldermail 2.0.0, the only dynamic
		 * block is `core/block` which is used to enable reusable blocks.
		 *
		 * The `core/block` has its own row in the `wp_posts` table, and the
		 * `bm_block_template` post only saves `<!-- wp:block {"ref":3708} /-->`
		 * in `post_content`. This functions return the content of the post
		 * ID 3708, for example.
		 *
		 * @since 2.0.0
		 */
		return do_blocks( $post->post_content );

	}

	/**
	 * Get the HTML content of the template.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_html() {

		// Save parent post data (Newsletter calls this function).
		global $post;
		$newsletter_post = $post;

		// Get block template post object.
		$block_template_post = get_post( $this->get_post_id() );

		// Setup block template post data.
		boldermail_setup_postdata( $block_template_post );

		// Get template HTML.
		ob_start();
		include BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-block-template.php';
		$html = str_replace( array( "\n", "\r" ), '', ob_get_clean() );

		// Remove all shortcodes.
		global $shortcode_tags;
		$orig_shortcode_tags = $shortcode_tags;
		remove_all_shortcodes();

		// Apply Boldermail shortcodes to template HTML.
		$html = boldermail()->shortcodes->apply_shortcodes( $html, 'raw' );

		// Restore original shortcodes.
		$shortcode_tags = $orig_shortcode_tags; /* phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited */

		// Restore original newsletter post data (Newsletter calls this function).
		boldermail_setup_postdata( $newsletter_post );

		return $html;

	}

	/**
	 * Get the HTML content of the newsletter with shortcodes applied and CSS inlined.
	 *
	 * @since  1.0.0
	 * @param  string $filter Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return string
	 */
	public function get_filtered_html( $filter = 'display' ) {

		$template_post   = get_post( $this->get_post_id() );
		$newsletter_post = get_post( $template_post->post_parent );

		$newsletter = boldermail_get_newsletter( $newsletter_post );

		if ( ! $newsletter ) {
			return '';
		}

		// Setup block template post data.
		boldermail_setup_postdata( $newsletter_post );

		$html = $newsletter->get_filtered_html( $filter );

		// Restore original newsletter post data (Newsletter calls this function).
		boldermail_setup_postdata( $template_post );

		return $html;

	}

	/**
	 * Get template style.
	 *
	 * @since  2.0.0
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

}
