<?php
/**
 * Handle Boldermail Cron tasks.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.7.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Cron class.
 *
 * @since 1.7.0
 */
class Boldermail_Cron {

	/**
	 * Initialize the cron tasks.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function init() {

		/**
		 * Update subscribers information.
		 *
		 * @since 1.7.0
		 */
		add_action( 'init', array( __CLASS__, 'schedule_updates' ) );
		add_action( 'boldermail_subscribers_update', array( __CLASS__, 'update_subscribers' ), 10, 1 );

		/**
		 * Scheduled WP Cron tasks.
		 *
		 * @since 1.7.0
		 */
		add_action( 'boldermail_scheduled_newsletter_rss_feed', array( __CLASS__, 'send_scheduled_campaign' ), 10, 1 );

		/**
		 * Refresh the Instagram access token.
		 *
		 * @since 2.3.0
		 */
		add_action( 'boldermail_instagram_integration_refresh_token', array( __CLASS__, 'instagram_integration_refresh_token' ), 10, 1 );

	}

	/**
	 * Schedule a CRON job to track new and updated subscribers.
	 * Deleting subscribers is a one-way operation (from WordPress to Boldermail),
	 * so there is no need to sync that.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function schedule_updates() {

		if ( ! wp_next_scheduled( 'boldermail_subscribers_update' ) ) {
			wp_schedule_event( time(), 'boldermail_subscriber_sync_interval', 'boldermail_subscribers_update' );
		}

	}

	/**
	 * Refresh the Instagram access token.
	 *
	 * @since 2.3.0
	 * @param string $user_id Instagram user ID.
	 */
	public static function instagram_integration_refresh_token( $user_id ) {

		$instagram_settings = boldermail_get_option( 'boldermail_instagram_integration' );
		$access_token       = in_array( $user_id, $instagram_settings['user_id'], true ) ? $instagram_settings['connected_accounts'][ $user_id ]['access_token'] : null;

		$instagram_api = new Boldermail_Instagram_API( $user_id, $access_token );

		$response = $instagram_api->refresh_token();

		if ( ! is_wp_error( $response ) ) {

			$new_access_token = $response['access_token'];
			$new_token_type   = $response['token_type'];
			$new_expires_in   = $response['expires_in'];

			$instagram_settings['connected_accounts'][ $user_id ]['access_token'] = $new_access_token;
			$instagram_settings['connected_accounts'][ $user_id ]['token_type']   = $new_token_type;
			$instagram_settings['connected_accounts'][ $user_id ]['expires_in']   = $new_expires_in;

			wp_schedule_single_event(
				time() + $new_expires_in - WEEK_IN_SECONDS,
				'boldermail_instagram_integration_refresh_token',
				array( $user_id )
			);

		} else {

			$instagram_settings['user_id'] = ( $instagram_settings['user_id'] ) ? array_diff( $instagram_settings['user_id'], [ $user_id ] ) : [];
			unset( $instagram_settings['connected_accounts'][ $user_id ] );

		}

		update_option( 'boldermail_instagram_integration', $instagram_settings, 'no' );

	}

	/**
	 * Schedule an RSS email campaign.
	 *
	 * @since  1.0.0 /includes/posts/class-boldermail-newsletter-rss-feed.php
	 * @since  1.7.1 /includes/posts/class-boldermail-cron.php
	 * @param  Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
	 * @return Boldermail_Error|bool                      True on success, Boldermail_Error on failure.
	 */
	public static function schedule_rss_campaign( $newsletter ) {

		// Delete all old jobs to avoid duplicates.
		self::clear_scheduled_rss_campaign( $newsletter );

		// Get newsletter data.
		$when_to_send = $newsletter->get_rss_when_to_send();
		$what_time    = $newsletter->get_rss_what_time();

		/**
		 * Cron jobs syntax.
		 *
		 *   ┌────────── minute (0 - 59)
		 *   │ ┌──────── hour (0 - 23)
		 *   │ │ ┌────── day of month (1 - 31)
		 *   │ │ │ ┌──── month (1 - 12)
		 *   │ │ │ │ ┌── day of week (0 - 6 => Sunday - Saturday, or
		 *   │ │ │ │ │                1 - 7 => Monday - Sunday)
		 *   ↓ ↓ ↓ ↓ ↓
		 *   * * * * * command to be executed
		 */
		$minute = ''; $hour = $what_time; $day = ''; $month = ''; $day_of_the_week = ''; /* phpcs:ignore Generic.Formatting.DisallowMultipleStatements.SameLine */

		switch ( $when_to_send ) {

			case 'every-day':
				$which_days      = $newsletter->get_rss_which_days();
				$minute          = '0';
				$day             = '*';
				$month           = '*';
				$day_of_the_week = implode( ',', $which_days );

				break;

			case 'every-week':
				$what_day        = $newsletter->get_rss_what_day();
				$minute          = '0';
				$day             = '*';
				$month           = '*';
				$day_of_the_week = $what_day;
				break;

			case 'every-month':
				$which_date      = $newsletter->get_rss_which_date();
				$minute          = '0';
				$day             = $which_date;
				$month           = '*';
				$day_of_the_week = '*';
				break;

			default:
				break;

		}

		$cron_job = $minute . ' ' . $hour . ' ' . $day . ' ' . $month . ' ' . $day_of_the_week;

		/**
		 * Load required files.
		 *
		 * @see   mtdowling/cron-expression https://github.com/mtdowling/cron-expression
		 * @since 1.0.0
		 */
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/plugins/cron-expression/autoload.php';

		/**
		 * Get a next run date relative to the current date or a specific date.
		 *
		 * @param string|DateTime $current_time       Relative calculation date.
		 * @param int             $nth                Number of matches to skip before returning a
		 *                                            matching next run date.  0, the default, will return the current
		 *                                            date and time if the next run date falls on the current date and
		 *                                            time. Setting this value to 1 will skip the first match and go to
		 *                                            the second match. Setting this value to 2 will skip the first 2
		 *                                            matches and so on.
		 * @param bool            $allow_current_date Set to TRUE to return the current date if
		 *                                            it matches the cron expression.
		 *
		 * @since 1.0.0
		 */
		$nth = 0; $allow_current_date = false; /* phpcs:ignore Generic.Formatting.DisallowMultipleStatements.SameLine */

		$timezone = get_option( 'timezone_string' ) ? get_option( 'timezone_string' ) : null;

		$dt = new DateTime( 'now' );

		if ( $timezone ) {
			$dt->setTimeZone( new DateTimeZone( $timezone ) );
		}

		try {
			$cron_expression = Cron\CronExpression::factory( $cron_job );
		} catch ( InvalidArgumentException $e ) { // If expression is invalid due to invalid options.
			return new Boldermail_Error( 'no_feed_schedule' );
		}

		$next_cron_date = $cron_expression->getNextRunDate( $dt, $nth, $allow_current_date )->format( 'Y-m-d H:i:s' );

		$scheduled = wp_schedule_single_event(
			strtotime( get_gmt_from_date( $next_cron_date ) . ' GMT' ),
			'boldermail_scheduled_newsletter_rss_feed',
			array( $newsletter->get_post_id() )
		);

		return ( $scheduled ) ? true : new Boldermail_Error( 'no_feed_schedule' );

	}

	/**
	 * Clear any scheduled hooks that check for new content in the feed.
	 *
	 * @since 1.0.0 /includes/posts/class-boldermail-newsletter-rss-feed.php
	 * @since 1.7.1 /includes/posts/class-boldermail-cron.php
	 * @param Boldermail_Newsletter $newsletter Newsletter object.
	 */
	public static function clear_scheduled_rss_campaign( $newsletter ) {

		wp_clear_scheduled_hook( 'boldermail_scheduled_newsletter_rss_feed', array( $newsletter->get_post_id() ) );

	}

	/**
	 * Send an scheduled RSS campaign.
	 *
	 * @since  1.7.0
	 * @param  int $post_id Post ID.
	 * @return void
	 */
	public static function send_scheduled_campaign( $post_id ) {

		// Setup post data if doing cron.
		if ( $post_id && wp_doing_cron() ) {
			boldermail_setup_postdata( $post_id );
		}

		$newsletter = boldermail_get_newsletter( $post_id );

		if ( ! $newsletter ) {
			return;
		}

		if ( $newsletter->get_type() !== 'rss-feed' ) {
			return;
		}

		// Check for new posts.
		$posts = $newsletter->get_the_posts( 'display' );

		if ( $posts && count( $posts ) > 0 ) {

			$this_post = get_post( $newsletter->get_post_id() );

			remove_action( 'save_post', array( Boldermail_Transitions::instance(), 'add_default_meta' ), 10 );

			$newsletter_post_id = wp_insert_post(
				array(
					'post_title'   => boldermail_sanitize_text( $newsletter->get_filtered_subject( 'raw', 'display' ) ),
					'post_content' => '',
					'post_status'  => 'publish',
					'post_type'    => 'bm_newsletter',
					'post_author'  => $this_post->post_author,
					'post_parent'  => $this_post->ID,
					'meta_input'   => array(
						'_list_id'         => boldermail_sanitize_text( implode( ',', $newsletter->get_list_id() ) ),
						'_from_name'       => boldermail_sanitize_text( $newsletter->get_from_name() ),
						'_from_email'      => boldermail_sanitize_email( $newsletter->get_from_email() ),
						'_reply_to'        => boldermail_sanitize_email( $newsletter->get_reply_to() ),
						'_company_name'    => boldermail_sanitize_text( $newsletter->get_company_name() ),
						'_company_address' => boldermail_sanitize_text( $newsletter->get_company_address() ),
						'_permission'      => boldermail_sanitize_textarea( $newsletter->get_permission() ),
						'_subject'         => boldermail_sanitize_text( $newsletter->get_filtered_subject( 'raw', 'display' ) ),
						'_preview_text'    => boldermail_sanitize_text( $newsletter->get_preview_text() ),
						'_html'            => $newsletter->get_filtered_html( 'display' ),
					),
				)
			);

			add_action( 'save_post', array( Boldermail_Transitions::instance(), 'add_default_meta' ), 10, 3 );

			if ( $newsletter_post_id ) {

				/**
				 * Save the last time email was sent.
				 *
				 * @since   1.2.0
				 */
				$newsletter->set_meta( 'last_rss_email_time', current_time( 'mysql' ) );

			}

		}

		// Update the last time we checked for content -- do this here because of shortcodes!
		$newsletter->set_meta( 'last_rss_check_time', current_time( 'mysql' ) );

		// Schedule the next check.
		self::schedule_rss_campaign( $newsletter );

	}

	/**
	 * Update subscriber information for a list.
	 *
	 * This follows the logic uses in the WordPress Importer plugin.
	 * Keep WP deferral inside loop because there is more chance of the
	 * `for` loop getting killed than the individual actions.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function update_subscribers() {

		$lists = boldermail_get_lists();

		foreach ( $lists as $list ) {

			// Setup post data if doing cron.
			if ( $list && wp_doing_cron() ) {
				boldermail_setup_postdata( $list->get_post_id() );
			}

			// Delete transient in cache -- just in case!
			wp_cache_delete( "boldermail_subscribers_update_{$list->get_post_id()}", 'options' );

			// Check transient.
			$lock = get_transient( "boldermail_subscribers_update_{$list->get_post_id()}" );

			if ( $lock ) {
				continue;
			}

			// Set new transient.
			set_transient( "boldermail_subscribers_update_{$list->get_post_id()}", time(), HOUR_IN_SECONDS );

			// @see WP_Import::import_start /wp-content/plugins/wordpress-importer/wordpress-importer.php
			wp_defer_term_counting( true );
			wp_defer_comment_counting( true );

			// @see WP_Import::import /wp-content/plugins/wordpress-importer/wordpress-importer.php
			wp_suspend_cache_invalidation( true );

			// Update subscribers.
			self::update_list_subscribers( $list );

			// Delete transient.
			delete_transient( "boldermail_subscribers_update_{$list->get_post_id()}" );

			// @see WP_Import::import /wp-content/plugins/wordpress-importer/wordpress-importer.php
			wp_suspend_cache_invalidation( false );

			// @see WP_Import::import_end /wp-content/plugins/wordpress-importer/wordpress-importer.php
			wp_cache_flush();
			wp_defer_term_counting( false );
			wp_defer_comment_counting( false );

		}

	}

	/**
	 * Update subscribers on our end based on changes on the Boldermail database.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_List $list Boldermail_List object.
	 * @return void
	 */
	private static function update_list_subscribers( Boldermail_List $list ) {

		global $wpdb;

		// Get triggered subscribers.
		$subscriber_updates = boldermail()->api->get_list_subscribers_data(
			array(
				'list_id'              => boldermail_sanitize_text( $list->get_list_id() ),
				'timestamp'            => boldermail_sanitize_int( $list->get_timestamp() ),
				'timestamp__not_email' => boldermail_sanitize_text( implode( ',', $list->get_timestamp__not_email() ) ),
			)
		);

		if ( is_wp_error( $subscriber_updates ) || count( $subscriber_updates ) === 0 ) {
			return;
		}

		// Track subscriber errors.
		$update_errors = $list->get_update_errors();

		// Get email excludes.
		$timestamp__not_email = $list->get_timestamp__not_email();

		foreach ( $subscriber_updates as $subscriber_data ) {

			// Get latest timestamp check.
			$old_timestamp = $list->get_timestamp();

			// If we don't have this basic data, continue.
			if ( ! $subscriber_data['email'] || ! $subscriber_data['list_post_id'] ) {
				continue;
			}

			// Get first subscriber.
			$subscriber = boldermail_get_subscriber_from_email_and_list( $subscriber_data['email'], $subscriber_data['list_post_id'] );
			$subscriber = is_array( $subscriber ) ? current( $subscriber ) : $subscriber;

			// The MySQL server has gone away...
			if ( $wpdb->last_error ) {
				return;
			}

			// If no subscriber exists in WordPress, create it.
			if ( ! $subscriber ) {

				// Remove transitions such that the Boldermail API does not get called.
				remove_action( 'transition_post_status', array( 'Boldermail_Transitions', 'subscriber_transition' ), 9999 );

				// Insert new subscriber.
				$subscriber_post_id = wp_insert_post(
					array(
						'post_title'   => $subscriber_data['email'],
						'post_content' => $subscriber_data['name'] . ' ' . $subscriber_data['custom_fields']['Last Name'] . ' (' . $subscriber_data['email'] . ')',
						'post_parent'  => $subscriber_data['list_post_id'],
						'post_status'  => $subscriber_data['status'],
						'post_type'    => 'bm_subscriber',
					)
				);

				// Restore API transitions.
				add_action( 'transition_post_status', array( 'Boldermail_Transitions', 'subscriber_transition' ), 9999, 3 );

				if ( is_wp_error( $subscriber_post_id ) || ! $subscriber_post_id ) {
					$update_errors[] = $subscriber_data['email'];
					continue;
				}

				// Get subscriber object.
				$subscriber = boldermail_get_subscriber( $subscriber_post_id );

				if ( ! $subscriber ) {
					wp_delete_post( $subscriber_post_id );
					$update_errors[] = $subscriber_data['email'];
					continue;
				}

			}

			// Save new data.
			$subscriber->save( $subscriber_data );

			// Reset the timestamp exclude lists if this is a new timestamp.
			if ( $subscriber_data['timestamp'] > $old_timestamp ) {
				$timestamp__not_email = array();
			}

			$timestamp__not_email[] = $subscriber_data['email'];

			$list->save_meta(
				array(
					'timestamp__not_email' => $timestamp__not_email,
					'timestamp'            => $subscriber_data['timestamp'],
					'update_errors'        => $update_errors,
				)
			);

		}

	}

}

Boldermail_Cron::init();
