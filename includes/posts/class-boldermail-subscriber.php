<?php
/**
 * Subscriber class.
 *
 * The Boldermail Subscriber class.
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
 * Boldermail_Subscriber class.
 *
 * @since   1.0.0
 */
class Boldermail_Subscriber extends Boldermail_Post {

	/**
	 * Save sanitized subscriber data.
	 *
	 * @since   1.2.5
	 * @param   array   $subscriber_data  Array of subscriber data (should be sanitized).
	 * @return  void
	 */
	public function save( $subscriber_data ) {

		$name = $subscriber_data['name'];
		$last_name = $subscriber_data['custom_fields']['Last Name'];
		$email = $subscriber_data['email'];
		$list_post_id = $subscriber_data['list_post_id'];
		$status = $subscriber_data['status'];

		// save all data data, except `status`
		$this->save_meta( array(
			'name' => $name,
			'custom_fields' => $subscriber_data['custom_fields'],
			'last_campaign' => $subscriber_data['last_campaign'],
			'last_ares' => $subscriber_data['last_ares'],
			'last_activity' => $subscriber_data['timestamp'],
			'join_date' => $subscriber_data['join_date'],
			'ip' => $subscriber_data['ip'],
			'country' => $subscriber_data['country'],
			'referer' => $subscriber_data['referrer'],
			'optin_method' => $subscriber_data['method'],
			'added_via' => $subscriber_data['added_via'],
			'gdpr' => $subscriber_data['gdpr'],
		) );

		$this->wpdb_update( array(
			'post_title' => $email,
			'post_parent' => $list_post_id,
			'post_content' => $name . ' ' . $last_name . ' (' . $email . ')',
			'post_status' => $status,
		) );

	}

	/**
	 * Copy data from another Subscriber.
	 *
	 * @since   1.0.0
	 * @param   mixed   $subscriber   Subscriber object or post ID of the subscriber.
	 * @return  void
	 */
	public function copy( $subscriber ) {

		$subscriber = boldermail_get_subscriber( $subscriber );

		if ( ! $subscriber ) {
			return;
		}

		/**
		 * Save all meta, except for `country`, `gdpr`, and `skip_opt_in_confirm`.
		 * Skip confirm is no longer necessary, as the contact is already
		 * in the database, so we should keep the original data. GDPR and
		 * country are not updated on Boldermail through the API.
		 *
		 * @see     Boldermail_Meta_Box_Subscriber_Data::save
		 * @since   1.0.0
		 */
		$name = boldermail_sanitize_text( $subscriber->get_name() );
		$last_name = boldermail_sanitize_text( $subscriber->get_last_name() );
		$custom_fields = array_map( 'boldermail_sanitize_text', $subscriber->get_custom_fields() );
		$email = boldermail_sanitize_email( $subscriber->get_email() );
		$list_post_id = boldermail_sanitize_int( $subscriber->get_list_post_id() );
		$status = boldermail_sanitize_text( $subscriber->get_status() );

		$this->save_meta( array(
			'name' => $name,
			'custom_fields' => $custom_fields,
		) );

		$this->wpdb_update( array(
			'post_title' => $email,
			'post_parent' => $list_post_id,
			'post_content' => $name . ' ' . $last_name . ' (' . $email . ')',
			'post_status' => $status,
		) );

	}

	/**
	 * Get the raw custom fields.
	 *
	 * @since   1.0.0
	 * @param   string  $filter   Optional. Type of filter to apply. Accepts 'raw', 'api'. Default 'raw'.
	 * @return  array
	 */
	public function get_custom_fields( $filter = 'raw' ) {

		$raw_custom_fields = (array) $this->get_meta( 'custom_fields' );

		if ( $filter === 'raw' ) {
			return $raw_custom_fields;
		}

		if ( $filter === 'api' ) {

			$custom_fields = array();

			foreach ( $raw_custom_fields as $key => $value ) {

				$key = boldermail_custom_field_to_tag( $key );

				/**
				 * Even if the value of the custom field is empty, send the empty
				 * value with the API request. Otherwise, it prevents user from
				 * deleting fields through the WordPress interface.
				 *
				 * However, do note that as of Sendy version 4.0.4.1, empty fields
				 * cause conflicts with date fields (i.e. a value of 0 = 01/01/1970).
				 *
				 * @since   1.5.0
				 */
				$custom_fields[ $key ] = boldermail_sanitize_text( $value );

			}

			return $custom_fields;

		}

		return array();

	}

	/**
	 * Get the raw custom field.
	 *
	 * @since   1.0.0
	 * @param   string  $name   Custom field name.
	 * @return  string
	 */
	public function get_custom_field( $name ) {

		$custom_fields = $this->get_custom_fields();

		return isset( $custom_fields[ $name ] ) ? $custom_fields[ $name ] : '';

	}

	/**
	 * Get the name.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_name() {

		return $this->get_meta( 'name' );

	}

	/**
	 * Get the last name.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_last_name() {

		return $this->get_custom_field( "Last Name" );

	}

	/**
	 * Get the email address.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_email() {

		$post = get_post( $this->get_post_id() );

		// @see wp-admin/edit-form-advanced.php
		if ( 'auto-draft' == $post->post_status ) {
			$post->post_title = '';
		}

		return $post->post_title;

	}

	/**
	 * Get the list post.
	 *
	 * @since   1.6.0
	 * @return  Boldermail_List|null
	 */
	public function get_list() {

		return boldermail_get_list( wp_get_post_parent_id( $this->get_post_id() ) );

	}

	/**
	 * Get the list ID for the Boldermail server.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_list_id() {

		$list_post_id = absint( $this->get_list_post_id() );

		if ( $list_post_id && $list = boldermail_get_list( $list_post_id ) ) {
			return $list->get_list_id();
		}

		return '';

	}

	/**
	 * Get the list post ID.
	 *
	 * @since   1.0.0
	 * @return  int
	 */
	public function get_list_post_id() {

		return wp_get_post_parent_id( $this->get_post_id() );

	}

	/**
	 * Get the country.
	 *
	 * @see     https://datahub.io/core/country-list#resource-data  Uses 2-letter country code.
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_country() {

		return $this->get_meta( 'country' );

	}

	/**
	 * Get the IP address.
	 *
	 * @since   1.0.0
	 * @return  string
	 */
	public function get_ip_address() {

		return $this->get_meta( 'ip' );

	}

	/**
	 * Send user confirmation email if list is double opt-in, or bypass and
	 * sign up user as single opt-in.
	 *
	 * @since   1.0.0
	 * @return  bool
	 */
	public function skip_opt_in_confirm() {

		return absint( $this->get_meta( 'skip_opt_in_confirm' ) ) ? true : false;

	}

	/**
	 * Is this an EU user signed up in a GDPR compliant manner?
	 *
	 * @since   1.0.0
	 * @return  bool
	 */
	public function is_gdpr() {

		return absint( $this->get_meta( 'gdpr' ) ) ? true : false;

	}

	/**
	 * Get last activity.
	 *
	 * @since   1.2.4
	 * @return  int     UNIX timestamp.
	 */
	public function get_last_activity() {

		return absint( $this->get_meta( 'last_activity' ) );

	}

	/**
	 * Check the post status and see if it was published already.
	 *
	 * {@inheritDoc}
	 * @see     Boldermail_Post::is_published()
	 * @since   1.7.0
	 * @return  bool
	 */
	public function is_published() {

		$subscriber_statuses = array( 'publish', 'subscribed', 'unconfirmed', 'unsubscribed', 'bounced', 'complained' );

		return in_array( $this->get_status(), $subscriber_statuses );

	}

}
