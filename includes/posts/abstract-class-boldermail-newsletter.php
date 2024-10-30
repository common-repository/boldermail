<?php /* phpcs:ignore WordPress.Files.FileName.InvalidClassFileName */
/**
 * Newsletter base class.
 *
 * The Boldermail newsletter base class.
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
 * Boldermail_Newsletter base class.
 *
 * @since 1.0.0
 */
abstract class Boldermail_Newsletter extends Boldermail_Post {

	/**
	 * Newsletter type.
	 *
	 * @since 1.0.0
	 * @var   string $type The newsletter type.
	 */
	protected $type;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param int    $post_id         The post ID.
	 * @param string $newsletter_type The newsletter type.
	 */
	public function __construct( $post_id, $newsletter_type ) {

		parent::__construct( $post_id );

		$this->type = $newsletter_type;

	}

	/**
	 * Are input fields read-only? Prevent editing if newsletter is published.
	 *
	 * @since  1.0.0
	 * @return bool
	 */
	abstract public function is_editable();

	/**
	 * Is the automated email enabled?
	 *
	 * @since  1.4.0
	 * @return bool
	 */
	public function is_enabled() {

		return in_array( $this->get_status(), array( 'publish', 'enabled' ), true );

	}

	/**
	 * Is the automated email paused?
	 *
	 * @since  1.4.0
	 * @return bool
	 */
	public function is_paused() {

		return $this->get_status() === 'paused';

	}

	/**
	 * Get the newsletter type.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_type() {

		return $this->type;

	}

	/**
	 * Get the subject of the newsletter with shortcodes applied.
	 *
	 * @since  1.0.0
	 * @param  string $output Output. Accepts 'raw', 'inline-css', 'utf-8'.
	 * @param  string $filter Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return string
	 */
	public function get_filtered_subject( $output = 'raw', $filter = 'display' ) {

		return $this->get_filtered_meta( 'subject', $output, $filter );

	}

	/**
	 * Get the HTML content of the newsletter with shortcodes applied and CSS inlined.
	 *
	 * @since  1.0.0
	 * @param  string $filter Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return string
	 */
	public function get_filtered_html( $filter = 'display' ) {

		return $this->get_filtered_meta( 'html', 'inline-css', $filter );

	}

	/**
	 * Get the plain text content of the newsletter with shortcodes applied.
	 *
	 * @since  1.0.0
	 * @param  string $filter Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return string
	 */
	public function get_filtered_plain_text( $filter = 'display' ) {

		$html = $this->get_filtered_html( $filter );

		return boldermail_html2text( $html );

	}

	/**
	 * Get list ID(s).
	 *
	 * @since  1.0.0
	 * @return array
	 */
	public function get_list_id() : array {

		$list_id = $this->get_meta( 'list_id' );

		if ( ! $list_id ) {
			return array();
		}

		// RSS Newsletters save list IDs as comma-separated lists.
		// We keep it that way for backward compatibility.
		if ( is_string( $list_id ) ) {
			return explode( ',', $list_id );
		}

		// Regular newsletters save list IDs as arrays.
		if ( is_array( $list_id ) ) {
			return $list_id;
		}

		return array();

	}

	/**
	 * Get the email subject.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_subject() {

		return $this->get_meta( 'subject' );

	}

	/**
	 * Get the email preview snippet.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_preview_text() {

		return $this->get_meta( 'preview_text' );

	}

	/**
	 * Get the "From Name" email field.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_from_name() {

		return $this->get_meta( 'from_name' );

	}

	/**
	 * Get the "From Email" email address.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_from_email() {

		return $this->get_meta( 'from_email' );

	}

	/**
	 * Get the "Reply-To" email address.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_reply_to() {

		return $this->get_meta( 'reply_to' );

	}

	/**
	 * Get the company name.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_company_name() {

		return $this->get_meta( 'company_name' );

	}

	/**
	 * Get the company address.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_company_address() {

		return $this->get_meta( 'company_address' );

	}

	/**
	 * Get the permission reminder.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_permission() {

		return $this->get_meta( 'permission' );

	}

	/**
	 * Get the HTML design of the email.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_html() {

		if ( $this->use_block_editor() ) {

			$block_template = boldermail_get_block_template( $this->get_block_template_post_id() );

			// Check for empty block comments in case there was an error saving (i.e. `<!-- boldermail:template /-->`).
			// This simple comment regex should work if there is a single comment and is only used to check for an error.
			$block_template_content = preg_replace( '/<!--(.*?)-->/', '', $block_template->get_content() );

			// Return empty HTML template if block editor is empty.
			if ( ! $block_template_content ) {
				return '';
			}

			return str_replace( array( "\n", "\r" ), '', $block_template->get_html() );

		}

		return html_entity_decode( esc_html( $this->get_meta( 'html' ) ) );

	}

	/**
	 * Get the raw links + clicks data.
	 * Only applies to regular and autoresponder newsletters.
	 *
	 * @since  1.4.0
	 * @return array
	 */
	public function get_clicks_data() {

		if ( $this->get_type() === 'rss' ) {
			return array();
		}

		return $this->get_meta( 'links' );

	}

	/**
	 * Get the unsanitized opens data.
	 * Only applies to regular and autoresponder newsletters.
	 *
	 * @since  1.4.0
	 * @return array
	 */
	public function get_opens_data() {

		if ( $this->get_type() === 'rss' ) {
			return array();
		}

		return (array) $this->get_meta( 'opens' );

	}

	/**
	 * Get the number of unique clicks.
	 * Only applies to regular and autoresponder newsletters.
	 *
	 * @since  1.4.0
	 * @return int
	 */
	public function get_unique_clicks() {

		if ( $this->get_type() === 'rss' ) {
			return '';
		}

		$links = $this->get_clicks_data();

		$all_clicks = array();
		foreach ( $links as $link ) {
			$all_clicks = array_merge( $link['clicks'], $all_clicks );
		}

		$clicks_unique = count( array_unique( $all_clicks ) );

		return absint( $clicks_unique );

	}

	/**
	 * Get the number of unique opens.
	 * Only applies to regular and autoresponder newsletters.
	 *
	 * @since  1.4.0
	 * @return int
	 */
	public function get_unique_opens() {

		if ( $this->get_type() === 'rss' ) {
			return '';
		}

		$opens = $this->get_opens_data();

		$opens_unique = count(
			array_unique(
				array_map(
					function ( $i ) {
						return isset( $i['subscriber_id'] ) ? absint( $i['subscriber_id'] ) : 0;
					},
					$opens
				)
			)
		);

		return absint( $opens_unique );

	}

	/**
	 * Get the number of subscribers this campaign was actually sent to.
	 * Only applies to regular and autoresponder newsletters.
	 *
	 * @since  1.4.0
	 * @return int
	 */
	public function get_recipients() {

		if ( $this->get_type() === 'rss' ) {
			return '';
		}

		return absint( $this->get_meta( 'recipients' ) );

	}

	/**
	 * Get the block template post ID.
	 *
	 * @since  2.0.0
	 * @return int
	 */
	public function get_block_template_post_id() {

		return absint( $this->get_meta( 'block_template_post_id' ) );

	}

	/**
	 * Get block template post object.
	 *
	 * @since  2.0.0
	 * @return WP_Post|null Post object on success, null on failure.
	 */
	public function get_block_template_post() {

		return get_post( $this->get_block_template_post_id() );

	}

	/**
	 * Get the edit post link for the block template.
	 *
	 * @since  2.0.0
	 * @param  string $context How to output the '&' character. Default '&'.
	 * @return string          Edit post link.
	 */
	public function get_edit_block_template_link( $context = 'display' ) {

		$post_id = $this->get_block_template_post_id();

		return get_edit_post_link( $post_id, $context );

	}

	/**
	 * Use the block editor?
	 *
	 * Use `get_block_template_post_id` instead of `get_block_template_post`
	 * because `get_block_template_post` seems to use cache and does not
	 * give an accurate response if the `_block_template_post_id` meta is
	 * missing.
	 *
	 * @since  2.0.0
	 * @return bool
	 */
	public function use_block_editor() {

		return $this->get_meta( 'use_block_editor' ) && $this->get_block_template_post_id();

	}

}
