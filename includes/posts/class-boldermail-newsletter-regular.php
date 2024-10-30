<?php
/**
 * Regular newsletter class.
 *
 * The Boldermail newsletter class for Regular newsletters.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/abstract-class-boldermail-newsletter.php';

/**
 * Boldermail_Newsletter_Regular class.
 *
 * @since   1.0.0
 */
class Boldermail_Newsletter_Regular extends Boldermail_Newsletter {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param int $post_id The post ID.
	 */
	public function __construct( $post_id ) {

		parent::__construct( $post_id, 'regular' );

	}

	/**
	 * Save campaign data.
	 *
	 * @since   1.2.5
	 * @param   array   $campaign_data  Array of campaign data (should be sanitized).
	 * @return  void
	 */
	public function save( $campaign_data ) {

		// save all data data, except `status`
		$this->save_meta( array(
			'to_send' => $campaign_data['to_send'],
			'recipients' => $campaign_data['recipients'],
			'opens' => $campaign_data['opens'],
			'links' => $campaign_data['links'],
		) );

		// update the newsletter status first
		$this->set_status();

		// set unique opens and clicks values for sorting in admin list
		$this->set_unique_opens_and_clicks();

	}

	/**
	 * Set the newsletter status.
	 *
	 * @since   1.3.0
	 * @return  void
	 */
	public function set_status() {

		$status = '';

		$to_send = $this->get_to_send();
		$recipients = $this->get_recipients();

		if ( $to_send == $recipients ) {
			$status = 'sent';
		} else if ( $recipients == 0 ) {
			$status = 'preparing';
		} else if ( $recipients > 0 ){
			$status = 'sending';
		}

		$this->wpdb_update( array( 'post_status' => $status ) );

	}

	/**
	 * Set the unique opens and clicks values for sorting the columns
	 * in the admin list.
	 *
	 * Newsletters not in the `preparing`, `sending`, or `sent` post statuses
	 * do not save a meta value. Newsletters in the `preparing` status receive
	 * a value of -2 to trick the query into putting them separately from the
	 * values of the `sending` or `sent` newsletters. If the newsletter
	 * contains no links, then we assign a value of -1 to separate those
	 * newsletters from the ones with links and clicks.
	 *
	 * So our hierarchy grouping for unique opens is:
	 * Sending|Sent > Preparing > Draft
	 *
	 * and for unique clicks is:
	 * Sending|Sent > No links > Preparing > Draft
	 *
	 * @since   1.3.0
	 * @return  void
	 */
	public function set_unique_opens_and_clicks() {

		$status = $this->get_status();

		$unique_opens = ''; $unique_clicks = '';

		if ( in_array( $status, array( 'sending', 'sent' ) ) ) {

			$unique_opens = $this->get_unique_opens();
			$unique_clicks = ( ! empty( $this->get_clicks_data() ) ) ? $this->get_unique_clicks() : -1;

		} else if ( in_array( $status, array( 'preparing' ) ) ) {

			$unique_opens = -2;
			$unique_clicks = -2;

		}

		$this->save_meta( array(
			'unique_opens' => $unique_opens,
			'unique_clicks' => $unique_clicks,
		) );

	}

	/**
	 * Get the campaign ID for the Boldermail server.
	 *
	 * @since   1.0.0
	 * @return  string  Campaign ID.
	 */
	public function get_campaign_id() {

		return absint( $this->get_meta( 'id' ) );

	}

	/**
	 * Get the number of subscribers this campaign should be sent to.
	 *
	 * @since   1.3.0
	 * @return  int
	 */
	public function get_to_send() {

		return absint( $this->get_meta( 'to_send' ) );

	}

	/**
	 * Check if newsletter has been published and it is preparing, sending,
	 * or finished sending.
	 *
	 * @see     Boldermail_Post::is_published()
	 * @since   1.3.0
	 * @return  bool
	 */
	public function is_published() {

		return in_array( $this->get_status(), array( 'publish', 'preparing', 'sending', 'sent' ), true );

	}

	/**
	 * Are input fields read-only? Prevent editing if newsletter is published.
	 *
	 * @see     Boldermail_Newsletter::is_editable()
	 * @since   2.1.0
	 * @return  bool
	 */
	public function is_editable() {

		return ! $this->is_published();

	}

}
