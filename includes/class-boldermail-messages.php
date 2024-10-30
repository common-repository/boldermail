<?php
/**
 * Messages and notices.
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
 * Boldermail_Messages class.
 *
 * @since 1.0.0
 */
class Boldermail_Messages {

	/**
	 * Initialize the hooks.
	 *
	 * @since 1.0.0
	 */
	public static function init() {

		/**
		 * Modify text in the `submitdiv` meta box.
		 *
		 * @since 1.0.0
		 */
		add_filter( 'gettext', array( __CLASS__, 'submitdiv_gettext' ), 10, 3 );
		add_filter( 'gettext_with_context', array( __CLASS__, 'submitdiv_gettext_with_context' ), 10, 4 );

		/**
		 * Filter the post updated messages.
		 *
		 * @since 1.0.0
		 */
		add_filter( 'post_updated_messages', array( __CLASS__, 'post_updated_messages' ) );

		/**
		 * Filter the bulk updated messages.
		 *
		 * @since 2.0.0
		 */
		add_filter( 'bulk_post_updated_messages', array( __CLASS__, 'bulk_post_updated_messages' ), 10, 2 );

		/**
		 * Handle admin notices.
		 *
		 * @since 1.0.0
		 */
		add_action( 'in_admin_header', array( __CLASS__, 'handle_admin_notices' ), PHP_INT_MAX );

	}

	/**
	 * Modify the text in the Publish meta box.
	 *
	 * @since  1.0.0
	 * @param  string $translated_text Translated text.
	 * @param  string $text            Text to translate.
	 * @param  string $textdomain      Text domain.
	 * @return string
	 */
	public static function submitdiv_gettext( $translated_text, $text, $textdomain ) {

		if ( ! is_admin() || ( 'default' !== $textdomain && 'boldermail' !== $textdomain ) ) {
			return $translated_text;
		}

		if ( boldermail_get_current_screen_post_type() === 'bm_newsletter' ) {

			// /wp-admin/includes/meta-boxes.php:41
			if ( 'Save Draft' === $text ) {
				$translated_text = __( 'Save Changes', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:91
			if ( 'Published' === $text ) {
				$translated_text = __( 'Newsletter sent', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:188
			if ( 'Published on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Newsletter sent on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:193
			if ( 'Schedule for: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Schedule newsletter for: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:190,200
			if ( 'Publish <b>immediately</b>' === $text ) {
				$translated_text = __( 'Send newsletter <b>immediately</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:196
			if ( 'Publish on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Send newsletter on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:296
			if ( 'Publish' === $text ) {
				$translated_text = __( 'Send Newsletter', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:300
			if ( 'Move to Trash' === $text ) {
				$translated_text = __( 'Delete', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:304
			if ( 'Update' === $text ) {
				$translated_text = __( 'Save Changes', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1432
			if ( 'Schedule for:' === $text ) {
				$translated_text = __( 'Schedule newsletter for:', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1433
			if ( 'Published on:' === $text ) {
				$translated_text = __( 'Newsletter sent on:', 'boldermail' );
			}

			// /wp-admin/includes/class-wp-posts-list-table.php:1401
			if ( 'View' === $text ) {
				$translated_text = __( 'Preview', 'boldermail' );
			}

		}

		if ( boldermail_get_current_screen_post_type() === 'bm_newsletter_rss' ) {

			// /wp-admin/includes/meta-boxes.php:41
			if ( 'Save Draft' === $text ) {
				$translated_text = __( 'Save Changes', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:91
			if ( 'Published' === $text ) {
				$translated_text = __( 'Campaign created', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:188
			if ( 'Published on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Campaign started on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:193
			if ( 'Schedule for: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Start campaign on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:190,200
			if ( 'Publish <b>immediately</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Start campaign <b>immediately</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:196
			if ( 'Publish on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Start campaign on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:296
			if ( 'Publish' === $text ) {
				$translated_text = __( 'Start Campaign', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:300
			if ( 'Move to Trash' === $text ) {
				$translated_text = __( 'Delete', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:304
			if ( 'Update' === $text ) {
				$translated_text = __( 'Save Changes', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1432
			if ( 'Schedule for:' === $text ) {
				$translated_text = __( 'Start campaign on:', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1433
			if ( 'Published on:' === $text ) {
				$translated_text = __( 'Campaign started on:', 'boldermail' );
			}

		}

		if ( boldermail_get_current_screen_post_type() === 'bm_newsletter_ares' ) {

			// /wp-admin/includes/meta-boxes.php:41
			if ( 'Save Draft' === $text ) {
				$translated_text = __( 'Save Changes', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:91
			if ( 'Published' === $text ) {
				$translated_text = __( 'Automation added', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:188
			if ( 'Published on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Automated email added on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:193
			if ( 'Schedule for: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Schedule automated email for: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:190,200
			if ( 'Publish <b>immediately</b>' === $text ) {
				$translated_text = __( 'Add automated email <b>immediately</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:196
			if ( 'Publish on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Add automated email on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:296
			if ( 'Publish' === $text ) {
				$translated_text = __( 'Add Automated Email', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:300
			if ( 'Move to Trash' === $text ) {
				$translated_text = __( 'Delete', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:304
			if ( 'Update' === $text ) {
				$translated_text = __( 'Save Changes', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1432
			if ( 'Schedule for:' === $text ) {
				$translated_text = __( 'Schedule automated email for:', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1433
			if ( 'Published on:' === $text ) {
				$translated_text = __( 'Automated email added on:', 'boldermail' );
			}

			// /wp-admin/includes/class-wp-posts-list-table.php:1401
			if ( 'View' === $text ) {
				$translated_text = __( 'Preview', 'boldermail' );
			}

		}

		if ( boldermail_get_current_screen_post_type() === 'bm_list' ) {

			// /wp-admin/includes/meta-boxes.php:41
			if ( 'Save Draft' === $text ) {
				$translated_text = __( 'Save Changes', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:91
			if ( 'Published' === $text ) {
				$translated_text = __( 'List created', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:188
			if ( 'Published on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'List created on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:193
			if ( 'Schedule for: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Schedule list for: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:190,200
			if ( 'Publish <b>immediately</b>' === $text ) {
				$translated_text = __( 'Create list <b>immediately</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:196
			if ( 'Publish on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Create list on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:296
			if ( 'Publish' === $text ) {
				$translated_text = __( 'Create List', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:304
			if ( 'Update' === $text ) {
				$translated_text = __( 'Save Changes', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1432
			if ( 'Schedule for:' === $text ) {
				$translated_text = __( 'Schedule list for:', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1433
			if ( 'Published on:' === $text ) {
				$translated_text = __( 'List created on:', 'boldermail' );
			}

		}

		if ( boldermail_get_current_screen_post_type() === 'bm_subscriber' ) {

			// /wp-admin/includes/meta-boxes.php:91
			if ( 'Published' === $text ) {
				$translated_text = __( 'Added', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:188
			if ( 'Published on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Subscribed on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:296
			if ( 'Publish' === $text ) {
				$translated_text = __( 'Add Subscriber', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:300
			if ( 'Move to Trash' === $text ) {
				$translated_text = __( 'Delete Subscriber', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:304
			if ( 'Update' === $text ) {
				$translated_text = __( 'Update Subscriber', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1433
			if ( 'Published on:' === $text ) {
				$translated_text = __( 'Subscribed on:', 'boldermail' );
			}

		}

		if ( boldermail_get_current_screen_post_type() === 'bm_autoresponder' ) {

			// /wp-admin/includes/meta-boxes.php:91
			if ( 'Published' === $text ) {
				$translated_text = __( 'Added', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:188
			if ( 'Published on: <b>%1$s</b>' === $text ) {
				/* translators: %s: Date. */
				$translated_text = __( 'Added on: <b>%1$s</b>', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:296
			if ( 'Publish' === $text ) {
				$translated_text = __( 'Add Autoresponder', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:300
			if ( 'Move to Trash' === $text ) {
				$translated_text = __( 'Delete Autoresponder', 'boldermail' );
			}

			// /wp-admin/includes/meta-boxes.php:304
			if ( 'Update' === $text ) {
				$translated_text = __( 'Update Autoresponder', 'boldermail' );
			}

			// /wp-includes/script-loader.php:1433
			if ( 'Published on:' === $text ) {
				$translated_text = __( 'Added on:', 'boldermail' );
			}

		}

		if ( boldermail_get_current_screen_post_type() === 'bm_template' ) {

			// /wp-admin/includes/meta-boxes.php:300
			if ( 'Move to Trash' === $text ) {
				$translated_text = __( 'Delete Template', 'boldermail' );
			}

		}

		return $translated_text;

	}

	/**
	 * Modify the text in the admin area.
	 *
	 * @since  1.0.0
	 * @param  string $translated_text Translated text.
	 * @param  string $text            Text to translate.
	 * @param  string $context         Context information for the translators.
	 * @param  string $textdomain      Text domain.
	 * @return string
	 */
	public static function submitdiv_gettext_with_context( $translated_text, $text, $context, $textdomain ) {

		if ( ! is_admin() || ( 'default' !== $textdomain && 'boldermail' !== $textdomain ) ) {
			return $translated_text;
		}

		if ( in_array( boldermail_get_current_screen_post_type(), array( 'bm_newsletter', 'bm_newsletter_rss', 'bm_newsletter_ares', 'bm_template', 'bm_subscriber', 'bm_autoresponder' ), true ) ) {

			// /wp-admin/includes/class-wp-posts-list-table.php:1369
			if ( 'Trash' === $text && 'verb' === $context ) {
				$translated_text = __( 'Delete', 'boldermail' );
			}

		}

		return $translated_text;

	}

	/**
	 * Filter the post updated messages.
	 *
	 * @since  1.0.0
	 * @param  array $messages Post updated messages.
	 * @return array
	 */
	public static function post_updated_messages( $messages ) {

		/* phpcs:disable WordPress.WP.I18n.MissingTranslatorsComment */

		global $post;

		$scheduled_date = date_i18n( get_option( 'date_format' ), strtotime( $post->post_date ) );

		$preview_newsletter_link_html = sprintf( ' <a href="%1$s">%2$s</a>', esc_url( get_permalink( $post->ID ) ), __( 'Preview newsletter', 'boldermail' ) );

		$scheduled_newsletter_link_html = sprintf( ' <a href="%1$s">%2$s</a>', esc_url( get_permalink( $post->ID ) ), __( 'Preview newsletter', 'boldermail' ) );

		$messages['bm_newsletter']     = $messages['post'];
		$messages['bm_newsletter'][1]  = __( 'Newsletter updated.', 'boldermail' );
		$messages['bm_newsletter'][4]  = __( 'Newsletter updated.', 'boldermail' );
		$messages['bm_newsletter'][5]  = isset( $_GET['revision'] ) ? sprintf( __( 'Newsletter restored to revision from %s.', 'boldermail' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		$messages['bm_newsletter'][6]  = __( 'Newsletter created and preparing to send.', 'boldermail' );
		$messages['bm_newsletter'][7]  = __( 'Newsletter saved.', 'boldermail' );
		$messages['bm_newsletter'][8]  = __( 'Newsletter submitted.', 'boldermail' ) . $preview_newsletter_link_html;
		$messages['bm_newsletter'][9]  = sprintf( __( 'Newsletter scheduled for: %s.', 'boldermail' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_newsletter_link_html;
		$messages['bm_newsletter'][10] = __( 'Newsletter draft updated.', 'boldermail' ) . $preview_newsletter_link_html;

		$messages['bm_newsletter_rss']     = $messages['post'];
		$messages['bm_newsletter_rss'][1]  = __( 'Campaign updated.', 'boldermail' );
		$messages['bm_newsletter_rss'][4]  = $messages['bm_newsletter_rss'][1];
		$messages['bm_newsletter_rss'][5]  = isset( $_GET['revision'] ) ? sprintf( __( 'Campaign restored to revision from %s.', 'boldermail' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		$messages['bm_newsletter_rss'][6]  = __( 'Campaign created.', 'boldermail' );
		$messages['bm_newsletter_rss'][7]  = __( 'Campaign saved.', 'boldermail' );
		$messages['bm_newsletter_rss'][8]  = __( 'Campaign submitted.', 'boldermail' ) . $preview_newsletter_link_html;
		$messages['bm_newsletter_rss'][9]  = sprintf( __( 'Campaign scheduled for: %s.', 'boldermail' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_newsletter_link_html;
		$messages['bm_newsletter_rss'][10] = __( 'Campaign draft updated.', 'boldermail' ) . $preview_newsletter_link_html;

		$messages['bm_newsletter_ares']     = $messages['post'];
		$messages['bm_newsletter_ares'][1]  = __( 'Automated email updated.', 'boldermail' );
		$messages['bm_newsletter_ares'][4]  = $messages['bm_newsletter_ares'][1];
		$messages['bm_newsletter_ares'][5]  = isset( $_GET['revision'] ) ? sprintf( __( 'Automated email restored to revision from %s.', 'boldermail' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		$messages['bm_newsletter_ares'][6]  = __( 'Automated email added.', 'boldermail' );
		$messages['bm_newsletter_ares'][7]  = __( 'Automated email saved.', 'boldermail' );
		$messages['bm_newsletter_ares'][8]  = __( 'Automated email submitted.', 'boldermail' ) . $preview_newsletter_link_html;
		$messages['bm_newsletter_ares'][9]  = sprintf( __( 'Automated email scheduled for: %s.', 'boldermail' ), '<strong>' . $scheduled_date . '</strong>' ) . $scheduled_newsletter_link_html;
		$messages['bm_newsletter_ares'][10] = __( 'Automated email draft updated.', 'boldermail' ) . $preview_newsletter_link_html;

		$template_post_type      = get_post_type_object( 'bm_template' );
		$messages['bm_template'] = str_replace( 'Post', $template_post_type->labels->singular_name, $messages['post'] );

		$list_post_type          = get_post_type_object( 'bm_list' );
		$messages['bm_list']     = str_replace( 'Post', $list_post_type->labels->singular_name, $messages['post'] );
		$messages['bm_list'][6]  = __( 'List created.', 'boldermail' );
		$messages['bm_list'][11] = __( 'Import file uploaded and processing.', 'boldermail' );

		$subscriber_post_type          = get_post_type_object( 'bm_subscriber' );
		$messages['bm_subscriber']     = str_replace( 'Post', $subscriber_post_type->labels->singular_name, $messages['post'] );
		$messages['bm_subscriber'][6]  = __( 'Subscriber added.', 'boldermail' );
		$messages['bm_subscriber'][11] = __( 'Subscriber already existed. Existing subscriber was updated.', 'boldermail' );

		$autoresponder_post_type          = get_post_type_object( 'bm_autoresponder' );
		$messages['bm_autoresponder']     = str_replace( 'Post', $autoresponder_post_type->labels->singular_name, $messages['post'] );
		$messages['bm_autoresponder'][6]  = __( 'Autoresponder added.', 'boldermail' );
		$messages['bm_autoresponder'][11] = __( 'There is already an autoresponder for this list with the specified type. You can only have one autoresponder of a certain type per list.', 'boldermail' );

		return $messages;

		/* phpcs:enable WordPress.WP.I18n.MissingTranslatorsComment */

	}

	/**
	 * Define the messages for bulk actions for each post type.
	 * Includes deleting individual posts.
	 *
	 * @since  2.0.0
	 * @param  string[] $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
	 *                                 keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
	 * @param  int[]    $bulk_counts   Array of item counts for each message, used to build internationalized strings.
	 * @return string[]
	 */
	public static function bulk_post_updated_messages( $bulk_messages, $bulk_counts ) {

		$bulk_messages['bm_newsletter'] = array(
			/* translators: %s: Number of posts. */
			'updated'   => _n( '%s newsletter updated.', '%s newsletters updated.', $bulk_counts['updated'], 'boldermail' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 newsletter not updated, somebody is editing it.', 'boldermail' ) :
			/* translators: %s: Number of posts. */
			_n( '%s newsletter not updated, somebody is editing it.', '%s newsletters not updated, somebody is editing them.', $bulk_counts['locked'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'deleted'   => _n( '%s newsletter permanently deleted.', '%s newsletters permanently deleted.', $bulk_counts['deleted'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'trashed'   => _n( '%s newsletter moved to the Trash.', '%s newsletters moved to the Trash.', $bulk_counts['trashed'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'untrashed' => _n( '%s newsletter restored from the Trash.', '%s newsletters restored from the Trash.', $bulk_counts['untrashed'], 'boldermail' ),
		);

		$bulk_messages['bm_newsletter_rss'] = array(
			/* translators: %s: Number of posts. */
			'updated'   => _n( '%s campaign updated.', '%s campaigns updated.', $bulk_counts['updated'], 'boldermail' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 campaign not updated, somebody is editing it.', 'boldermail' ) :
			/* translators: %s: Number of posts. */
			_n( '%s campaign not updated, somebody is editing it.', '%s campaigns not updated, somebody is editing them.', $bulk_counts['locked'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'deleted'   => _n( '%s campaign permanently deleted.', '%s campaigns permanently deleted.', $bulk_counts['deleted'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'trashed'   => _n( '%s campaign moved to the Trash.', '%s campaigns moved to the Trash.', $bulk_counts['trashed'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'untrashed' => _n( '%s campaign restored from the Trash.', '%s campaigns restored from the Trash.', $bulk_counts['untrashed'], 'boldermail' ),
		);

		$bulk_messages['bm_newsletter_ares'] = array(
			/* translators: %s: Number of posts. */
			'updated'   => _n( '%s automated email updated.', '%s automated emails updated.', $bulk_counts['updated'], 'boldermail' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 automated email not updated, somebody is editing it.', 'boldermail' ) :
			/* translators: %s: Number of posts. */
			_n( '%s automated email not updated, somebody is editing it.', '%s automated emails not updated, somebody is editing them.', $bulk_counts['locked'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'deleted'   => _n( '%s automated email permanently deleted.', '%s automated emails permanently deleted.', $bulk_counts['deleted'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'trashed'   => _n( '%s automated email moved to the Trash.', '%s automated emails moved to the Trash.', $bulk_counts['trashed'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'untrashed' => _n( '%s automated email restored from the Trash.', '%s automated emails restored from the Trash.', $bulk_counts['untrashed'], 'boldermail' ),
		);

		$bulk_messages['bm_subscriber'] = array(
			/* translators: %s: Number of posts. */
			'updated'   => _n( '%s subscriber updated.', '%s subscribers updated.', $bulk_counts['updated'], 'boldermail' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 subscriber not updated, somebody is editing it.', 'boldermail' ) :
			/* translators: %s: Number of posts. */
			_n( '%s subscriber not updated, somebody is editing it.', '%s subscribers not updated, somebody is editing them.', $bulk_counts['locked'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'deleted'   => _n( '%s subscriber permanently deleted.', '%s subscribers permanently deleted.', $bulk_counts['deleted'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'trashed'   => _n( '%s subscriber moved to the Trash.', '%s subscribers moved to the Trash.', $bulk_counts['trashed'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'untrashed' => _n( '%s subscriber restored from the Trash.', '%s subscribers restored from the Trash.', $bulk_counts['untrashed'], 'boldermail' ),
		);

		$bulk_messages['bm_autoresponder'] = array(
			/* translators: %s: Number of posts. */
			'updated'   => _n( '%s autoresponder updated.', '%s autoresponders updated.', $bulk_counts['updated'], 'boldermail' ),
			'locked'    => ( 1 === $bulk_counts['locked'] ) ? __( '1 autoresponder not updated, somebody is editing it.', 'boldermail' ) :
			/* translators: %s: Number of posts. */
			_n( '%s autoresponder not updated, somebody is editing it.', '%s autoresponders not updated, somebody is editing them.', $bulk_counts['locked'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'deleted'   => _n( '%s autoresponder permanently deleted.', '%s autoresponders permanently deleted.', $bulk_counts['deleted'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'trashed'   => _n( '%s autoresponder moved to the Trash.', '%s autoresponders moved to the Trash.', $bulk_counts['trashed'], 'boldermail' ),
			/* translators: %s: Number of posts. */
			'untrashed' => _n( '%s autoresponder restored from the Trash.', '%s autoresponders restored from the Trash.', $bulk_counts['untrashed'], 'boldermail' ),
		);

		return $bulk_messages;

	}

	/**
	 * Remove all admin notices and only display our own on Boldermail pages.
	 *
	 * @see   https://www.satollo.net/how-to-remove-wordpress-admin-notices
	 * @since 2.0.0
	 */
	public static function handle_admin_notices() {

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, boldermail_get_screen_ids(), true ) ) {
			return;
		}

		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
		add_action( 'admin_notices', array( __CLASS__, 'add_admin_notices' ) );

	}

	/**
	 * Handle admin notices.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function add_admin_notices() {

		/**
		 * $_REQUEST['resubscribed'] and $_REQUEST['unsubscribed'] are
		 * necessary because we have row action buttons to resubscribe or
		 * unsubscribe in the screen `edit.php?post_type=bm_subscriber`.
		 *
		 * However, that screen does not offer a filter to modify the preset
		 * message queries (only 'updated', 'locked', 'deleted', 'trashed',
		 * 'untrashed' are allowed).
		 *
		 * To notify the user, we develop our own message here.
		 *
		 * We don't check for post type because we use this message in both `edit.php`
		 * and `post.php`. `$_REQUEST['post_type']` is not defined in `post.php`.
		 *
		 * @since 1.0.0
		 */
		if ( isset( $_REQUEST['unsubscribed'] ) && boldermail_sanitize_int( $_REQUEST['unsubscribed'] ) === 1 ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
			printf( '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Contact unsubscribed.', 'boldermail' ) . '</p></div>' );
		}

		if ( isset( $_REQUEST['resubscribed'] ) && boldermail_sanitize_int( $_REQUEST['resubscribed'] ) === 1 ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
			printf( '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Contact resubscribed.', 'boldermail' ) . '</p></div>' );
		}

		/**
		 * For a better UX experience, we redirect the user to the `edit.php`
		 * page of the newsletters so they can see that their newsletter is
		 * preparing to send. In `edit.php`, `$_REQUEST['post_type']` is available.
		 *
		 * @since 2.0.0
		 */
		if ( isset( $_REQUEST['post_type'] ) && boldermail_sanitize_key( $_REQUEST['post_type'] ) === 'bm_newsletter' && isset( $_REQUEST['created'] ) && boldermail_sanitize_int( $_REQUEST['created'] ) === 1 ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
			printf( '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Newsletter created and preparing to send.', 'boldermail' ) . '</p></div>' );
		}

		/**
		 * Convert error code to user-friendly message.
		 *
		 * @since 1.0.0
		 */
		$error = isset( $_REQUEST['error'] ) ? boldermail_sanitize_text( $_REQUEST['error'] ) : false; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
		if ( $error ) {
			printf( '<div class="notice notice-error is-dismissible">' . wp_kses_post( boldermail_get_error_message( $error ) ) . '</div>' );
		}

		/**
		 * Add admin notices per post.
		 *
		 * @since 1.0.0
		 */
		$screen = get_current_screen();

		if ( 'post' === $screen->base && in_array( $screen->post_type, array( 'bm_newsletter', 'bm_newsletter_rss', 'bm_newsletter_ares' ), true ) ) {

			global $post;

			$newsletter = boldermail_get_newsletter( $post );

			if ( ! $newsletter ) {
				return;
			}

			switch ( $screen->post_type ) {

				case 'bm_newsletter_rss':
					self::add_newsletter_rss_admin_notices( $newsletter );
					break;

				case 'bm_newsletter_ares':
					self::add_newsletter_ares_admin_notices( $newsletter );
					break;

			}

		}

	}

	/**
	 * Handle admin notices for the RSS campaigns.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_RSS_Feed $newsletter The newsletter object.
	 * @return void
	 */
	private static function add_newsletter_rss_admin_notices( Boldermail_Newsletter_RSS_Feed $newsletter ) {

		/**
		 * Display last checked and last created timestamps for campaign.
		 *
		 * @since 1.0.0
		 */
		$last_check_time = $newsletter->get_last_rss_check_time();
		$last_email_time = $newsletter->get_last_rss_email_time();

		if ( $last_check_time && $last_email_time ) {
			/* translators: %s: Date. */
			printf( '<div class="notice notice-success"><p>' . esc_html( sprintf( __( 'The blog was last checked for new content on %s.', 'boldermail' ), gmdate( 'l, F j, Y \a\t g:i:s A', strtotime( $last_check_time ) ) ) ) . '</p></div>' );
		}

		if ( $last_email_time ) {
			/* translators: %s: Date. */
			printf( '<div class="notice notice-success"><p>' . esc_html( sprintf( __( 'The last campaign was created on %s.', 'boldermail' ), gmdate( 'l, F j, Y \a\t g:i:s A', strtotime( $last_email_time ) ) ) ) . '</p></div>' );
		}

		/**
		 * Display the next scheduled cron task in the local time zone.
		 *
		 * @see   https://stackoverflow.com/questions/16750447/convert-unix-timestamp-to-time-zone Convert Unix Timestamp to time zone?
		 * @since 1.0.0
		 */
		$next_scheduled = wp_next_scheduled( 'boldermail_scheduled_newsletter_rss_feed', array( $newsletter->get_post_id() ) );

		if ( $next_scheduled ) {

			if ( class_exists( 'DateTime' ) && class_exists( 'DateTimeZone' ) ) {

				try {
					$dt = new DateTime( '@' . $next_scheduled );

					if ( get_option( 'timezone_string' ) ) {
						$dt->setTimeZone( new DateTimeZone( get_option( 'timezone_string' ) ) );
					}

					/* translators: %s: Date. */
					printf( '<div class="notice notice-success"><p>' . esc_html( sprintf( __( 'The next campaign will go out on %s.', 'boldermail' ), $dt->format( 'l, F j, Y \a\t g:i A' ) ) ) . '</p></div>' );
				} catch ( Exception $e ) {
					printf( '<div class="notice notice-error"><p>' . esc_html__( 'Unable to compute next scheduled campaign.', 'boldermail' ) . '</p></div>' );
				}
			}

		}

		if ( $newsletter->get_status() === 'enabled' ) {
			printf( '<div class="notice notice-alt notice-success"><p>' . wp_kses_post( __( 'The RSS campaign is <strong>enabled</strong> and sending. Pause the campaign to edit it.', 'boldermail' ) ) . '</p></div>' );
		}

		if ( $newsletter->get_status() === 'paused' ) {
			printf( '<div class="notice notice-alt notice-warning"><p>' . wp_kses_post( __( 'The RSS campaign is <strong>paused</strong> and not sending.', 'boldermail' ) ) . '</p></div>' );
		}

	}

	/**
	 * Handle admin notices for the automated emails.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Autoresponder $newsletter The newsletter object.
	 * @return void
	 */
	private static function add_newsletter_ares_admin_notices( Boldermail_Newsletter_Autoresponder $newsletter ) {

		$autoresponder = $newsletter->get_autoresponder();

		if ( $autoresponder ) {
			include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-autoresponders-list-table.php';
			printf(
				'<div class="notice notice-info"><p><a href="%1$s">%2$s</a></p></div>',
				esc_url( Boldermail_Autoresponders_List_Table::get_view_emails_url( $autoresponder ) ),
				esc_html__( '&larr; Back to Automated Emails', 'boldermail' )
			);
		} else {
			printf(
				'<div class="notice notice-info"><p><a href="%1$s">%2$s</a></p></div>',
				esc_url( admin_url( 'edit.php?post_type=bm_autoresponder' ) ),
				esc_html__( '&larr; Back to Autoresponders', 'boldermail' )
			);
		}

		if ( $newsletter->get_status() === 'enabled' ) {
			printf( '<div class="notice notice-alt notice-success"><p>' . wp_kses_post( __( 'This automated email is <strong>enabled</strong> and sending.', 'boldermail' ) ) . '</p></div>' );
		}

		if ( $newsletter->get_status() === 'paused' ) {
			printf( '<div class="notice notice-alt notice-warning"><p>' . wp_kses_post( __( 'This automated email is <strong>paused</strong> and not sending.', 'boldermail' ) ) . '</p></div>' );
		}

	}

}

Boldermail_Messages::init();
