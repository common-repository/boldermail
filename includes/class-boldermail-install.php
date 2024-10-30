<?php
/**
 * Installation related functions and actions.
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
 * Boldermail_Install class.
 *
 * @since 1.7.0
 */
class Boldermail_Install {

	/**
	 * Initialize the hooks.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function init() {

		/**
		 * Add image sizes.
		 *
		 * @since 1.1.0
		 */
		add_image_size( 'boldermail_newsletter', 564, 9999, false );
		add_image_size( 'boldermail_template_thumbnail', 480, 480, true );

		/**
		 * Upgrade the plugin if necessary.
		 *
		 * @since 1.7.0
		 */
		add_action( 'after_setup_theme', array( 'Boldermail_Upgrade', 'init' ), 11 );

		/**
		 * Register the default template data on the `init` hook,
		 * instead of the `register_activation_hook` because taxonomies are
		 * not registered during activation. Furthermore, localization is not
		 * available yet until `init`, so we the text wouldn't translate
		 * properly.
		 *
		 * @since   1.2.0
		 */
		add_action( 'init', array( __CLASS__, 'add_default_templates' ), 9999 );

		/**
		 * Add the cron schedules.
		 *
		 * @since 1.7.0
		 */
		add_filter( 'cron_schedules', array( __CLASS__, 'cron_schedules' ) ); /* phpcs:ignore WordPress.WP.CronInterval.CronSchedulesInterval */

		/**
		 * Add plugin information to Plugins page.
		 *
		 * @since 1.7.0
		 */
		add_filter( 'plugin_action_links_' . BOLDERMAIL_PLUGIN_BASENAME, array( __CLASS__, 'plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );

	}

	/**
	 * Add default template data + thumbnails.
	 *
	 * @since 1.2.0
	 */
	public static function add_default_templates() {

		/**
		 * Check for the option here. In a previous version, we included this check before adding the hook.
		 * However, the `init` hook may get called multiple times during loading.
		 * Once this function got called there was no check to see if the posts were already inserted.
		 * Therefore, we include this check here instead.
		 *
		 * @since 1.4.0
		 */
		if ( get_option( 'boldermail_add_default_templates' ) === 'no' ) {
			return;
		}

		// Update option.
		add_option( 'boldermail_add_default_templates', 'no', '', 'no' );

		$default_templates = array();

		/**
		 * One column.
		 *
		 * @since 1.2.0
		 */
		$default_templates[] = self::insert_template(
			array(
				'title'          => __( 'One Column', 'boldermail' ),
				'html'           => boldermail_get_include( BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-template-one-column.php' ),
				'tags'           => array( 'content', 'one-column', 'title', 'excerpt', 'permalink', 'social' ),
				'thumb_filename' => BOLDERMAIL_PLUGIN_DIR . 'assets/images/templates/one-column.jpg',
				'thumb_title'    => __( 'One Column Template Thumbnail', 'boldermail' ),
			)
		);

		/**
		 * One column with logo.
		 *
		 * @since 1.2.0
		 */
		$default_templates[] = self::insert_template(
			array(
				'title'          => __( 'One Column Logo', 'boldermail' ),
				'html'           => boldermail_get_include( BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-template-one-column-logo.php' ),
				'tags'           => array( 'content', 'one-column', 'logo', 'title', 'excerpt', 'social' ),
				'thumb_filename' => BOLDERMAIL_PLUGIN_DIR . 'assets/images/templates/one-column-logo.jpg',
				'thumb_title'    => __( 'One Column Logo Template Thumbnail', 'boldermail' ),
			)
		);

		/**
		 * One column with permalink + logo.
		 *
		 * @since 1.2.0
		 */
		$default_templates[] = self::insert_template(
			array(
				'title'          => __( 'One Column Permalink Logo', 'boldermail' ),
				'html'           => boldermail_get_include( BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-template-one-column-permalink-logo.php' ),
				'tags'           => array( 'content', 'one-column', 'logo', 'title', 'excerpt', 'permalink', 'social' ),
				'thumb_filename' => BOLDERMAIL_PLUGIN_DIR . 'assets/images/templates/one-column-permalink-logo.jpg',
				'thumb_title'    => __( 'One Column Permalink Logo Template Thumbnail', 'boldermail' ),
			)
		);

		/**
		 * One column with RSS feed support.
		 *
		 * @since 1.2.0
		 */
		$default_templates[] = self::insert_template(
			array(
				'title'          => __( 'One Column (for RSS Feed)', 'boldermail' ),
				'html'           => boldermail_get_include( BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-template-one-column-rss.php' ),
				'tags'           => array( 'content', 'one-column', 'title', 'excerpt', 'permalink', 'social', 'rss-feed' ),
				'thumb_filename' => BOLDERMAIL_PLUGIN_DIR . 'assets/images/templates/one-column-rss.jpg',
				'thumb_title'    => __( 'One Column RSS Feed Template Thumbnail', 'boldermail' ),
			)
		);

		/**
		 * One column full width.
		 *
		 * @since 1.2.0
		 */
		$default_templates[] = self::insert_template(
			array(
				'title'          => __( 'One Column Full Width', 'boldermail' ),
				'html'           => boldermail_get_include( BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-template-one-column-full-width.php' ),
				'tags'           => array( 'content', 'one-column', 'title', 'excerpt', 'permalink', 'social' ),
				'thumb_filename' => BOLDERMAIL_PLUGIN_DIR . 'assets/images/templates/one-column-full-width.jpg',
				'thumb_title'    => __( 'One Column Full Width Template Thumbnail', 'boldermail' ),
			)
		);

		/**
		 * One column full width with RSS support.
		 *
		 * @since 1.2.0
		 */
		$default_templates[] = self::insert_template(
			array(
				'title'          => __( 'One Column Full Width (for RSS Feed)', 'boldermail' ),
				'html'           => boldermail_get_include( BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-template-one-column-full-width-rss.php' ),
				'tags'           => array( 'content', 'one-column', 'title', 'excerpt', 'permalink', 'social', 'rss-feed' ),
				'thumb_filename' => BOLDERMAIL_PLUGIN_DIR . 'assets/images/templates/one-column-full-width-rss.jpg',
				'thumb_title'    => __( 'One Column Full Width RSS Feed Template Thumbnail', 'boldermail' ),
			)
		);

		/**
		 * Simple text template.
		 *
		 * @since 1.2.0
		 */
		$default_templates[] = self::insert_template(
			array(
				'title'          => __( 'Simple Text', 'boldermail' ),
				'html'           => boldermail_get_include( BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-template-simple-text.php' ),
				'tags'           => array( 'content', 'one-column', 'plain-text', 'title' ),
				'thumb_filename' => BOLDERMAIL_PLUGIN_DIR . 'assets/images/templates/simple-text.jpg',
				'thumb_title'    => __( 'Simple Text Template Thumbnail', 'boldermail' ),
			)
		);

		/**
		 * Simple text template with RSS support.
		 *
		 * @since 1.2.0
		 */
		$default_templates[] = self::insert_template(
			array(
				'title'          => __( 'Simple Text (for RSS Feed)', 'boldermail' ),
				'html'           => boldermail_get_include( BOLDERMAIL_PLUGIN_DIR . 'partials/template/html-boldermail-template-simple-text-rss.php' ),
				'tags'           => array( 'content', 'one-column', 'plain-text', 'title', 'rss-feed' ),
				'thumb_filename' => BOLDERMAIL_PLUGIN_DIR . 'assets/images/templates/simple-text-rss.jpg',
				'thumb_title'    => __( 'Simple Text RSS Feed Template Thumbnail', 'boldermail' ),
			)
		);

		// Save default templates.
		add_option( 'boldermail_default_templates', $default_templates, '', 'no' );

	}

	/**
	 * Add new schedules to track updates.
	 *
	 * @since  1.0.0
	 * @param  array $schedules Cron schedules.
	 * @return array
	 */
	public static function cron_schedules( $schedules ) {

		if ( ! isset( $schedules['boldermail_subscriber_sync_interval'] ) ) {
			$schedules['boldermail_subscriber_sync_interval'] = array(
				'interval' => 300,
				'display'  => __( 'Every 5 minutes', 'boldermail' ),
			);
		}

		return $schedules;

	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @since  1.7.0
	 * @param  string[] $links Plugin action links.
	 * @return array
	 */
	public static function plugin_action_links( $links ) {

		$action_links = array(
			'settings' => '<a href="' . esc_url( admin_url( 'edit.php?post_type=bm_newsletter&page=boldermail-settings' ) ) . '" aria-label="' . esc_attr__( 'View Boldermail settings', 'boldermail' ) . '">' . esc_html__( 'Settings', 'boldermail' ) . '</a>',
			'docs'     => '<a href="' . esc_url( 'https://www.boldermail.com/documentation/' ) . '" aria-label="' . esc_attr__( 'View Boldermail documentation', 'boldermail' ) . '">' . esc_html__( 'Docs', 'boldermail' ) . '</a>',
			'support'  => '<a href="' . esc_url( 'https://www.boldermail.com/contact/' ) . '" aria-label="' . esc_attr__( 'Get support', 'boldermail' ) . '">' . esc_html__( 'Support', 'boldermail' ) . '</a>',
		);

		return array_merge( $action_links, $links );

	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @since  1.7.0
	 * @param  string[] $links Plugin row meta.
	 * @param  string   $file  Plugin base file.
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {

		if ( BOLDERMAIL_PLUGIN_BASENAME === $file ) {

			$row_meta = array(
				'subscription' => '<a href="' . esc_url( 'https://www.boldermail.com/my-account/subscriptions/' ) . '" aria-label="' . esc_attr__( 'View your Boldermail subscription', 'boldermail' ) . '">' . esc_html__( 'View subscription', 'boldermail' ) . '</a>',
			);

			return array_merge( $links, $row_meta );

		}

		return (array) $links;

	}

	/**
	 * Insert template post.
	 *
	 * @since  1.2.0
	 * @param  array $args Template arguments.
	 * @return int|bool
	 */
	private static function insert_template( $args ) {

		/**
		 * Always require these files!
		 *
		 * In an earlier version, we first checked if `media_handle_sideload`
		 * already existed before including these 3 files. However, if another
		 * plugin or theme included `wp-admin/includes/media.php`, this check
		 * failed to include `wp-admin/includes/image.php` and
		 * `wp-admin/includes/file.php`. This prevented the templates
		 * from being imported correctly, and threw the error
		 * `Call to undefined function wp_read_image_metadata() in /public_html/wp-admin/includes/media.php:462`.
		 *
		 * @since   1.2.0
		 */
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$post_data = array(
			'post_title'  => $args['title'],
			'post_status' => 'publish',
			'post_type'   => 'bm_template',
		);

		$post_id = wp_insert_post( $post_data, true );

		if ( ! is_wp_error( $post_id ) ) {

			// Add HTML.
			$html = $args['html'];
			add_post_meta( $post_id, '_html', $html, true );

			// Set default terms.
			foreach ( $args['tags'] as $tag ) {
				wp_set_object_terms( $post_id, $tag, 'bm_template_tag', true );
			}

			$filename   = $args['thumb_filename'];
			$basename   = basename( $filename );
			$filetype   = wp_check_filetype( basename( $filename ), null );
			$thumb_file = array(
				'name'     => $basename,
				'type'     => $filetype['type'],
				'tmp_name' => $filename,
				'error'    => 0,
				'size'     => filesize( $filename ),
			);

			$attachment_id = media_handle_sideload( $thumb_file, $post_id, $args['thumb_title'] );

			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}

			return $post_id;

		}

		return false;

	}

}

Boldermail_Install::init();
