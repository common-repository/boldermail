<?php
/**
 * Register custom post types.
 *
 * @link       https://www.boldermail.com/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

/**
 * Boldermail_Post_Types class.
 *
 * @since 1.0.0
 */
class Boldermail_Post_Types {

	/**
	 * Initialize the hooks.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function init() {

		/**
		 * Register posts types.
		 *
		 * @since 1.0.0
		 */
		add_action( 'init', array( __CLASS__, 'register_post_types' ) );
		add_action( 'init', array( __CLASS__, 'register_post_statuses' ) );
		add_action( 'boldermail_after_register_post_type', array( __CLASS__, 'maybe_flush_rewrite_rules' ) );
		add_action( 'admin_print_scripts', array( __CLASS__, 'disable_autosave' ) );

		/**
		 * Jetpack settings.
		 *
		 * @since 1.0.0
		 */
		add_filter( 'sharing_meta_box_show', array( __CLASS__, 'sharing_meta_box_show' ), 10, 2 );

		/**
		 * Modify title.
		 *
		 * @since 1.0.0
		 */
		add_filter( 'enter_title_here', array( __CLASS__, 'enter_title_here' ), 10, 2 );

		/**
		 * Setup admin list tables.
		 *
		 * @since 1.3.0
		 */
		add_action( 'current_screen', array( __CLASS__, 'setup_screen' ) );

		/**
		 * Add body classes to posts.
		 *
		 * @since 1.7.0
		 */
		add_filter( 'admin_body_class', array( __CLASS__, 'admin_body_class' ) );

	}

	/**
	 * Register all Boldermail post types.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function register_post_types() {

		if ( ! is_blog_installed() || post_type_exists( 'bm_newsletter' ) ) {
			return;
		}

		do_action( 'boldermail_before_register_post_type' );

		$newsletter_args = apply_filters(
			'bm_newsletter_post_type_args',
			array(
				'labels'             => array(
					'name'                  => __( 'Newsletters', 'boldermail' ),
					'singular_name'         => __( 'Newsletter', 'boldermail' ),
					'add_new'               => __( 'Add New', 'boldermail' ),
					'add_new_item'          => __( 'Add New Newsletter', 'boldermail' ),
					'edit_item'             => __( 'Edit Newsletter', 'boldermail' ),
					'new_item'              => __( 'New Newsletter', 'boldermail' ),
					'view_item'             => __( 'View Newsletter', 'boldermail' ),
					'view_items'            => __( 'View Newsletters', 'boldermail' ),
					'search_items'          => __( 'Search Newsletters', 'boldermail' ),
					'not_found'             => __( 'No newsletters found', 'boldermail' ),
					'not_found_in_trash'    => __( 'No newsletters found in Trash', 'boldermail' ),
					'all_items'             => __( 'Newsletters', 'boldermail' ),
					'archive'               => __( 'Newsletter Archives', 'boldermail' ),
					'attributes'            => __( 'Newsletter Attributes', 'boldermail' ),
					'insert_into_item'      => __( 'Insert into newsletter', 'boldermail' ),
					'uploaded_to_this_item' => __( 'Uploaded to this newsletter', 'boldermail' ),
				),
				'description'        => __( 'This is where you can create newsletters for your mailing lists.', 'boldermail' ),
				'public'             => false,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_admin_bar'  => true,
				'menu_position'      => 25,
				'menu_icon'          => 'dashicons-email-alt',
				'capability_type'    => 'post',
				'map_meta_cap'       => true,
				'supports'           => array(
					'title',
					'author',
					'custom-fields',
				),
				'rewrite'            => array(
					'slug'       => 'newsletter',
					'with_front' => false,
					'pages'      => true,
					'feeds'      => false,
					'ep_mask'    => EP_PERMALINK,
				),
				'query_var'          => 'newsletter',
				'show_in_rest'       => true,
			)
		);

		$newsletter_rss_feed_args = apply_filters(
			'bm_newsletter_rss_feed_post_type_args',
			array(
				'labels'            => array(
					'name'                  => __( 'RSS Campaigns', 'boldermail' ),
					'singular_name'         => __( 'RSS Campaign', 'boldermail' ),
					'add_new'               => __( 'Add New', 'boldermail' ),
					'add_new_item'          => __( 'Add New RSS Campaign', 'boldermail' ),
					'edit_item'             => __( 'Edit RSS Campaign', 'boldermail' ),
					'new_item'              => __( 'New RSS Campaign', 'boldermail' ),
					'view_item'             => __( 'View RSS Campaign', 'boldermail' ),
					'view_items'            => __( 'View RSS Campaigns', 'boldermail' ),
					'search_items'          => __( 'Search RSS Campaigns', 'boldermail' ),
					'not_found'             => __( 'No RSS campaigns found', 'boldermail' ),
					'not_found_in_trash'    => __( 'No RSS campaigns found in Trash', 'boldermail' ),
					'all_items'             => __( 'RSS Campaigns', 'boldermail' ),
					'archive'               => __( 'RSS Campaign Archives', 'boldermail' ),
					'attributes'            => __( 'RSS Campaign Attributes', 'boldermail' ),
					'insert_into_item'      => __( 'Insert into RSS campaign', 'boldermail' ),
					'uploaded_to_this_item' => __( 'Uploaded to this RSS campaign', 'boldermail' ),
				),
				'description'       => __( 'This is where you can create RSS campaigns for your mailing lists.', 'boldermail' ),
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => 'edit.php?post_type=bm_newsletter',
				'show_in_admin_bar' => false,
				'capability_type'   => 'post',
				'map_meta_cap'      => true,
				'supports'          => array(
					'title',
					'author',
					'custom-fields',
				),
				'rewrite'           => false,
				'query_var'         => false,
				'can_export'        => true,
			)
		);

		$newsletter_autoresponder_args = apply_filters(
			'bm_newsletter_autoresponder_post_type_args',
			array(
				'labels'             => array(
					'name'                  => __( 'Automated Emails', 'boldermail' ),
					'singular_name'         => __( 'Automated Email', 'boldermail' ),
					'add_new'               => __( 'Add New', 'boldermail' ),
					'add_new_item'          => __( 'Add New Automated Email', 'boldermail' ),
					'edit_item'             => __( 'Edit Automated Email', 'boldermail' ),
					'new_item'              => __( 'New Automated Email', 'boldermail' ),
					'view_item'             => __( 'View Automated Email', 'boldermail' ),
					'view_items'            => __( 'View Automated Emails', 'boldermail' ),
					'search_items'          => __( 'Search Automated Emails', 'boldermail' ),
					'not_found'             => __( 'No automated emails found', 'boldermail' ),
					'not_found_in_trash'    => __( 'No automated emails found in Trash', 'boldermail' ),
					'all_items'             => __( 'Automated Emails', 'boldermail' ),
					'archive'               => __( 'Automated Email Archives', 'boldermail' ),
					'attributes'            => __( 'Automated Email Attributes', 'boldermail' ),
					'insert_into_item'      => __( 'Insert into automated email', 'boldermail' ),
					'uploaded_to_this_item' => __( 'Uploaded to this automated email', 'boldermail' ),
				),
				'description'        => __( 'This is where you can create automated emails for your mailing lists.', 'boldermail' ),
				'public'             => false,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => false,
				'show_in_admin_bar'  => false,
				'capability_type'    => 'post',
				'map_meta_cap'       => true,
				'supports'           => array(
					'title',
					'author',
					'custom-fields',
				),
				'rewrite'            => array(
					'slug'       => 'newsletter_ares',
					'with_front' => false,
					'pages'      => true,
					'feeds'      => false,
					'ep_mask'    => EP_PERMALINK,
				),
				'query_var'          => 'newsletter_ares',
				'show_in_rest'       => true,
			)
		);

		$template_args = apply_filters(
			'bm_template_post_type_args',
			array(
				'labels'             => array(
					'name'                  => __( 'Templates', 'boldermail' ),
					'singular_name'         => __( 'Template', 'boldermail' ),
					'add_new'               => __( 'Add New', 'boldermail' ),
					'add_new_item'          => __( 'Add New Template', 'boldermail' ),
					'edit_item'             => __( 'Edit Template', 'boldermail' ),
					'new_item'              => __( 'New Template', 'boldermail' ),
					'view_item'             => __( 'View Template', 'boldermail' ),
					'view_items'            => __( 'View Templates', 'boldermail' ),
					'search_items'          => __( 'Search Templates', 'boldermail' ),
					'not_found'             => __( 'No templates found', 'boldermail' ),
					'not_found_in_trash'    => __( 'No templates found in Trash', 'boldermail' ),
					'all_items'             => __( 'Templates', 'boldermail' ),
					'archive'               => __( 'Template Archives', 'boldermail' ),
					'attributes'            => __( 'Template Attributes', 'boldermail' ),
					'insert_into_item'      => __( 'Insert into template', 'boldermail' ),
					'uploaded_to_this_item' => __( 'Uploaded to this template', 'boldermail' ),
					'featured_image'        => __( 'Thumbnail', 'boldermail' ),
					'set_featured_image'    => __( 'Set thumbnail', 'boldermail' ),
					'remove_featured_image' => __( 'Remove thumbnail', 'boldermail' ),
					'use_featured_image'    => __( 'Use as thumbnail', 'boldermail' ),
					'filter_items_list'     => __( 'Filter templates list', 'boldermail' ),
				),
				'description'        => __( 'This is where you can edit your HTML templates for your newsletters.', 'boldermail' ),
				'public'             => false,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => 'edit.php?post_type=bm_newsletter',
				'show_in_admin_bar'  => false,
				'capability_type'    => 'post',
				'map_meta_cap'       => true,
				'supports'           => array(
					'title',
					'author',
					'thumbnail',
					'editor',
					'custom-fields',
					'revisions',
				),
				'rewrite'            => false,
				'query_var'          => false,
				'show_in_rest'       => true,
				'can_export'         => true,
			)
		);

		$list_args = apply_filters(
			'bm_list_post_type_args',
			array(
				'labels'            => array(
					'name'                  => __( 'Lists', 'boldermail' ),
					'singular_name'         => __( 'List', 'boldermail' ),
					'add_new'               => __( 'Add New', 'boldermail' ),
					'add_new_item'          => __( 'Add New List', 'boldermail' ),
					'edit_item'             => __( 'Edit List', 'boldermail' ),
					'new_item'              => __( 'New List', 'boldermail' ),
					'view_item'             => __( 'View List', 'boldermail' ),
					'view_items'            => __( 'View Lists', 'boldermail' ),
					'search_items'          => __( 'Search Lists', 'boldermail' ),
					'not_found'             => __( 'No lists found', 'boldermail' ),
					'not_found_in_trash'    => __( 'No lists found in Trash', 'boldermail' ),
					'all_items'             => __( 'Lists', 'boldermail' ),
					'archive'               => __( 'List Archives', 'boldermail' ),
					'attributes'            => __( 'List Attributes', 'boldermail' ),
					'insert_into_item'      => __( 'Insert into list', 'boldermail' ),
					'uploaded_to_this_item' => __( 'Uploaded to this list', 'boldermail' ),
				),
				'description'       => __( 'This is where you can manage your subscription lists.', 'boldermail' ),
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => 'edit.php?post_type=bm_newsletter',
				'show_in_admin_bar' => false,
				'capability_type'   => 'post',
				'map_meta_cap'      => true,
				'supports'          => array(
					'title',
					'custom-fields',
				),
				'rewrite'           => false,
				'query_var'         => false,
				'can_export'        => true,
			)
		);

		$subscriber_args = apply_filters(
			'bm_subscriber_post_type_args',
			array(
				'labels'            => array(
					'name'                  => __( 'Subscribers', 'boldermail' ),
					'singular_name'         => __( 'Subscriber', 'boldermail' ),
					'add_new'               => __( 'Add New', 'boldermail' ),
					'add_new_item'          => __( 'Add New Subscriber', 'boldermail' ),
					'edit_item'             => __( 'Edit Subscriber', 'boldermail' ),
					'new_item'              => __( 'New Subscriber', 'boldermail' ),
					'view_item'             => __( 'View Subscriber', 'boldermail' ),
					'view_items'            => __( 'View Subscribers', 'boldermail' ),
					'search_items'          => __( 'Search Subscribers', 'boldermail' ),
					'not_found'             => __( 'No subscribers found', 'boldermail' ),
					'not_found_in_trash'    => __( 'No subscribers found in Trash', 'boldermail' ),
					'all_items'             => __( 'Subscribers', 'boldermail' ),
					'archive'               => __( 'Subscriber Archives', 'boldermail' ),
					'attributes'            => __( 'Subscriber Attributes', 'boldermail' ),
					'insert_into_item'      => __( 'Insert into subscriber', 'boldermail' ),
					'uploaded_to_this_item' => __( 'Uploaded to this subscriber', 'boldermail' ),
				),
				'description'       => __( 'This is where you can manage your subscribers.', 'boldermail' ),
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => 'edit.php?post_type=bm_newsletter',
				'show_in_admin_bar' => false,
				'capability_type'   => 'post',
				'map_meta_cap'      => true,
				'supports'          => array(
					'custom-fields',
				),
				'rewrite'           => false,
				'query_var'         => false,
				'can_export'        => true,
			)
		);

		$autoresponder_args = apply_filters(
			'bm_autoresponder_post_type_args',
			array(
				'labels'            => array(
					'name'                  => __( 'Automations', 'boldermail' ),
					'singular_name'         => __( 'Automation', 'boldermail' ),
					'add_new'               => __( 'Add New', 'boldermail' ),
					'add_new_item'          => __( 'Add New Automation', 'boldermail' ),
					'edit_item'             => __( 'Edit Automation', 'boldermail' ),
					'new_item'              => __( 'New Automation', 'boldermail' ),
					'view_item'             => __( 'View Automation', 'boldermail' ),
					'view_items'            => __( 'View Automations', 'boldermail' ),
					'search_items'          => __( 'Search Automations', 'boldermail' ),
					'not_found'             => __( 'No automations found', 'boldermail' ),
					'not_found_in_trash'    => __( 'No automations found in Trash', 'boldermail' ),
					'all_items'             => __( 'Automations', 'boldermail' ),
					'archive'               => __( 'Automation Archives', 'boldermail' ),
					'attributes'            => __( 'Automation Attributes', 'boldermail' ),
					'insert_into_item'      => __( 'Insert into automation', 'boldermail' ),
					'uploaded_to_this_item' => __( 'Uploaded to this automation', 'boldermail' ),
				),
				'description'       => __( 'This is where you can manage your automations.', 'boldermail' ),
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => 'edit.php?post_type=bm_newsletter',
				'show_in_admin_bar' => false,
				'capability_type'   => 'post',
				'map_meta_cap'      => true,
				'supports'          => array(
					'title',
					'custom-fields',
				),
				'rewrite'           => false,
				'query_var'         => false,
				'can_export'        => true,
			)
		);

		$block_template_args = apply_filters(
			'bm_block_template_post_type_args',
			array(
				'labels'             => array(
					'name'                  => __( 'Block Templates', 'boldermail' ),
					'singular_name'         => __( 'Block Template', 'boldermail' ),
					'add_new'               => __( 'Add New', 'boldermail' ),
					'add_new_item'          => __( 'Add New Template', 'boldermail' ),
					'edit_item'             => __( 'Edit Block Template', 'boldermail' ),
					'new_item'              => __( 'New Block Template', 'boldermail' ),
					'view_item'             => __( 'View Block Template', 'boldermail' ),
					'view_items'            => __( 'View Block Templates', 'boldermail' ),
					'search_items'          => __( 'Search Block Templates', 'boldermail' ),
					'not_found'             => __( 'No block templates found', 'boldermail' ),
					'not_found_in_trash'    => __( 'No block templates found in Trash', 'boldermail' ),
					'all_items'             => __( 'Block Templates', 'boldermail' ),
					'archive'               => __( 'Block Template Archives', 'boldermail' ),
					'attributes'            => __( 'Block Template Attributes', 'boldermail' ),
					'insert_into_item'      => __( 'Insert into block template', 'boldermail' ),
					'uploaded_to_this_item' => __( 'Uploaded to this block template', 'boldermail' ),
					'featured_image'        => __( 'Thumbnail', 'boldermail' ),
					'set_featured_image'    => __( 'Set thumbnail', 'boldermail' ),
					'remove_featured_image' => __( 'Remove thumbnail', 'boldermail' ),
					'use_featured_image'    => __( 'Use as thumbnail', 'boldermail' ),
				),
				'description'        => __( 'This is where you can edit your HTML templates for your newsletters.', 'boldermail' ),
				'public'             => false,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => false,
				'capability_type'    => 'post',
				'map_meta_cap'       => true,
				'supports'           => array(
					'editor',
					'custom-fields',
					'revisions',
				),
				'rewrite'            => false,
				'query_var'          => false,
				'can_export'         => true,
				'show_in_rest'       => true,
				'template'           => array(
					array( 'boldermail/template' ),
				),
			)
		);

		register_post_type( 'bm_newsletter', $newsletter_args );
		register_post_type( 'bm_newsletter_rss', $newsletter_rss_feed_args );
		register_post_type( 'bm_newsletter_ares', $newsletter_autoresponder_args );
		register_post_type( 'bm_template', $template_args );
		register_post_type( 'bm_list', $list_args );
		register_post_type( 'bm_subscriber', $subscriber_args );
		register_post_type( 'bm_autoresponder', $autoresponder_args );
		register_post_type( 'bm_block_template', $block_template_args );

		do_action( 'boldermail_after_register_post_type' );

	}

	/**
	 * Register all post statuses.
	 *
	 * @since  1.3.0
	 * @return void
	 */
	public static function register_post_statuses() {

		$newsletter_statuses = apply_filters(
			'boldermail_register_newsletter_post_statuses',
			array(
				'sent'      => array(
					'label'                     => _x( 'Sent', 'Newsletter status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: number of newsletters */
					'label_count'               => _n_noop( 'Sent <span class="count">(%s)</span>', 'Sent <span class="count">(%s)</span>', 'boldermail' ),
				),
				'sending'   => array(
					'label'                     => _x( 'Sending', 'Newsletter status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: number of newsletters */
					'label_count'               => _n_noop( 'Sending <span class="count">(%s)</span>', 'Sending <span class="count">(%s)</span>', 'boldermail' ),
				),
				'preparing' => array(
					'label'                     => _x( 'Preparing', 'Newsletter status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: number of newsletters */
					'label_count'               => _n_noop( 'Preparing <span class="count">(%s)</span>', 'Preparing <span class="count">(%s)</span>', 'boldermail' ),
				),
				'enabled'   => array(
					'label'                     => _x( 'Enabled', 'Newsletter status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: number of newsletters */
					'label_count'               => _n_noop( 'Enabled <span class="count">(%s)</span>', 'Enabled <span class="count">(%s)</span>', 'boldermail' ),
				),
				'paused'    => array(
					'label'                     => _x( 'Paused', 'Newsletter status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: number of newsletters */
					'label_count'               => _n_noop( 'Paused <span class="count">(%s)</span>', 'Paused <span class="count">(%s)</span>', 'boldermail' ),
				),
			)
		);

		foreach ( $newsletter_statuses as $newsletter_status => $values ) {
			register_post_status( $newsletter_status, $values );
		}

		$subscriber_statuses = apply_filters(
			'boldermail_register_subscriber_post_statuses',
			array(
				'subscribed'   => array(
					'label'                     => _x( 'Subscribed', 'Subscriber status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: Number of subscribers. */
					'label_count'               => _n_noop( 'Subscribed <span class="count">(%s)</span>', 'Subscribed <span class="count">(%s)</span>', 'boldermail' ),
				),
				'unsubscribed' => array(
					'label'                     => _x( 'Unsubscribed', 'Subscriber status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: Number of subscribers. */
					'label_count'               => _n_noop( 'Unsubscribed <span class="count">(%s)</span>', 'Unsubscribed <span class="count">(%s)</span>', 'boldermail' ),
				),
				'unconfirmed'  => array(
					'label'                     => _x( 'Unconfirmed', 'Subscriber status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: Number of subscribers. */
					'label_count'               => _n_noop( 'Unconfirmed <span class="count">(%s)</span>', 'Unconfirmed <span class="count">(%s)</span>', 'boldermail' ),
				),
				'bounced'      => array(
					'label'                     => _x( 'Bounced', 'Subscriber status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: Number of subscribers. */
					'label_count'               => _n_noop( 'Bounced <span class="count">(%s)</span>', 'Bounced <span class="count">(%s)</span>', 'boldermail' ),
				),
				'complained'   => array(
					'label'                     => _x( 'Marked as Spam', 'Subscriber status', 'boldermail' ),
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					/* translators: %s: Number of subscribers. */
					'label_count'               => _n_noop( 'Marked as Spam <span class="count">(%s)</span>', 'Marked as Spam <span class="count">(%s)</span>', 'boldermail' ),
				),
			)
		);

		foreach ( $subscriber_statuses as $subscriber_status => $values ) {
			register_post_status( $subscriber_status, $values );
		}

	}

	/**
	 * Flush rules if the event is queued.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function maybe_flush_rewrite_rules() {

		if ( 'yes' === get_option( 'boldermail_queue_flush_rewrite_rules' ) ) {
			update_option( 'boldermail_queue_flush_rewrite_rules', 'no' );
			flush_rewrite_rules();
		}

	}

	/**
	 * Filter whether to display the Sharing Meta Box or not.
	 *
	 * @since  1.2.5
	 * @param  bool    $show Display Sharing Meta Box.
	 * @param  WP_Post $post Post.
	 * @return bool
	 */
	public static function sharing_meta_box_show( $show, $post ) {

		if ( in_array( $post->post_type, array( 'bm_newsletter', 'bm_newsletter_ares' ), true ) ) {
			return false;
		}

		return $show;

	}

	/**
	 * Disable the auto-save functionality for specific post types.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function disable_autosave() {

		global $post;

		// Do not include `bm_template` nor `bm_newsletter` nor `bm_newsletter_rss` nor `bm_newsletter_ares`.
		if ( $post && in_array( get_post_type( $post->ID ), array( 'bm_list', 'bm_subscriber' ), true ) ) {
			wp_dequeue_script( 'autosave' );
		}

	}

	/**
	 * Filters the title field placeholder text.
	 *
	 * @since  1.7.0
	 * @param  string  $text Placeholder text. Default "Enter title here".
	 * @param  WP_Post $post Post object.
	 * @return string
	 */
	public static function enter_title_here( $text, $post ) {

		switch ( $post->post_type ) {

			case 'bm_template':
				$text = esc_html__( 'Enter your template name here', 'boldermail' );
				break;

			case 'bm_list':
				$text = esc_html__( 'Enter your list name here', 'boldermail' );
				break;

			case 'bm_autoresponder':
				$text = esc_html__( 'Enter your autoresponder name here', 'boldermail' );
				break;

		}

		return $text;

	}

	/**
	 * Filter the CSS classes for the body tag in the admin.
	 *
	 * @since  1.7.0
	 * @param  string $classes Space-separated list of CSS classes.
	 * @return string
	 */
	public static function admin_body_class( $classes ) {

		global $post;

		$screen = get_current_screen();

		// If not Boldermail page...
		if ( ! in_array( $screen->id, boldermail_get_screen_ids(), true ) ) {
			return $classes;
		}

		// Add post status.
		if ( $post && $post->post_status ) {
			$classes .= " post-status-{$post->post_status} ";
		}

		return $classes;

	}

	/**
	 * Looks at the current screen and loads the correct list table handler.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function setup_screen() {

		$screen_id = false;

		if ( function_exists( 'get_current_screen' ) ) {
			$screen    = get_current_screen();
			$screen_id = isset( $screen, $screen->id ) ? $screen->id : '';
		}

		switch ( $screen_id ) {

			case 'edit-bm_newsletter':
				include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-regular-list-table.php';
				new Boldermail_Newsletters_Regular_List_Table();
				break;

			case 'edit-bm_newsletter_rss':
				include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-rss-feed-list-table.php';
				new Boldermail_Newsletters_RSS_Feed_List_Table();
				break;

			case 'edit-bm_newsletter_ares':
				include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-autoresponder-list-table.php';
				new Boldermail_Newsletters_Autoresponder_List_Table();
				break;

			case 'edit-bm_list':
				include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-lists-list-table.php';
				new Boldermail_Lists_List_Table();
				break;

			case 'edit-bm_autoresponder':
				include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-autoresponders-list-table.php';
				new Boldermail_Autoresponders_List_Table();
				break;

			case 'edit-bm_subscriber':
				include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-subscribers-list-table.php';
				new Boldermail_Subscribers_List_Table();
				break;

			case 'edit-bm_template':
				include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-templates-list-table.php';
				new Boldermail_Templates_List_Table();
				break;

		}

	}

}

Boldermail_Post_Types::init();
