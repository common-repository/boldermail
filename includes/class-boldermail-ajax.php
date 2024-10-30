<?php
/**
 * Handle Boldermail AJAX events.
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
 * Boldermail_Ajax class.
 *
 * @since 1.7.0
 */
class Boldermail_Ajax {

	/**
	 * Initialize the AJAX calls.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function init() {

		self::add_ajax_events();

	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 *
	 * @since  1.7.0
	 * @return void
	 */
	private static function add_ajax_events() {

		$ajax_events = array(
			'block_editor_html_preview',
			'template_html',
			'subscriber_custom_fields',
			'editor_html_preview',
			'editor_plain_text_preview',
			'list_custom_field',
			'newsletter_list_data',
			'newsletter_ares_data',
			'newsletter_ares_trigger',
			'recipients_count',
			'rss_post_type_taxonomies',
			'rss_taxonomy_terms',
			'test_send',
			'update_newsletter',
		);

		foreach ( $ajax_events as $ajax_event ) {
			add_action( 'wp_ajax_boldermail_' . $ajax_event, array( __CLASS__, $ajax_event ) );
		}

	}

	/**
	 * Get the HTML content of the block editor for previewing.
	 *
	 * @since  2.0.0
	 * @return void
	 */
	public static function block_editor_html_preview() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		// Get POST data.
		$post_id = isset( $_POST['post_ID'] ) ? boldermail_sanitize_int( $_POST['post_ID'] ) : 0;

		// Bail early if no post.
		$post = get_post( $post_id );

		if ( ! $post ) {
			wp_send_json_error();
		}

		// Get object.
		$block_template = boldermail_get_object( $post );

		if ( ! $block_template ) {
			wp_send_json_error();
		}

		// Get the parent object.
		$parent = boldermail_get_object( $post->post_parent );

		if ( ! $parent ) {
			wp_send_json_error();
		}

		// Get preview data.
		$preview  = $block_template->get_meta( 'preview' );
		$meta_key = isset( $preview['parent_meta_key'] ) ? boldermail_sanitize_text( $preview['parent_meta_key'] ) : '';
		$filter   = isset( $preview['filter'] ) ? boldermail_sanitize_text( $preview['filter'] ) : false;

		// Setup post data.
		boldermail_setup_postdata( $post->post_parent );

		// Get the HTML preview.
		$html = $parent->get_filtered_meta( $meta_key, 'inline-css', $filter );

		// Send JSON response.
		wp_send_json( $html );

	}

	/**
	 * Get the HTML content of the editor for previewing.
	 *
	 * @see    assets/js/tinymce/plugins/boldermail/plugin.js
	 * @since  1.7.0
	 * @return void
	 */
	public static function editor_html_preview() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		// Get POST data.
		$post_id  = isset( $_POST['post_ID'] ) ? boldermail_sanitize_int( $_POST['post_ID'] ) : 0;
		$meta_key = isset( $_POST['meta_key'] ) ? boldermail_sanitize_text( $_POST['meta_key'] ) : '';
		$filter   = isset( $_POST['filter'] ) ? boldermail_sanitize_text( $_POST['filter'] ) : false;

		// Bail early if no post.
		$post = get_post( $post_id );

		if ( ! $post ) {
			wp_send_json_error();
		}

		// Get object.
		$object = boldermail_get_object( $post );

		if ( ! $object ) {
			wp_send_json_error();
		}

		// Setup post data.
		boldermail_setup_postdata( $post );

		// Save the post data.
		do_action( "save_post_{$post->post_type}", $post_id, $post, true ); /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound */

		// Get the HTML preview.
		$html = $object->get_filtered_meta( $meta_key, 'inline-css', $filter );

		// Send JSON response.
		wp_send_json( $html );

	}

	/**
	 * Get the contents of the editor in plain text for previewing.
	 *
	 * @see    assets/js/tinymce/plugins/boldermail/plugin.js
	 * @since  1.7.0
	 * @return void
	 */
	public static function editor_plain_text_preview() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		// Get POST data.
		$post_id  = isset( $_POST['post_ID'] ) ? boldermail_sanitize_int( $_POST['post_ID'] ) : 0;
		$meta_key = isset( $_POST['meta_key'] ) ? boldermail_sanitize_text( $_POST['meta_key'] ) : '';
		$filter   = isset( $_POST['filter'] ) ? boldermail_sanitize_text( $_POST['filter'] ) : false;

		// Bail early if no post.
		$post = get_post( $post_id );

		if ( ! $post ) {
			wp_send_json_error();
		}

		// Get object.
		$object = boldermail_get_object( $post );

		if ( ! $object ) {
			wp_send_json_error();
		}

		// Setup post data.
		boldermail_setup_postdata( $post );

		// Save the post data.
		do_action( "save_post_{$post->post_type}", $post_id, $post, true ); /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound */

		// Get the HTML preview.
		$html = $object->get_filtered_meta( $meta_key, 'inline-css', $filter );

		// Convert HTML to plain text.
		$plain_text = boldermail_html2text( $html );

		wp_send_json( $plain_text );

	}

	/**
	 * Get the custom fields inputs for a subscriber based on list input.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function subscriber_custom_fields() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id = ( isset( $_POST['post'] ) && is_numeric( $_POST['post'] ) ) ? boldermail_sanitize_int( $_POST['post'] ) : 0;

		$subscriber = boldermail_get_subscriber( $post_id );

		if ( ! $subscriber ) {
			wp_send_json_error();
		}

		$list_post_id = ( isset( $_POST['list'] ) && is_numeric( $_POST['list'] ) ) ? boldermail_sanitize_int( $_POST['list'] ) : 0;

		$list = boldermail_get_list( $list_post_id );
		if ( ! $list ) {
			wp_send_json_error();
		}

		ob_start();

		foreach ( $list->get_custom_fields() as $custom_field ) {
			?>
		<p class="form-field">
			<label for="<?php echo esc_attr( boldermail_string_to_attr( $custom_field['name'] ) ); ?>"><?php echo esc_html( $custom_field['name'] ); ?></label>
			<input type="<?php echo esc_attr( $custom_field['type'] ); ?>" id="<?php echo esc_attr( boldermail_string_to_attr( $custom_field['name'] ) ); ?>" name="_custom_fields[<?php echo esc_attr( $custom_field['name'] ); ?>]" value="<?php echo esc_attr( $subscriber->get_custom_field( $custom_field['name'] ) ); ?>" class="regular-text" />
			<input type="hidden" name="_custom_fields_type[<?php echo esc_attr( $custom_field['name'] ); ?>]" value="<?php echo esc_attr( $custom_field['type'] ); ?>">
			<?php echo isset( $custom_field['tip'] ) ? boldermail_help_tip( $custom_field['tip'] ) : ''; ?>
		</p>
			<?php
		}

		$html = str_replace( array( "\n", "\r" ), '', str_replace( "'", '"', ob_get_clean() ) );

		wp_send_json( array( 'html' => $html ) );

	}

	/**
	 * Get the template HTML code for the "Select Template" preview and to
	 * replace the editor content upon clicking on "Activate".
	 *
	 * @see    assets/js/boldermail-admin.js
	 * @see    https://wordpress.stackexchange.com/a/255199/85404
	 * @since  1.7.0
	 * @return void
	 */
	public static function template_html() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id = isset( $_POST['post'] ) ? boldermail_sanitize_int( $_POST['post'] ) : 0;
		$preview = isset( $_POST['preview'] );

		$post = get_post( $post_id );

		if ( ! $post ) {
			wp_send_json( '<!doctype html><html><head></head><body></body></html>' );
		}

		// Setup post data.
		boldermail_setup_postdata( $post );

		$template = boldermail_get_template( $post->ID );

		if ( ! $template ) {
			wp_send_json( '<!doctype html><html><head></head><body></body></html>' );
		}

		if ( $preview ) {
			wp_send_json( $template->get_filtered_html( 'raw' ) );
		} else {
			wp_send_json( $template->get_html() );
		}

	}

	/**
	 * Get the custom field input to add a field to a list.
	 *
	 * @see    assets/js/boldermail-admin-list-custom-fields.js
	 * @since  1.7.0
	 * @return void
	 */
	public static function list_custom_field() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id = ( isset( $_POST['post'] ) && is_numeric( $_POST['post'] ) ) ? boldermail_sanitize_int( $_POST['post'] ) : 0;

		$addon         = array();
		$addon['name'] = '';
		$addon['type'] = 'text';

		// Do not move from here -- @see `/partials/list/html-boldermail-meta-box-list-settings-custom-fields.php`.
		$loop = '{loop}';

		$list = boldermail_get_list( $post_id );

		if ( ! $list ) {
			wp_send_json_error();
		}

		ob_start();
		include BOLDERMAIL_PLUGIN_DIR . 'partials/list/html-boldermail-meta-box-list-settings-custom-field.php';
		$html = str_replace( array( "\n", "\r" ), '', str_replace( "'", '"', ob_get_clean() ) );
		wp_send_json( $html );

	}

	/**
	 * Get the list info from the autoresponder by AJAX.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function newsletter_ares_data() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id = isset( $_POST['post'] ) ? absint( $_POST['post'] ) : '';

		$autoresponder_post_id = isset( $_POST['autoresponder'] ) ? absint( $_POST['autoresponder'] ) : '';

		$newsletter = boldermail_get_newsletter( $post_id );

		// Do not update data if newsletter is already published.
		if ( $newsletter && $newsletter->is_published() ) {
			wp_send_json_error();
		}

		$autoresponder = boldermail_get_autoresponder( $autoresponder_post_id );

		if ( ! $autoresponder ) {
			wp_send_json_error();
		}

		$list = boldermail_get_list( $autoresponder->get_list_post_id() );

		if ( ! $list ) {
			wp_send_json_error();
		}

		wp_send_json( self::get_newsletter_panel_data( $list ) );

	}

	/**
	 * Get the list info for the "From" tab.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function newsletter_list_data() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id = isset( $_POST['post'] ) ? absint( $_POST['post'] ) : '';
		$list_id = isset( $_POST['list_id'] ) ? boldermail_sanitize_key( $_POST['list_id'] ) : '';

		$newsletter = boldermail_get_newsletter( $post_id );

		// Do not update data if newsletter is already published.
		if ( $newsletter && $newsletter->is_published() ) {
			wp_send_json_error();
		}

		$list = boldermail_get_list_from_id( $list_id );

		if ( ! $list ) {
			wp_send_json_error();
		}

		wp_send_json( self::get_newsletter_panel_data( $list ) );

	}

	/**
	 * Get the contents of the "Trigger" newsletter tab for automated emails.
	 *
	 * @since  1.4.0
	 * @return void
	 */
	public static function newsletter_ares_trigger() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id = ( isset( $_POST['post_id'] ) && is_numeric( $_POST['post_id'] ) ) ? boldermail_sanitize_int( $_POST['post_id'] ) : 0;

		$newsletter = boldermail_get_newsletter( $post_id );

		if ( ! $newsletter ) {
			wp_send_json_error();
		}

		ob_start();
		include BOLDERMAIL_PLUGIN_DIR . 'partials/newsletter/html-boldermail-meta-box-newsletter-data-trigger.php';
		wp_send_json( ob_get_clean() );

	}

	/**
	 * Get the recipients count for a newsletter.
	 *
	 * @since  2.3.0
	 * @return void
	 */
	public static function recipients_count() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$list_ids = isset( $_POST['list_id'] ) ? (array) $_POST['list_id'] : array(); /* phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash */

		$lists = array();
		foreach ( $list_ids as $list_id ) {
			$list = boldermail_get_list_from_id( boldermail_sanitize_key( $list_id ) );

			if ( $list ) {
				$lists[] = $list->get_post_id();
			}
		}

		if ( ! $lists ) {
			wp_send_json( '' );
		}

		$list_args = implode( ', ', array_fill( 0, count( $lists ), '%s' ) );

		global $wpdb;

		$recipients_count = $wpdb->get_var( /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching */
			$wpdb->prepare(
				"SELECT COUNT( DISTINCT post_title ) FROM $wpdb->posts WHERE post_parent IN ( $list_args ) AND post_status = 'subscribed'", /* phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare */
				$lists
			)
		);

		/* translators: %s: Number of recipients. */
		wp_send_json( sprintf( esc_html__( 'Sending to %s recipients.', 'boldermail' ), number_format( $recipients_count ) ) );

	}

	/**
	 * Get all taxonomies for a post type.
	 *
	 * @since  2.3.0
	 * @return void
	 */
	public static function rss_post_type_taxonomies() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id   = isset( $_POST['post'] ) ? boldermail_sanitize_int( $_POST['post'] ) : '';
		$post_type = isset( $_POST['post_type'] ) ? boldermail_sanitize_key( $_POST['post_type'] ) : '';

		$newsletter = boldermail_get_newsletter( $post_id );

		if ( ! $newsletter ) {
			wp_send_json_error();
		}

		if ( 'rss-feed' !== $newsletter->get_type() ) {
			wp_send_json_error();
		}

		if ( ! $post_type ) {
			wp_send_json_error();
		}

		ob_start();

		boldermail_wp_select(
			[
				'id'          => 'taxonomy',
				'label'       => __( 'Taxonomy', 'boldermail' ),
				'name'        => '_taxonomy',
				'class'       => 'boldermail-select2 short',
				'placeholder' => __( 'Select your feed taxonomy (optional)', 'boldermail' ),
				'value'       => $newsletter->get_rss_taxonomy(),
				'options'     => array_replace( // Use `array_replace` instead of `array_merge` to preserve keys.
					[
						'' => '',
					],
					boldermail_get_taxonomies_labels(
						[
							'public'      => true,
							'object_type' => [ $post_type ],
						]
					)
				),
				'editable'    => $newsletter->is_editable(),
				'description' => __( 'Select the taxonomy for your feed.', 'boldermail' ),
			]
		);

		wp_send_json( ob_get_clean() );

	}

	/**
	 * Get all terms for a taxonomy.
	 *
	 * @since  2.3.0
	 * @return void
	 */
	public static function rss_taxonomy_terms() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id  = isset( $_POST['post'] ) ? boldermail_sanitize_int( $_POST['post'] ) : '';
		$taxonomy = isset( $_POST['taxonomy'] ) ? boldermail_sanitize_key( $_POST['taxonomy'] ) : '';

		$newsletter = boldermail_get_newsletter( $post_id );

		if ( ! $newsletter ) {
			wp_send_json_error();
		}

		if ( 'rss-feed' !== $newsletter->get_type() ) {
			wp_send_json_error();
		}

		if ( ! $taxonomy ) {
			wp_send_json_error();
		}

		ob_start();

		boldermail_wp_select(
			[
				'id'                => 'term__includes',
				'label'             => __( 'Include terms', 'boldermail' ),
				'name'              => '_term__includes[]',
				'class'             => 'boldermail-select2 short',
				'placeholder'       => __( 'Select which terms to include in your campaign', 'boldermail' ),
				'value'             => $newsletter->get_rss_term__includes(),
				'options'           => array_replace( // Use `array_replace` instead of `array_merge` to preserve keys.
					[
						'' => '',
					],
					get_terms(
						[
							'taxonomy'   => [ $taxonomy ],
							'hide_empty' => false,
							'fields'     => 'id=>name',
						]
					)
				),
				'editable'          => $newsletter->is_editable(),
				'description'       => __( 'Select the terms (categories, tags) to include in your RSS campaign.', 'boldermail' ),
				'custom_attributes' => array( 'multiple' => '' ),
			]
		);

		boldermail_wp_select(
			[
				'id'                => 'term__excludes',
				'label'             => __( 'Exclude terms', 'boldermail' ),
				'name'              => '_term__excludes[]',
				'class'             => 'boldermail-select2 short',
				'placeholder'       => __( 'Select which terms to exclude from your campaign', 'boldermail' ),
				'value'             => $newsletter->get_rss_term__excludes(),
				'options'           => array_replace( // Use `array_replace` instead of `array_merge` to preserve keys.
					[
						'' => '',
					],
					get_terms(
						[
							'taxonomy'   => [ $taxonomy ],
							'hide_empty' => false,
							'fields'     => 'id=>name',
						]
					)
				),
				'editable'          => $newsletter->is_editable(),
				'description'       => __( 'Select the terms (categories, tags) to exclude from your RSS campaign.', 'boldermail' ),
				'custom_attributes' => array( 'multiple' => '' ),
			]
		);

		wp_send_json( ob_get_clean() );

	}

	/**
	 * Send a test email.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function test_send() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		// Get POST data -- all keys are text!
		$post_id        = isset( $_POST['post_ID'] ) ? boldermail_sanitize_int( $_POST['post_ID'] ) : 0;
		$test_email     = isset( $_POST['test_email'] ) ? boldermail_sanitize_text( $_POST['test_email'] ) : '';
		$from_name_key  = isset( $_POST['from_name'] ) ? boldermail_sanitize_text( $_POST['from_name'] ) : '';
		$from_email_key = isset( $_POST['from_email'] ) ? boldermail_sanitize_text( $_POST['from_email'] ) : '';
		$reply_to_key   = isset( $_POST['reply_to'] ) ? boldermail_sanitize_text( $_POST['reply_to'] ) : '';
		$subject_key    = isset( $_POST['subject'] ) ? boldermail_sanitize_text( $_POST['subject'] ) : '';
		$message_key    = isset( $_POST['content'] ) ? boldermail_sanitize_text( $_POST['content'] ) : '';
		$filter         = isset( $_POST['filter'] ) ? boldermail_sanitize_text( $_POST['filter'] ) : '';

		// Bail early if no post.
		$post = get_post( $post_id );

		if ( ! $post ) {
			wp_send_json( '<div class="notice notice-alt notice-error">' . boldermail_get_error_message( 'no_object' ) . '</div>' );
		}

		// Get object.
		$object = boldermail_get_object( $post );

		if ( ! $object ) {
			wp_send_json( '<div class="notice notice-alt notice-error">' . boldermail_get_error_message( 'no_object' ) . '</div>' );
		}

		// Setup post data.
		boldermail_setup_postdata( $post );

		// Save the post data.
		do_action( "save_post_{$post->post_type}", $post_id, $post, true ); /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound */

		// Convert test emails to array.
		$test_emails = preg_replace( '/\s+/', '', $test_email );
		$test_emails = ( $test_emails ) ? explode( ',', $test_emails ) : array();

		if ( empty( $test_emails ) ) {
			wp_send_json( '<div class="notice notice-alt notice-error">' . boldermail_get_error_message( 'no_test_email' ) . '</div>' );
		}

		// Get meta values.
		$from_name  = ( $from_name_key ) ? $object->get_meta( $from_name_key ) : '';
		$from_email = ( $from_email_key ) ? $object->get_meta( $from_email_key ) : '';
		$reply_to   = ( $reply_to_key ) ? $object->get_meta( $reply_to_key ) : '';

		if ( 'title' === $subject_key ) {
			$subject = get_the_title( $post );
		} else {
			$subject = ( $subject_key ) ? $object->get_filtered_meta( $subject_key, 'utf-8', $filter ) : '';
		}

		if ( ! $subject ) {
			wp_send_json( '<div class="notice notice-alt notice-error">' . boldermail_get_error_message( 'no_subject' ) . '</div>' );
		}

		$message = ( $message_key ) ? $object->get_filtered_meta( $message_key, 'inline-css', $filter ) : '';

		if ( ! $message ) {
			wp_send_json( '<div class="notice notice-alt notice-error">' . boldermail_get_error_message( 'no_html' ) . '</div>' );
		}

		// Build headers.
		$from_header = '';
		if ( is_email( $from_email ) ) {
			$from_header = 'From: ' . ( ( $from_name ) ? $from_name : '' ) . ' <' . $from_email . '>';
		}

		$reply_to_header = '';
		if ( is_email( $reply_to ) ) {
			$reply_to_header = 'Reply-To: <' . $reply_to . '>';
		}

		$content_type_header = 'Content-Type: text/html; charset=UTF-8';

		$app_url = get_option( 'boldermail_url' );

		$list_unsub_header = $app_url ? 'List-Unsubscribe: <' . $app_url . '/unsubscribe-success.php?c=' . time() . '>' : '';

		$headers = array();
		if ( $from_header ) {
			$headers[] = $from_header;
		}
		if ( $reply_to_header ) {
			$headers[] = $reply_to_header;
		}
		if ( $content_type_header ) {
			$headers[] = $content_type_header;
		}
		if ( $list_unsub_header ) {
			$headers[] = $list_unsub_header;
		}

		// Send emails.
		$success = array();
		$error   = array();

		foreach ( $test_emails as $test_email ) {

			if ( is_email( $test_email ) ) {
				$mail_return = wp_mail( $test_email, $subject, $message, $headers );
			} else {
				$mail_return = false;
			}

			if ( $mail_return ) {
				$success[ $test_email ] = $mail_return;
			} else {
				$error[ $test_email ] = $mail_return;
			}

		}

		$message = '';

		if ( count( $success ) > 0 ) {
			$message .= '<div class="notice notice-alt notice-success inline">';

			foreach ( $success as $email => $response ) {
				$message .= '<p>' . $email . ' - Ok</p>';
			}

			$message .= '</div>';
		}

		if ( count( $error ) > 0 ) {
			$message .= '<div class="notice notice-alt notice-error inline">';

			foreach ( $error as $email => $response ) {
				$message .= '<p>' . $email . ' - Error</p>';
			}

			$message .= '</div>';
		}

		// Send JSON response.
		wp_send_json( $message );

	}

	/**
	 * Update campaign data on the WP_List_Table page.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function update_newsletter() {

		check_ajax_referer( 'boldermail_ajax', 'nonce' );

		$post_id = ( isset( $_POST['post_id'] ) && is_numeric( $_POST['post_id'] ) ) ? boldermail_sanitize_int( $_POST['post_id'] ) : 0;

		$newsletter = boldermail_get_newsletter( $post_id );

		if ( ! $newsletter ) {
			wp_send_json_error();
		}

		// Check for regular newsletters and autoresponders -- @see `assets/js/boldermail-admin-edit-bm_newsletter.js`.
		if ( ! $newsletter->is_published() ) {
			wp_send_json_error();
		}

		switch ( $newsletter->get_type() ) {

			case 'regular':
				self::update_newsletter_regular( $newsletter );
				break;

			case 'autoresponder':
				self::update_newsletter_autoresponder( $newsletter );
				break;

			default:
				wp_send_json_error();
				break;

		}

	}

	/**
	 * Update regular newsletters on the WP_List_Table page.
	 *
	 * @since 1.7.0
	 * @param Boldermail_Newsletter_Regular $newsletter Newsletter object.
	 */
	private static function update_newsletter_regular( $newsletter ) {

		$get_params = array(
			'campaign_id' => boldermail_sanitize_int( $newsletter->get_campaign_id() ),
		);

		$newsletter_data = boldermail()->api->get_campaign_data( $get_params );

		if ( is_wp_error( $newsletter_data ) ) {
			wp_send_json_error();
		}

		$newsletter->save( $newsletter_data );

		include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-regular-list-table.php';

		$newsletter_html = array();

		$newsletter_html['status']     = Boldermail_Newsletters_Regular_List_Table::get_status_column_html( $newsletter );
		$newsletter_html['recipients'] = Boldermail_Newsletters_Regular_List_Table::get_recipients_column_html( $newsletter );
		$newsletter_html['opens']      = Boldermail_Newsletters_Regular_List_Table::get_opens_column_html( $newsletter );
		$newsletter_html['clicks']     = Boldermail_Newsletters_Regular_List_Table::get_clicks_column_html( $newsletter );

		wp_send_json( $newsletter_html );

	}

	/**
	 * Update automated newsletters on the WP_List_Table page.
	 *
	 * @since 1.7.0
	 * @param Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 */
	private static function update_newsletter_autoresponder( $newsletter ) {

		$get_params = array(
			'ares_email' => boldermail_sanitize_int( $newsletter->get_ares_email_id() ),
			'ares'       => $newsletter->get_autoresponder_id(),
		);

		$newsletter_data = boldermail()->api->get_ares_email_data( $get_params );

		if ( is_wp_error( $newsletter_data ) ) {
			wp_send_json_error();
		}

		$newsletter->save( $newsletter_data );

		include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-autoresponder-list-table.php';

		$newsletter_html = array();

		$newsletter_html['status']     = Boldermail_Newsletters_Autoresponder_List_Table::get_status_column_html( $newsletter );
		$newsletter_html['recipients'] = Boldermail_Newsletters_Autoresponder_List_Table::get_recipients_column_html( $newsletter );
		$newsletter_html['opens']      = Boldermail_Newsletters_Autoresponder_List_Table::get_opens_column_html( $newsletter );
		$newsletter_html['clicks']     = Boldermail_Newsletters_Autoresponder_List_Table::get_clicks_column_html( $newsletter );

		wp_send_json( $newsletter_html );

	}

	/**
	 * Get data for the Newsletter "From:" tab.
	 *
	 * @since  1.4.0
	 * @param  Boldermail_List $list List object.
	 * @return array
	 */
	private static function get_newsletter_panel_data( $list ) {

		$list_data = array();

		/* phpcs:disable Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure */
		/* phpcs:disable WordPress.CodeAnalysis.AssignmentInCondition.Found */

		if ( $name = $list->get_from_name() ) {
			$list_data['from_name'] = $name;
		}

		if ( $email = $list->get_from_email() ) {
			$list_data['from_email'] = $email;
		}

		if ( $reply_to = $list->get_reply_to() ) {
			$list_data['reply_to'] = $reply_to;
		}

		if ( $company_name = $list->get_company_name() ) {
			$list_data['company_name'] = $company_name;
		}

		if ( $company_address = $list->get_company_address() ) {
			$list_data['company_address'] = $company_address;
		}

		if ( $permission = $list->get_permission() ) {
			$list_data['permission'] = $permission;
		}

		/* phpcs:enable Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure */
		/* phpcs:enable WordPress.CodeAnalysis.AssignmentInCondition.Found */

		return $list_data;

	}

}

Boldermail_Ajax::init();
