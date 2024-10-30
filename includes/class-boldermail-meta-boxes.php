<?php
/**
 * Adds meta boxes.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Meta_Boxes class.
 *
 * @since 1.0.0
 */
class Boldermail_Meta_Boxes {

	/**
	 * Initialize the hooks.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function init() {

		self::load_dependencies();

		/**
		 * Add/remove meta boxes for general options + content.
		 *
		 * @since 1.0.0
		 */
		add_action( 'add_meta_boxes', array( __CLASS__, 'remove_meta_boxes' ), PHP_INT_MAX, 2 );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ), PHP_INT_MAX, 2 );

		/**
		 * Save the meta data.
		 *
		 * @since 1.0.0
		 */
		add_action( 'save_post_bm_newsletter', array( 'Boldermail_Meta_Box_Newsletter_Data', 'save' ), 10, 2 );
		add_action( 'save_post_bm_newsletter_rss', array( 'Boldermail_Meta_Box_Newsletter_Data', 'save' ), 10, 2 );
		add_action( 'save_post_bm_newsletter_ares', array( 'Boldermail_Meta_Box_Newsletter_Data', 'save' ), 10, 2 );
		add_action( 'save_post_bm_template', array( 'Boldermail_Meta_Box_Template_HTML', 'save' ), 10, 2 );
		add_action( 'save_post_bm_list', array( 'Boldermail_Meta_Box_List_Settings', 'save' ), 10, 2 );
		add_action( 'save_post_bm_subscriber', array( 'Boldermail_Meta_Box_Subscriber_Data', 'save' ), 10, 2 );
		add_action( 'save_post_bm_autoresponder', array( 'Boldermail_Meta_Box_Autoresponder_Settings', 'save' ), 10, 2 );

		/**
		 * Save the meta data on heartbeat.
		 *
		 * @since 1.7.0
		 */
		add_filter( 'heartbeat_received', array( 'Boldermail_Meta_Box_Newsletter_Data', 'save_on_heartbeat' ), 10, 2 );
		add_filter( 'heartbeat_received', array( 'Boldermail_Meta_Box_Template_HTML', 'save_on_heartbeat' ), 10, 2 );

		/**
		 * Display meta boxes with special context.
		 *
		 * @see   https://wordpress.stackexchange.com/a/221151/85404
		 * @since 1.0.0
		 */
		add_action( 'edit_form_after_title', array( __CLASS__, 'submitbox_meta_box' ), 10, 1 );

	}

	/**
	 * Load required files.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	private static function load_dependencies() {

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/meta-boxes/boldermail-meta-box-functions.php';

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/meta-boxes/class-boldermail-meta-box-submit.php';

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/meta-boxes/class-boldermail-meta-box-newsletter-data.php';

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/meta-boxes/class-boldermail-meta-box-template-html.php';

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/meta-boxes/class-boldermail-list-settings.php';
		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/meta-boxes/class-boldermail-list-information.php';

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/meta-boxes/class-boldermail-subscriber-data.php';

		require_once BOLDERMAIL_PLUGIN_DIR . 'includes/meta-boxes/class-boldermail-autoresponder-settings.php';

	}

	/**
	 * Remove meta boxes.
	 *
	 * @since  1.0.0
	 * @param  string  $post_type Post type.
	 * @param  WP_Post $post      Post object.
	 * @return void
	 */
	public static function remove_meta_boxes( $post_type, $post ) {

		self::remove_all_metaboxes( 'bm_block_template' );

		self::remove_all_metaboxes(
			array(
				'bm_newsletter',
				'bm_newsletter_ares',
			),
			array(
				'authordiv',
				'postcustom',
				'slugdiv',
				'submitdiv',
			)
		);

		self::remove_all_metaboxes(
			'bm_newsletter_rss',
			array(
				'authordiv',
				'postcustom',
				'submitdiv',
			)
		);

		self::remove_all_metaboxes(
			'bm_template',
			array(
				'authordiv',
				'postcustom',
				'submitdiv',
				'postimagediv',
				'tagsdiv-bm_template_tag',
			)
		);

		// Plugins use this hook to display meta boxes after all other boxes have been added.
		if ( in_array( $post_type, array( 'bm_newsletter', 'bm_newsletter_rss', 'bm_newsletter_ares', 'bm_template', 'bm_list', 'bm_subscriber', 'bm_autoresponder' ), true ) ) {
			remove_all_actions( 'do_meta_boxes' );
		}

		$add_submitdiv_newsletter = false;

		$newsletter = boldermail_get_newsletter( $post );

		if ( $newsletter && 'regular' === $newsletter->get_type() && $newsletter->is_published() ) {
			$add_submitdiv_newsletter = true;
		}

		remove_meta_box( 'submitdiv', 'bm_newsletter_rss', 'side' );
		remove_meta_box( 'submitdiv', 'bm_newsletter_ares', 'side' );
		remove_meta_box( 'submitdiv', 'bm_template', 'side' );
		remove_meta_box( 'submitdiv', 'bm_list', 'side' );
		remove_meta_box( 'submitdiv', 'bm_subscriber', 'side' );
		remove_meta_box( 'submitdiv', 'bm_autoresponder', 'side' );

		if ( $add_submitdiv_newsletter ) {
			remove_meta_box( 'submitdiv', 'bm_newsletter', 'side' );
		}

		remove_meta_box( 'slugdiv', 'bm_newsletter_rss', 'normal' );
		remove_meta_box( 'slugdiv', 'bm_template', 'normal' );
		remove_meta_box( 'slugdiv', 'bm_list', 'normal' );
		remove_meta_box( 'slugdiv', 'bm_subscriber', 'normal' );
		remove_meta_box( 'slugdiv', 'bm_autoresponder', 'normal' );

	}

	/**
	 * Add meta boxes.
	 *
	 * @since  1.0.0
	 * @param  string  $post_type Post type.
	 * @param  WP_Post $post      Post object.
	 * @return void
	 */
	public static function add_meta_boxes( $post_type, $post ) {

		if ( $post_type !== $post->post_type ) {
			return;
		}

		add_meta_box(
			'boldermail-newsletter',
			__( 'Setup your Campaign', 'boldermail' ),
			array( 'Boldermail_Meta_Box_Newsletter_Data', 'output' ),
			array( 'bm_newsletter', 'bm_newsletter_rss', 'bm_newsletter_ares' ),
			'normal',
			'high'
		);

		$template = boldermail_get_template( $post );

		if ( $template && true !== $template->use_block_editor() ) {
			add_meta_box(
				'boldermail-template-html',
				__( 'Design', 'boldermail' ),
				array( 'Boldermail_Meta_Box_Template_HTML', 'output' ),
				'bm_template',
				'normal',
				'high'
			);
		}

		add_meta_box(
			'boldermail-list-settings',
			__( 'Settings', 'boldermail' ),
			array( 'Boldermail_Meta_Box_List_Settings', 'output' ),
			'bm_list',
			'normal',
			'high'
		);

		if ( 'publish' === $post->post_status ) {

			add_meta_box(
				'boldermail-list-information',
				__( 'At a Glance', 'boldermail' ),
				array( 'Boldermail_Meta_Box_List_Information', 'output' ),
				'bm_list',
				'side',
				'high'
			);

		}

		add_meta_box(
			'boldermail-subscriber-data',
			__( 'Subscriber Data', 'boldermail' ),
			array( 'Boldermail_Meta_Box_Subscriber_Data', 'output' ),
			'bm_subscriber',
			'normal',
			'high'
		);

		add_meta_box(
			'boldermail-autoresponder-settings',
			__( 'Autoresponder Settings', 'boldermail' ),
			array( 'Boldermail_Meta_Box_Autoresponder_Settings', 'output' ),
			'bm_autoresponder',
			'normal',
			'high'
		);

		add_action( 'boldermail_submitbox_delete_action', array( 'Boldermail_Meta_Box_Submit', 'delete_action' ), 10, 1 );
		add_action( 'boldermail_submitbox_publishing_action', array( 'Boldermail_Meta_Box_Submit', 'publishing_action' ), 10, 1 );

		$add_submitdiv_newsletter = false;

		$newsletter = boldermail_get_newsletter( $post );

		if ( $newsletter && 'regular' === $newsletter->get_type() && $newsletter->is_published() ) {
			$add_submitdiv_newsletter = true;
		}

		add_meta_box(
			'boldermail-submitdiv',
			__( 'Submit', 'boldermail' ),
			array( 'Boldermail_Meta_Box_Submit', 'output' ),
			( $add_submitdiv_newsletter ) ? array( 'bm_newsletter', 'bm_newsletter_rss', 'bm_newsletter_ares', 'bm_template', 'bm_list', 'bm_subscriber', 'bm_autoresponder' ) : array( 'bm_newsletter_rss', 'bm_newsletter_ares', 'bm_template', 'bm_list', 'bm_subscriber', 'bm_autoresponder' ),
			'edit_form_after_title',
			'default'
		);

	}

	/**
	 * Do meta box with the `edit_form_after_title` context.
	 *
	 * @since  1.0.0
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public static function submitbox_meta_box( $post ) {

		do_meta_boxes( get_current_screen(), 'edit_form_after_title', $post );

	}

	/**
	 * Callback to sort data tabs on priority.
	 *
	 * @since  1.0.0
	 * @param  int $a First item.
	 * @param  int $b Second item.
	 * @return bool
	 */
	public static function tabs_sort_priority( $a, $b ) {

		if ( ! isset( $a['priority'], $b['priority'] ) ) {
			return -1;
		}

		if ( $a['priority'] === $b['priority'] ) {
			return 0;
		}

		return $a['priority'] < $b['priority'] ? -1 : 1;

	}

	/**
	 * Remove all meta boxes for a post type.
	 *
	 * @see   https://stackoverflow.com/a/28280501/1991500
	 * @since 2.0.0
	 * @param string|string[] $post_types Post types.
	 * @param string|string[] $exceptions Array of meta boxes exceptions, ones that should not be removed.
	 */
	public static function remove_all_metaboxes( $post_types, $exceptions = array() ) {

		global $post;

		if ( ! in_array( $post->post_type, (array) $post_types, true ) ) {
			return;
		}

		$exceptions = (array) $exceptions;

		global $wp_meta_boxes;

		if ( ! empty( $wp_meta_boxes ) ) {

			// Loop through each page key of the `$wp_meta_boxes` global.
			foreach ( $wp_meta_boxes as $page => $page_boxes ) {

				if ( ! empty( $page_boxes ) ) {

					// Loop through each context.
					foreach ( $page_boxes as $context => $box_context ) {

						if ( ! empty( $box_context ) ) {

							// Loop through each type of meta box.
							foreach ( $box_context as $box_type ) {

								if ( ! empty( $box_type ) ) {

									// Loop through each individual box.
									foreach ( $box_type as $id => $box ) {

										// Check to see if the meta box should be removed.
										if ( ! in_array( $id, $exceptions, true ) ) {

											// Remove the meta box.
											remove_meta_box( $id, $page, $context );

											// Unset the meta box as well to prevent PHP runtime warnings.
											foreach ( array( 'high', 'core', 'default', 'low' ) as $priority ) {
												unset( $wp_meta_boxes[ $page ][ $context ][ $priority ][ $id ] );
											}

										}

									}

								}

							}

						}

					}

				}

			}

		}

	}

}

Boldermail_Meta_Boxes::init();
