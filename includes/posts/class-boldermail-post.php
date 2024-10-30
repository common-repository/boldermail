<?php
/**
 * Post class.
 *
 * The Boldermail Post class.
 *
 * @link       https://www.boldermail.com/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post class.
 *
 * @since   1.0.0
 */
class Boldermail_Post {

	/**
	 * Post ID.
	 *
	 * @since   1.0.0
	 * @var     int $ID   The post ID.
	 */
	protected $ID;

	/**
	 * Constructor.
	 *
	 * @since   1.0.0
	 * @param   string $post_id  The post ID.
	 */
	public function __construct( $post_id ) {
		$this->ID = $post_id;
	}

	/**
	 * Returns the unique ID for this object.
	 *
	 * @since   1.0.0
	 * @return  int
	 */
	public function get_post_id() : int {
		return $this->ID;
	}

	/**
	 * Save the meta data for this post type.
	 *
	 * @since   1.0.0
	 * @param   array $meta   Array meta data.
	 * @return  void
	 */
	public function save_meta( $meta ) {

		foreach ( $meta as $key => $value ) {
			$this->set_meta( $key, $value );
		}

	}

	/**
	 * Update a row in the table:
	 *
	 *   Boldermail_Post::wpdb_update( array( 'column' => 'foo', 'field' => 'bar' ) )
	 *   Boldermail_Post::wpdb_update( array( 'column' => 'foo', 'field' => 1337 ) )
	 *
	 * Works the same as if calling $wpdb::update, but we also clean the post cache
	 * afterwards.
	 *
	 * @since   1.3.0
	 * @see     wpdb::prepare()
	 * @see     wpdb::$field_types
	 * @see     wp_set_wpdb_vars()
	 * @param   array $data         Data to update (in column => value pairs).
	 *                              Both $data columns and $data values should be "raw" (neither should be SQL escaped).
	 *                              Sending a null value will cause the column to be set to NULL - the corresponding
	 *                              format is ignored in this case.
	 * @return  int|false           The number of rows updated, or false on error.
	 */
	public function wpdb_update( $data ) {

		global $wpdb;

		$return = 0;

		if ( $data ) {
			$return = $wpdb->update( $wpdb->posts, $data, array( 'ID' => $this->get_post_id() ) ); /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery */
			clean_post_cache( $this->get_post_id() );
		}

		return $return;

	}

	/**
	 * Save the meta data for this post type.
	 *
	 * It is important to use `add_post_meta` first, and then
	 * `update_post_meta`. This function gets called during the
	 * `transition_post_status` hook, and the meta data was not saving
	 * when only `update_post_meta` was being called.
	 *
	 * @since   1.0.0
	 * @param   string $prop    Property key.
	 * @param   mixed  $value   Property value.
	 * @return  mixed
	 */
	public function set_meta( $prop, $value ) {

		// Add a new custom field if the key does not already exist.
		$meta_id = add_post_meta( $this->get_post_id(), "_{$prop}", $value, true );

		// Or update the value of the custom field with that key otherwise.
		if ( ! $meta_id ) {
			return update_post_meta( $this->get_post_id(), "_{$prop}", $value );
		}

		return $meta_id;

	}

	/**
	 * Returns the meta data for this post type.
	 *
	 * @since   1.0.0
	 * @param   string $prop  Property key.
	 * @return  mixed
	 */
	public function get_meta( $prop ) {

		return get_post_meta( $this->get_post_id(), "_{$prop}", true );

	}

	/**
	 * Delete post meta.
	 *
	 * @since   1.0.0
	 * @param   string $prop  Property key.
	 * @return  mixed
	 */
	public function delete_meta( $prop ) {

		return delete_post_meta( $this->get_post_id(), "_{$prop}" );

	}

	/**
	 * Get children post IDs.
	 *
	 * @since   1.4.0
	 * @return  array
	 */
	public function get_children() {

		global $wpdb;

		$children_posts = get_transient( "boldermail_post_children_{$this->get_post_id()}" );

		if ( $children_posts ) {
			return $children_posts;
		}

		// Do not use 'post_type' => 'any'.
		// 'any' retrieves any type except revisions and types with 'exclude_from_search' set to true,
		// it will exclude newsletters.
		// @see https://wordpress.stackexchange.com/a/275468.
		$children_posts = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type'      => array( 'bm_newsletter', 'bm_newsletter_ares' ),
				'post_parent'    => $this->get_post_id(),
				'post_status'    => array( 'publish', 'enabled', 'paused', 'preparing', 'sending', 'sent', 'subscribed', 'unconfirmed', 'unsubscribed', 'bounced', 'complained' ),
				'orderby'        => 'none',  // 95% faster than using `ORDER BY wp_posts.post_date DESC`
				'fields'         => 'ids',
			)
		);

		if ( $wpdb->last_error ) {
			return array();
		}

		set_transient( "boldermail_post_children_{$this->get_post_id()}", $children_posts, 300 );

		return $children_posts;

	}

	/**
	 * Get the HTML data with shortcodes and filters applied.
	 *
	 * @since   1.0.0
	 * @param   string $meta_key  Property key.
	 * @param   string $output    Output. Accepts 'raw', 'inline-css', 'utf-8'.
	 *                            'utf-8' is only used for subject lines.
	 *                            As of version 2.1.4, 'utf-8' does nothing because the new PHPMailer
	 *                            in WordPress 5.5, and Sendy as of version 2.0.8 already encode
	 *                            the subject line as UTF-8. No need to do it again, or else it causes errors.
	 *                            @see https://github.com/PHPMailer/PHPMailer/issues/2115.
	 * @param   string $filter    Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return  string
	 */
	public function get_filtered_meta( $meta_key, $output = '', $filter = 'display' ) {

		// Get content.
		$html = trim( call_user_func( array( $this, "get_{$meta_key}" ) ) );

		// Return early if string is empty.
		if ( empty( $html ) ) {
			return '';
		}

		// Extract CSS.
		$css = '';
		if ( 'inline-css' === $output ) {
			$css = boldermail_extract_css( $html );
		}

		// Apply shortcodes.
		$html = boldermail()->shortcodes->apply_video_shortcodes( $html );
		$html = boldermail()->shortcodes->apply_shortcodes( $html, $filter );

		// Inline CSS.
		if ( 'inline-css' === $output ) {
			$html = boldermail_inline_css( $html, $css );
		}

		return $html;

	}

	/**
	 * Get post/newsletter/subscription status.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_status() {

		return get_post_status( $this->get_post_id() );

	}

	/**
	 * Check if post has been published.
	 *
	 * @since   1.3.0
	 * @return  bool
	 */
	public function is_published() {

		return in_array( $this->get_status(), array( 'publish' ), true );

	}

	/**
	 * Use the block editor?
	 *
	 * @since   2.0.0
	 * @return  bool
	 */
	public function use_block_editor() {

		return $this->get_meta( 'use_block_editor' ) ? true : false;

	}

}
