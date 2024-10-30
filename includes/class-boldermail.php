<?php
/**
 * The core Boldermail plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/includes
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail class.
 *
 * @since 1.0.0
 */
class Boldermail {

	/**
	 * Singleton instance.
	 *
	 * @since 1.0.0
	 * @var Boldermail $instance
	 */
	private static $instance;

	/**
	 * Handles the Boldermail API.
	 *
	 * @since 1.7.0
	 * @access public
	 * @var Boldermail_API $api
	 */
	public $api;

	/**
	 * Handles the countries and their locale.
	 *
	 * @since 1.7.0
	 * @access public
	 * @var Boldermail_Countries $countries
	 */
	public $countries;

	/**
	 * Handles the shortcodes
	 *
	 * @since 1.7.0
	 * @access public
	 * @var Boldermail_Shortcodes $shortcodes
	 */
	public $shortcodes;

	/**
	 * Get instance.
	 *
	 * @since 1.0.0
	 * @return Boldermail
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load the dependencies, define the locale, and set the hooks
	 * for the admin area and the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {

		/**
		 * Include required core files used in admin.
		 *
		 * @since 1.0.0
		 */
		$this->includes();

		/**
		 * Enqueue scripts and styles
		 *
		 * @since 1.0.0
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ), PHP_INT_MAX );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), PHP_INT_MAX );

		/**
		 * Remove the emoji script as it always defaults to replacing emojis with
		 * Twemoji images. Twemoji uses inline HTML to display emojis with either
		 * PNG or SVG files. These are not compatible with subject lines in email.
		 * Also, the emoji script is incompatible with both React and any contenteditable
		 * fields. WooCommerce and Gutenberg has also disabled emojis.
		 *
		 * @see https://github.com/WordPress/gutenberg/pull/6151
		 * @since 2.1.0
		 */
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

		/**
		 * Uses the Boldermail_API class
		 * in order to interact with the Boldermail server.
		 *
		 * @since 1.7.0
		 */
		$app_url = get_option( 'boldermail_url' );
		$api_key = get_option( 'boldermail_api' );
		$app_id  = get_option( 'boldermail_app' );

		$this->api = new Boldermail_API( $app_url, $api_key, $app_id );

		/**
		 * Initialize shortcodes tool.
		 *
		 * @since 1.7.0
		 */
		$this->shortcodes = Boldermail_Shortcodes::instance();

		/**
		 * Initialize countries tool.
		 *
		 * @since 1.7.0
		 */
		$this->countries = new Boldermail_Countries();

	}

	/**
	 * Setup the post types, taxonomies, meta boxes, shortcodes, and settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function includes() {

		// i18n.
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-i18n.php';

		// Utilities.
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/boldermail-admin-functions.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/boldermail-formatting-functions.php';

		// API.
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/api/class-boldermail-api.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/api/class-boldermail-countdown-rest-controller.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/api/class-boldermail-errors.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/api/class-boldermail-instagram-api.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/api/class-boldermail-transitions.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/api/class-boldermail-twitter-api.php';

		// Tools.
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/tools/class-boldermail-countries.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/tools/class-boldermail-editor.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/tools/class-boldermail-error.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/tools/class-boldermail-site.php';

		// Setup.
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-ajax.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-cron.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-fetch.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-help.php';
//		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-importers.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-install.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-menus.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-messages.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-meta-boxes.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-post-types.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-settings.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-taxonomies.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-template-loader.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/class-boldermail-upgrade.php';

		// Shortcodes.
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/shortcodes/class-boldermail-shortcodes.php';

		// Gutenberg.
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/gutenberg/class-boldermail-gutenberg.php';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_styles() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'boldermail', BOLDERMAIL_PLUGIN_URL . "assets/css/boldermail-admin$suffix.css", array(), BOLDERMAIL_VERSION, 'all' );

		// Fancybox for previews.
		wp_enqueue_style( 'fancybox', "https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox$suffix.css", array(), '3.5.7', 'all' );

		// select2 for select fields.
		wp_enqueue_style( 'select2', "https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/css/select2$suffix.css", array(), '4.0.12', 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script(
			'boldermail',
			BOLDERMAIL_PLUGIN_URL . "assets/js/boldermail-admin$suffix.js",
			array(
				'jquery',
				'jquery-ui-sortable',
				'postbox',
				'fancybox',
				'select2',
				'jquery-tiptip',
				'emoji-button',
			),
			BOLDERMAIL_VERSION,
			true
		);

		$screen = get_current_screen();

		if ( 'edit' === $screen->base && in_array( $screen->post_type, array( 'bm_newsletter', 'bm_newsletter_ares' ), true ) ) {
			wp_enqueue_script( 'boldermail-edit-bm_newsletter', BOLDERMAIL_PLUGIN_URL . "assets/js/boldermail-admin-edit-bm_newsletter$suffix.js", array( 'boldermail' ), BOLDERMAIL_VERSION, true );
		}

		if ( 'post' === $screen->base && in_array( $screen->post_type, array( 'bm_subscriber' ), true ) ) {
			wp_enqueue_script( 'boldermail-edit-bm_newsletter', BOLDERMAIL_PLUGIN_URL . "assets/js/boldermail-admin-subscriber-custom-fields$suffix.js", array( 'boldermail' ), BOLDERMAIL_VERSION, true );
		}

		if ( 'post' === $screen->base && in_array( $screen->post_type, array( 'bm_list' ), true ) ) {
			wp_enqueue_script( 'boldermail-list-custom-fields', BOLDERMAIL_PLUGIN_URL . "assets/js/boldermail-admin-list-custom-fields$suffix.js", array( 'boldermail', 'jquery' ), BOLDERMAIL_VERSION, true );
		}

		wp_enqueue_script( 'boldermail-editor', BOLDERMAIL_PLUGIN_URL . "assets/js/boldermail-admin-editor$suffix.js", array( 'boldermail', 'jquery', 'wp-i18n' ), BOLDERMAIL_VERSION, true );
		wp_enqueue_script( 'boldermail-heartbeat', BOLDERMAIL_PLUGIN_URL . "assets/js/boldermail-admin-heartbeat$suffix.js", array( 'jquery' ), BOLDERMAIL_VERSION, true );

		// Fancybox for previews.
		wp_enqueue_script( 'fancybox', 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );

		// select2 for select fields.
		wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.12/js/select2.min.js', array( 'jquery' ), '4.0.12', true );

		// tipTip for help tips.
		wp_enqueue_script( 'jquery-tiptip', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.tiptip/1.3/jquery.tipTip.minified.js', array( 'jquery' ), '1.3', true );

		// Emoji Button.
		wp_enqueue_script( 'emoji-button', 'https://github.com/joeattardi/emoji-button/releases/download/v3.1.1/emoji-button-3.1.1.min.js', array(), '3.1.1', true );

		$this->localize_scripts();

	}

	/**
	 * Localize variables into the scripts.
	 *
	 * @since 2.2.0
	 * @return void
	 */
	public function localize_scripts() {

		global $post_type_object;

		$screen = get_current_screen();

		$script_params = array(
			'restUrl'   => esc_url_raw( get_rest_url() ),
			'ajaxUrl'   => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
			'ajaxNonce' => wp_create_nonce( 'boldermail_ajax' ),
		);

		if ( 'bm_newsletter' === $screen->post_type ) {
			$script_params += array(
				'screenId'   => $screen->id,
				'postStatus' => get_post_status(),
			);
		}

		if ( 'bm_block_template' === $screen->post_type ) {
			$script_params += array(
				'editNewsletterLink' => current_user_can( $post_type_object->cap->edit_posts ) ? untrailingslashit( get_edit_post_link( wp_get_post_parent_id( get_post() ) ) ) . '#design_panel' : null,
			);
		}

		if ( 'bm_template' === $screen->post_type ) {
			$script_params += array(
				'trashTemplateLink' => current_user_can( $post_type_object->cap->delete_posts ) ? esc_url_raw( wp_specialchars_decode( get_delete_post_link() ) ) : null,
				'newTemplateLink'   => array(
					'classicEditor' => current_user_can( $post_type_object->cap->create_posts ) ? esc_url_raw( admin_url( 'post-new.php?post_type=bm_template' ) ) : null,
					'blockEditor'   => current_user_can( $post_type_object->cap->create_posts ) ? esc_url_raw( admin_url( 'post-new.php?post_type=bm_template&bm_block_template' ) ) : null,
				),
			);
		}

		if ( 'bm_subscriber' === $screen->post_type ) {
			$script_params += array(
				'importSubscriberLink' => current_user_can( $post_type_object->cap->create_posts ) ? esc_url_raw( admin_url( 'edit.php?post_type=bm_newsletter&page=subscriber_importer' ) ) : null,
				'exportSubscriberLink' => current_user_can( $post_type_object->cap->create_posts ) ? esc_url_raw( admin_url( 'edit.php?post_type=bm_newsletter&page=subscriber_exporter' ) ) : null,
			);
		}

		wp_localize_script( 'boldermail', 'boldermail', $script_params );

	}

}

/**
 * Return an instance of Boldermail.
 *
 * @since 1.0.0
 * @return Boldermail
 */
function boldermail() {
	return Boldermail::instance();
}
