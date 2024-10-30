<?php
/**
 * Boldermail.
 *
 * @link    https://www.boldermail.com
 * @package Boldermail
 *
 * @wordpress-plugin
 * Plugin Name: Boldermail
 * Plugin URI:  https://www.boldermail.com
 * Description: A newsletter plugin for WordPress powered by Amazon SES. Write email newsletters and/or share blog post updates with your subscribers for 75% less than the cost of using Mailchimp.
 * Version:     2.4.0
 * Author:      Hernan Villanueva
 * Author URI:  https://www.boldermail.com/about/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: boldermail
 * Domain Path: /languages
 */

defined( 'WPINC' ) || exit;

/**
 * Plugin constants.
 *
 * @see   SemVer https://semver.org
 * @since 1.0.0
 */
define( 'BOLDERMAIL_VERSION', '2.4.0' );
define( 'BOLDERMAIL_SLUG', 'boldermail' );
define( 'BOLDERMAIL_PREFIX', 'boldermail' );
define( 'BOLDERMAIL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'BOLDERMAIL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BOLDERMAIL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 *
 * @see   includes/class-boldermail-activator.php
 * @since 1.0.0
 */
function boldermail_activate() {
	require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-activator.php';
	Boldermail_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 *
 * @see   includes/class-boldermail-deactivator.php
 * @since 1.0.0
 */
function boldermail_deactivate() {
	require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-deactivator.php';
	Boldermail_Deactivator::deactivate();
}

/**
 * The code that runs during plugin uninstallation.
 * We use this method instead of `uninstall.php` because we need access
 * to the Boldermail admin functions to delete CRON hooks properly.
 *
 * @see   includes/class-boldermail-uninstaller.php
 * @since 1.0.0
 */
function boldermail_uninstall() {
	require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-uninstaller.php';
	Boldermail_Uninstaller::uninstall();
}

register_activation_hook( __FILE__, 'boldermail_activate' );
register_deactivation_hook( __FILE__, 'boldermail_deactivate' );
register_uninstall_hook( __FILE__, 'boldermail_uninstall' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 *
 * @since 1.0.0
 */
require BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail.php';

add_action( 'boldermail_init', 'boldermail_run' );
/**
 * Begins execution of Boldermail.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function boldermail_run() {
	Boldermail::instance();
}

do_action( 'boldermail_init' );
