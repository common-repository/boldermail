<?php
/**
 * List class.
 *
 * The Boldermail List class.
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
 * Boldermail_List class.
 *
 * @since   1.0.0
 */
class Boldermail_List extends Boldermail_Post {

	/**
	 * Get the list ID for the Boldermail server.
	 *
	 * @since   1.0.0
	 * @return  string  List ID.
	 */
	public function get_list_id() {
		return $this->get_meta( 'id' );
	}

	/**
	 * Get the list name.
	 *
	 * @since   1.0.0
	 * @return  string  List name.
	 */
	public function get_name() {
		return get_the_title( $this->ID );
	}

	/**
	 * Get the "From Name" email field.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_from_name() {
		return $this->get_meta( 'from_name' );
	}

	/**
	 * Get the "From Email" email address.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_from_email() {
		return $this->get_meta( 'from_email' );
	}

	/**
	 * Get the "Reply-To" email address.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_reply_to() {
		return $this->get_meta( 'reply_to' );
	}

	/**
	 * Get the company name.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_company_name() {
		return $this->get_meta( 'company_name' );
	}

	/**
	 * Get the company address.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_company_address() {
		return $this->get_meta( 'company_address' );
	}

	/**
	 * Get the permission reminder.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_permission() {
		return $this->get_meta( 'permission' );
	}

	/**
	 * Get opt-in type (single opt-in or double opt-in).
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_opt_in_type() {
		return $this->get_meta( 'opt_in_type' );
	}

	/**
	 * Where to redirect users after subscription.
	 *
	 * @since   1.0.0
	 * @return  int     Post ID.
	 */
	public function get_subscribe_page() {
		return absint( $this->get_meta( 'subscribe_page' ) );
	}

	/**
	 * Where to redirect users after subscription.
	 *
	 * @since   1.0.0
	 * @return  int     Post ID.
	 */
	public function get_already_subscribed_page() {
		return absint( $this->get_meta( 'already_subscribed_page' ) );
	}

	/**
	 * Where to redirect users after confirmation (for double opt-in's).
	 *
	 * @since   1.0.0
	 * @return  int     Post ID.
	 */
	public function get_confirmation_page() {
		return absint( $this->get_meta( 'confirmation_page' ) );
	}

	/**
	 * Send a "Thank You" email for subscribing?
	 *
	 * @since   1.0.0
	 * @return  bool
	 */
	public function send_thank_you_email() {
		return absint( $this->get_meta( 'send_thank_you_email' ) );
	}

	/**
	 * Get the subject line for the subscription email.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_thank_you_email_subject() {
		return $this->get_meta( 'thank_you_email_subject' );
	}

	/**
	 * Get the content for the subscription email.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_thank_you_email_content() {
		return html_entity_decode( esc_html( $this->get_meta( 'thank_you_email_content' ) ) );
	}

	/**
	 * Get the content for the subscription email with shortcodes applied.
	 *
	 * @since   1.0.0
	 * @param   string  $filter   Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return  string
	 */
	public function get_filtered_thank_you_email_content( $filter = 'display' ) {
		return $this->get_filtered_meta( 'thank_you_email_content', 'inline-css', $filter );
	}

	/**
	 * Get the subject line for the confirmation email (for double opt-in's).
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_confirmation_email_subject() {
		return $this->get_meta( 'confirmation_email_subject' );
	}

	/**
	 * Get the content for the confirmation email (for double opt-in's).
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_confirmation_email_content() {
		return html_entity_decode( esc_html( $this->get_meta( 'confirmation_email_content' ) ) );
	}

	/**
	 * Get the content for the confirmation email with shortcodes applied.
	 *
	 * @since   1.0.0
	 * @param   string  $filter   Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return  string
	 */
	public function get_filtered_confirmation_email_content( $filter = 'display' ) {
		return $this->get_filtered_meta( 'confirmation_email_content', 'inline-css', $filter );
	}

	/**
	 * Get opt-out type (single opt-out or double opt-out).
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_opt_out_type() {
		return $this->get_meta( 'opt_out_type' );
	}

	/**
	 * Where to redirect users after unsubscription.
	 *
	 * @since   1.0.0
	 * @return  int     Post ID.
	 */
	public function get_unsubscribe_page() {
		return absint( $this->get_meta( 'unsubscribe_page' ) );
	}

	/**
	 * Send a "Goodbye" email for unsubscribing?
	 *
	 * @since   1.0.0
	 * @return  bool
	 */
	public function send_unsubscribe_email() {
		return absint( $this->get_meta( 'send_unsubscribe_email' ) );
	}

	/**
	 * Get the subject line for the unsubscription email.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_unsubscribe_email_subject() {
		return $this->get_meta( 'unsubscribe_email_subject' );
	}

	/**
	 * Get the content for the unsubscription email.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_unsubscribe_email_content() {
		return html_entity_decode( esc_html( $this->get_meta( 'unsubscribe_email_content' ) ) );
	}

	/**
	 * Get the content for the unsubscription email with shortcodes applied.
	 *
	 * @since   1.0.0
	 * @param   string  $filter   Type of filter to apply. Accepts 'raw', 'preview', 'display', 'api'.
	 * @return  string
	 */
	public function get_filtered_unsubscribe_email_content( $filter = 'display' ) {
		return $this->get_filtered_meta( 'unsubscribe_email_content', 'inline-css', $filter );
	}

	/**
	 * When a user is unsubscribed from this list, unsubscribe the user from all lists?
	 *
	 * @since   1.0.0
	 * @return  bool
	 */
	public function do_unsubscribe_all_lists() {
		return absint( $this->get_meta( 'unsubscribe_all_lists' ) );
	}

	/**
	 * Is GDPR enabled for this list?
	 *
	 * @since   1.0.0
	 * @return  bool
	 */
	public function is_gdpr_enabled() {
		return absint( $this->get_meta( 'gdpr_enabled' ) );
	}

	/**
	 * Get the number of autoresponders for this list.
	 *
	 * @since   1.4.0
	 * @return  int
	 */
	public function get_autoresponders_count() {

		global $wpdb;

		$autoresponder_posts = get_posts( array(
			'posts_per_page' => -1,
			'post_type'      => 'bm_autoresponder',
			'post_parent'    => $this->get_post_id(),
			'post_status'    => array( 'draft', 'publish' ),
			'orderby'        => 'none',
		) );

		if ( $wpdb->last_error ) {
			return 0;
		}

		return count( $autoresponder_posts );

	}

	/**
	 * Get number of contacts with a specific status.
	 * This function gets called after `update_counts`, which gets called
	 * through the `the_posts` filter.
	 *
	 * Prior to version 1.3.0, we kept track of how many subscribers with a
	 * status exist in the List with meta data.
	 *
	 * We did this to avoid having to query the database.
	 *
	 * ```
	 * new WP_Query( array(
	 *   'posts_per_page' => -1,
	 *   'post_type'      => 'bm_subscriber',
	 *   'post_status'    => 'publish',
	 *   'meta_query'     => array(
	 *     'relation' => 'AND',
	 *       array(
	 *         'key'     => '_status',
	 *         'value'   => $status,
	 *         'compare' => '=='
	 *       ),
	 *       array(
	 *         'key'     => '_list',
	 *         'value'   => $this->get_list_id(),
	 *         'compare' => '=='
	 *       ),
	 *   )
	 * ) );
	 * ```
	 *
	 * For example, with our list at 75,000 subscribers, the operation timed out
	 * before we were able to get the number of subscribers.
	 *
	 * As of version 1.3.0, we use `post_parent` to track the post ID of the
	 * list the subscriber belongs to, and `post_status` to track the subscription
	 * status. This significantly reduces the computational cost.
	 *
	 * We only store the count as meta data to sort the columns in the admin list.
	 *
	 * @since   1.0.0
	 * @param   string|array    $status   Subscription status.
	 * @return  int
	 */
	public function get_subscribers_count( $status ) : int {
		return absint( $this->get_meta( $status ) );
	}

	/**
	 * Update the number of subscribers in this List.
	 * This function gets called every single time a List is queried through
	 * `WP_Query`.
	 *
	 * @since   2.0.0
	 * @param   string  $new_status
	 * @param   string  $old_status
	 * @return  void
	 */
	public function update_counts() {

		global $wpdb;

		$statuses_count = array(
			'publish' => 0,
			'subscribed' => 0,
			'unconfirmed' => 0,
			'unsubscribed' => 0,
			'bounced' => 0,
			'complained' => 0,
		);

		$subscriber_posts = get_posts( array(
			'posts_per_page' => -1,
			'post_type'      => 'bm_subscriber',
			'post_parent'    => $this->get_post_id(),
			'post_status'    => array_keys( $statuses_count ),
			'orderby'        => 'none',  // 95% faster than using `ORDER BY wp_posts.post_date DESC`
		) );

		if ( $wpdb->last_error ) {
			return;
		}

		foreach ( $subscriber_posts as $subscriber_post ) {

			if ( $subscriber_post->post_status === 'publish' ) {
				++$statuses_count['publish'];
			}

			if ( $subscriber_post->post_status === 'subscribed' ) {
				++$statuses_count['subscribed'];
			}

			if ( $subscriber_post->post_status === 'unconfirmed' ) {
				++$statuses_count['unconfirmed'];
			}

			if ( $subscriber_post->post_status === 'unsubscribed' ) {
				++$statuses_count['unsubscribed'];
			}

			if ( $subscriber_post->post_status === 'bounced' ) {
				++$statuses_count['bounced'];
			}

			if ( $subscriber_post->post_status === 'complained' ) {
				++$statuses_count['complained'];
			}

		}

		$statuses_count['all'] = array_sum( $statuses_count );

		foreach ( $statuses_count as $status => $count ) {
			$this->set_subscribers_count( $status, $count );
		}

		wp_reset_postdata();

	}

	/**
	 * Set number of contacts with a specific status.
	 *
	 * @since   1.0.0
	 * @param   string  $status   Subscription status.
	 * @return  int
	 */
	public function set_subscribers_count( $status, $value ) {
		$this->set_meta( $status, $value );
	}

	/**
	 * Get the timestamp for the last subscriber update.
	 *
	 * @since   1.0.0
	 * @return  int
	 */
	public function get_timestamp() : int {
		return absint( $this->get_meta( 'timestamp' ) );
	}

	/**
	 * Get the emails to exclude from this timestamp query.
	 *
	 * @since   1.0.1
	 * @return  array
	 */
	public function get_timestamp__not_email() : array {
		return (array) $this->get_meta( 'timestamp__not_email' );
	}

	/**
	 * Get list of emails that were not properly updated.
	 *
	 * @since   1.0.1
	 * @return  array
	 */
	public function get_update_errors() : array {
		return (array) $this->get_meta( 'update_errors' );
	}

	/**
	 * Core fields in Sendy.
	 *
	 * @since   1.6.0
	 * @return  array
	 */
	public static function get_core_fields() {

		// we need this specific order for the display in import form
		// @see html-boldermail-list-bulk-subscriber-actions.php
		return array(
			array(
				'name' => 'Name',
				'label' => esc_html__( 'Name', 'boldermail' ),
				'type' => 'Text',
				'required' => false,
				'shortcode' => '[boldermail_name fallback=""]',
			),
			array(
				'name' => 'Email',
				'label' => esc_html__( 'Email', 'boldermail' ),
				'type' => 'Text',
				'required' => true,
				'shortcode' => '[boldermail_email]',
			),
		);

	}

	/**
	 * Default fields in Boldermail.
	 *
	 * @since   1.6.0
	 * @return  array
	 */
	public static function get_default_fields() {

		return array(
			array(
				'name' => 'Last Name',
				'label' => esc_html__( 'Last Name', 'boldermail' ),
				'type' => 'Text',
				'required' => false,
				'shortcode' => '[boldermail_last_name fallback=""]',
				'tip' => esc_html__( "Enter your subscriber's last name. Use the <code>[boldermail_last_name]</code> shortcode to use it in a newsletter.", 'boldermail' ),
			),
			array(
				'name' => 'Company',
				'label' => esc_html__( 'Company', 'boldermail' ),
				'type' => 'Text',
				'required' => false,
				'shortcode' => '[boldermail_company fallback=""]',
				'tip' => esc_html__( "Enter the name of your subscriber's company (if applicable). Use the <code>[boldermail_company]</code> shortcode to use it in a newsletter.", 'boldermail' ),
			),
			array(
				'name' => 'City',
				'label' => esc_html__( 'City', 'boldermail' ),
				'type' => 'Text',
				'required' => false,
				'shortcode' => '[boldermail_city fallback=""]',
				'tip' => esc_html__( "Enter the city where your subscriber resides. Use the <code>[boldermail_city]</code> shortcode to use it in a newsletter.", 'boldermail' ),
			),
			array(
				'name' => 'State',
				'label' => esc_html__( 'State', 'boldermail' ),
				'type' => 'Text',
				'required' => false,
				'shortcode' => '[boldermail_state fallback=""]',
				'tip' => esc_html__( "Enter the state or province where your subscriber resides. Use the <code>[boldermail_state]</code> shortcode to use it in a newsletter.", 'boldermail' ),
			),
			array(
				'name' => 'Zip Code',
				'label' => esc_html__( 'Zip Code', 'boldermail' ),
				'type' => 'Text',
				'required' => false,
				'shortcode' => '[boldermail_zip_code fallback=""]',
				'tip' => esc_html__( "Enter the zip code/postcode where your subscriber resides. Use the <code>[boldermail_zip_code]</code> shortcode to use it in a newsletter.", 'boldermail' ),
			),
			array(
				'name' => 'Country',
				'label' => esc_html__( 'Country', 'boldermail' ),
				'type' => 'Text',
				'required' => false,
				'shortcode' => '[boldermail_country fallback=""]',
				'tip' => esc_html__( "Enter your subscriber's country of residence. Use the <code>[boldermail_country]</code> shortcode to use it in a newsletter.", 'boldermail' ),
			),
			array(
				'name' => 'Phone',
				'label' => esc_html__( 'Phone', 'boldermail' ),
				'type' => 'Text',
				'required' => false,
				'shortcode' => '[boldermail_phone fallback=""]',
				'tip' => esc_html__( "Enter your subscriber's phone number. Use the <code>[boldermail_phone]</code> shortcode to use it in a newsletter.", 'boldermail' ),
			),
		);

	}

	/**
	 * Get the raw custom fields data.
	 *
	 * @since   1.6.0
	 * @return  array
	 */
	public function get_custom_fields() {

		$custom_fields = $this->get_meta( 'custom_fields' );

		return is_array( $custom_fields ) ? $custom_fields : array();

	}

}
