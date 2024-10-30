<?php
/**
 * A timeout resistant, single-serve upgrader for Boldermail.
 *
 * This class is used to make all reasonable attempts to neatly upgrade data
 * between versions of Boldermail.
 *
 * Based on `WC_Subscriptions_Upgrader`.
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
 * Boldermail_Upgrade class.
 *
 * @since 1.7.0
 */
class Boldermail_Upgrade {

	/**
	 * Active version stored in database.
	 *
	 * @since 1.7.0
	 * @var   string
	 */
	private static $active_version;

	/**
	 * Initialize the hooks.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function init() {

		self::$active_version = get_option( 'boldermail_active_version', '0' );

		$version_out_of_date = version_compare( self::$active_version, BOLDERMAIL_VERSION, '<' );

		// Set the cron lock on every request with an out of date version, regardless of authentication level,
		// as we can only lock cron for up to 10 minutes at a time. We need to keep it locked until the upgrade is complete,
		// regardless of who is browsing the site.
		if ( $version_out_of_date ) {
			self::set_cron_lock();
		}

		if ( @current_user_can( 'activate_plugins' ) ) { /* phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged */

			if ( $version_out_of_date ) {

				$is_upgrading = get_option( 'boldermail_is_upgrading', false );

				/**
				 * Check if we've exceeded the 2 minute upgrade window we use for blocking upgrades.
				 * We could seemingly use transients here to get the check for free
				 * if transients were guaranteed to exist.
				 *
				 * @see   http://journal.rmccue.io/296/youre-using-transients-wrong/
				 * @since 1.7.0
				 */
				if ( false !== $is_upgrading && $is_upgrading < gmdate( 'U' ) ) {
					$is_upgrading = false;
					delete_option( 'boldermail_is_upgrading' );
				}

				if ( false !== $is_upgrading ) {

					add_action( 'init', array( __CLASS__, 'upgrade_in_progress_notice' ), 11 );

				} else {

					// Run upgrades as soon as admin hits site.
					add_action( 'wp_loaded', array( __CLASS__, 'upgrade' ), 11 );

				}

			}

		}

	}

	/**
	 * Let the site administrator know we are upgrading the database already
	 * to prevent duplicate processes running the upgrade. Also provides some
	 * useful diagnostic information, like how long before the site admin can
	 * restart the upgrade process, and how many subscriptions per request can
	 * typically be updated given the amount of memory allocated to PHP.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function upgrade_in_progress_notice() {

		include_once BOLDERMAIL_PLUGIN_DIR . 'partials/upgrades/html-boldermail-upgrade-in-progress.php';

	}

	/**
	 * Checks which upgrades need to run and calls the necessary functions for that upgrade.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function upgrade() {

		update_option( 'boldermail_previous_version', self::$active_version );

		/**
		 * Before upgrade hook.
		 *
		 * @uses    BOLDERMAIL_VERSION
		 * @since   1.7.0
		 */
		do_action( 'boldermail_before_upgrade', BOLDERMAIL_VERSION, self::$active_version );

		// Keep track of site url to prevent duplicate payments from staging sites.
		// First added in 1.3.8 & updated with 1.4.2 to work with WP Engine staging sites.
		if ( '0' === self::$active_version || version_compare( self::$active_version, '1.7.0', '<' ) ) {
			Boldermail_Site::set_duplicate_site_url_lock();
		}

		self::upgrade_complete();

	}

	/**
	 * When an upgrade is complete, set the active version, delete the
	 * transient locking upgrade and fire a hook.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function upgrade_complete() {

		update_option( 'boldermail_active_version', BOLDERMAIL_VERSION );

		delete_transient( 'doing_cron' );

		delete_option( 'boldermail_is_upgrading' );

		do_action( 'boldermail_upgraded', BOLDERMAIL_VERSION, self::$active_version );

	}

	/**
	 * Try to block WP-Cron until upgrading finishes. spawn_cron() will only
	 * let us steal the lock for 10 minutes into the future, so we can actually
	 * only block it for 9 minutes confidently. But as long as the upgrade
	 * process continues, the lock will remain.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	private static function set_cron_lock() {

		delete_transient( 'doing_cron' );

		set_transient( 'doing_cron', sprintf( '%.22F', 9 * MINUTE_IN_SECONDS + microtime( true ) ), 0 );

	}

}
