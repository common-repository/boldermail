<?php
/**
 * Autoresponder newsletter class.
 *
 * The Boldermail newsletter class for Autoresponders.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.4.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/abstract-class-boldermail-newsletter.php';

/**
 * Boldermail_Newsletter_Autoresponder class.
 *
 * @since 1.4.0
 */
class Boldermail_Newsletter_Autoresponder extends Boldermail_Newsletter {

	/**
	 * Constructor.
	 *
	 * @since 1.4.0
	 * @param int $post_id The post ID.
	 */
	public function __construct( $post_id ) {

		parent::__construct( $post_id, 'autoresponder' );

	}

	/**
	 * Save campaign data.
	 *
	 * @since  1.4.0
	 * @param  array $campaign_data Array of campaign data (should be sanitized).
	 * @return void
	 */
	public function save( $campaign_data ) {

		// Save all data data, except `status`.
		$this->save_meta(
			[
				'recipients' => $campaign_data['recipients'],
				'opens'      => $campaign_data['opens'],
				'links'      => $campaign_data['links'],
			]
		);

		// Update the newsletter status first.
		$this->set_status( $campaign_data['enabled'] );

		// Set unique opens and clicks values for sorting in admin list.
		$this->set_unique_opens_and_clicks();

	}

	/**
	 * Set the automated email status.
	 *
	 * @since 1.4.0
	 * @param string $enabled Whether the automated email is enabled on Boldermail or not.
	 */
	public function set_status( $enabled ) {

		if ( $enabled ) {
			$this->wpdb_update( array( 'post_status' => 'enabled' ) );
		} else {
			$this->wpdb_update( array( 'post_status' => 'paused' ) );
		}

	}

	/**
	 * Set the unique opens and clicks values for sorting the columns
	 * in the admin list.
	 *
	 * Automated emails that have not been previously published do not save a
	 * meta value. Newsletters in the `draft` or `publish` statuses that have
	 * an automated email ID from Boldermail store the unique opens and clicks.
	 * If the newsletter contains no links, then we assign a value of -1 to
	 * separate those newsletters from the ones with links and clicks.
	 *
	 * So our hierarchy grouping for unique opens is:
	 * Publish|Draft (with ID) > Draft
	 *
	 * and for unique clicks is:
	 * Publish|Draft (with ID) > No links > Draft
	 *
	 * @since  1.3.0
	 * @return void
	 */
	public function set_unique_opens_and_clicks() {

		$unique_opens  = '';
		$unique_clicks = '';

		if ( in_array( $this->get_status(), array( 'enabled', 'paused' ), true ) ) {
			$unique_opens  = $this->get_unique_opens();
			$unique_clicks = ( ! empty( $this->get_clicks_data() ) ) ? $this->get_unique_clicks() : -1;
		}

		$this->save_meta(
			[
				'unique_opens'  => $unique_opens,
				'unique_clicks' => $unique_clicks,
			]
		);

	}

	/**
	 * Get the automated email ID for the Boldermail server.
	 *
	 * @since  1.4.0
	 * @return int Automated email ID.
	 */
	public function get_ares_email_id() {

		return absint( $this->get_meta( 'id' ) );

	}

	/**
	 * Get the autoresponder object associated with this automated email.
	 *
	 * @since  1.4.0
	 * @return Boldermail_Autoresponder|null
	 */
	public function get_autoresponder() {

		$autoresponder_post_id = $this->get_autoresponder_post_id();

		return boldermail_get_autoresponder( $autoresponder_post_id );

	}

	/**
	 * Get the autoresponder ID for the Boldermail server.
	 *
	 * @since  1.4.0
	 * @return int
	 */
	public function get_autoresponder_id() {

		$autoresponder = $this->get_autoresponder();

		return ( $autoresponder ) ? absint( $autoresponder->get_autoresponder_id() ) : '';

	}

	/**
	 * Get the autoresponder post ID.
	 *
	 * @since  1.4.0
	 * @return int
	 */
	public function get_autoresponder_post_id() {

		return wp_get_post_parent_id( $this->get_post_id() );

	}

	/**
	 * Get the autoresponder type.
	 *
	 * @since  1.4.0
	 * @return string
	 */
	public function get_autoresponder_type() {

		$autoresponder = $this->get_autoresponder();

		return ( $autoresponder ) ? $autoresponder->get_type() : '';

	}

	/**
	 * Get the before or after condition for the trigger of this automated email.
	 *
	 * @since  1.4.0
	 * @return string
	 */
	public function get_trigger_beforeafter() {

		return $this->get_meta( 'trigger_beforeafter' );

	}

	/**
	 * Get the wait time for the before/after condition of the trigger.
	 *
	 * @since  1.4.0
	 * @return int
	 */
	public function get_trigger_number() {

		return absint( $this->get_meta( 'trigger_number' ) );

	}

	/**
	 * Get the interval (time unit) for the before/after condition of the trigger.
	 *
	 * @since  1.4.0
	 * @return string
	 */
	public function get_trigger_interval() {

		return $this->get_meta( 'trigger_interval' );

	}

	/**
	 * Check if automated email has been published in the Boldermail servers.
	 *
	 * @since  1.4.0
	 * @return bool
	 */
	public function is_published() {

		return in_array( $this->get_status(), array( 'publish', 'enabled', 'paused' ), true );

	}

	/**
	 * Input fields are always editable.
	 *
	 * @see    Boldermail_Newsletter::is_editable()
	 * @since  2.1.0
	 * @return bool
	 */
	public function is_editable() {

		return true;

	}

}
