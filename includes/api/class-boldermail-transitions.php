<?php
/**
 * Interact with the Boldermail server during post transitions.
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
 * Boldermail_Transitions class.
 *
 * @since 1.0.0
 */
class Boldermail_Transitions {

	/**
	 * Singleton instance.
	 *
	 * @since 1.7.0
	 * @var   Boldermail_Transitions $instance
	 */
	private static $instance;

	/**
	 * Get instance.
	 *
	 * @since  1.7.0
	 * @return Boldermail_Transitions
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Initialize the hooks.
	 *
	 * @since 1.7.0
	 */
	private function __construct() {

		/**
		 * Add default meta when transitioning from `new` to `auto-draft`.
		 *
		 * @since 1.7.0
		 */
		add_action( 'save_post', array( $this, 'add_default_meta' ), 10, 3 );

		/**
		 * Save the meta data on post transitions.
		 *
		 * @since 1.0.0
		 */
		add_action( 'transition_post_status', array( $this, 'save_post_on_transition_post_status' ), 1, 3 );

		/**
		 * Handle Boldermail post objects on post publish.
		 *
		 * @since  1.0.0
		 */
		add_action( 'transition_post_status', array( $this, 'subscriber_transition' ), 9999, 3 );
		add_action( 'transition_post_status', array( $this, 'list_transition' ), 9999, 3 );
		add_action( 'transition_post_status', array( $this, 'newsletter_transition' ), 9999, 3 );
		add_action( 'transition_post_status', array( $this, 'autoresponder_transition' ), 9999, 3 );
		add_action( 'transition_post_status', array( $this, 'template_transition' ), 9999, 3 );

		/**
		 * Handle post actions.
		 *
		 * @since 1.0.0
		 */
		add_action( 'post_action_unsubscribe', array( $this, 'unsubscribe_post_action' ), 10, 1 );
		add_action( 'post_action_resubscribe', array( $this, 'resubscribe_post_action' ), 10, 1 );
		add_action( 'post_action_import_subscribers', array( $this, 'import_subscribers' ), 10, 1 );
		add_action( 'post_action_pause', array( $this, 'newsletter_pause_post_action' ), 10, 1 );
		add_action( 'post_action_duplicate', array( $this, 'newsletter_duplicate_post_action' ), 10, 1 );

		/**
		 * Redirect user to block template after submitting form.
		 *
		 * @since 2.0.0
		 */
		add_filter( 'redirect_post_location', array( $this, 'redirect_to_block_template' ), 0, 2 );

	}

	/**
	 * Redirect user to block template after submitting form.
	 *
	 * When the users click on the "Design Email" button in the newsletter, they
	 * are redirected to the child Block Template. There are several ways we could
	 * have achieved this:
	 *   1. With a `input[type="submit"]` button. However, this method did not
	 *      allow us to add a dashicon in the button.
	 *   2. With a link. This method allows us to add a dashicon, but we have to
	 *      add a `name` attribute and then submit the form with JavaScript.
	 * Both methods above required us to use some JavaScript either way to prevent
	 * the "Leave Site?" popup from happening.
	 * We submit the form with JavaScript rather than using a link with an action
	 * call or an AJAX save because it is simpler to use the existing framework,
	 * and has the same effect. So instead of saving via AJAX and then redirecting
	 * with JavaScript, or using the `post_action_${action}` hook, we just submit
	 * the form. And since we didn't click on "Save as Pending", or "Save Changes",
	 * or "Publish", the post status remains the same (see `_wp_translate_postdata`).
	 * The data is saved correctly. The only thing left to do is redirect
	 * the user to the Block template afterwards.
	 *
	 * @since  2.0.0
	 * @param  string $location The destination URL.
	 * @param  int    $post_id  The post ID of the newsletter, NOT the block template.
	 * @return string
	 */
	public function redirect_to_block_template( $location, $post_id ) {

		check_admin_referer( 'update-post_' . $post_id );

		if ( isset( $_POST['redirect_to_block_template'] ) ) {

			$block_template_post_id = boldermail_sanitize_int( $_POST['redirect_to_block_template'] );

			if ( boldermail_get_block_template( $block_template_post_id ) ) {
				$location = esc_url_raw( get_edit_post_link( $block_template_post_id, 'url' ) );
			}

		}

		return $location;

	}

	/**
	 * Add or update a subscriber.
	 *
	 * *_to_auto-draft              Only when clicking "Add New".
	 * *_to_draft                   No UI for this transition. See `Boldermail_Post_Types::disable_autosave`. Not possible?
	 * *_to_pending                 No UI for this transition. Not possible?
	 * *_to_future                  No UI for this transition. Not possible?
	 * *_to_private                 No UI for this transition. Not possible?
	 * *_to_publish                 Add or update subscriber.
	 * subscribed_to_subscribed     Update subscriber.
	 * unconfirmed_to_unconfirmed   Update subscriber.
	 * unsubscribed_to_unsubscribed Update subscriber.
	 * bounced_to_bounced           Update subscriber.
	 * complained_to_complained     Update subscriber.
	 * trash_to_trash               Delete subscriber.
	 *
	 * auto-draft_to_* No UI for this transition. Not possible?
	 * draft_to_*      No UI for this transition. Not possible?
	 * pending_to_*    No UI for this transition. Not possible?
	 * future_to_*     No UI for this transition. Not possible?
	 * private_to_*    No UI for this transition. Not possible?
	 * publish_to_*    Only `published_to_publish` and `publish_to_trash` UIs provided. Updates or deletes Subscriber in Boldermail.
	 * trash_to_*      Disabled. Subscribers get permanently deleted when they are moved to the Trash, so the Trash is always empty.
	 *
	 * @since 1.0.0
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Post object.
	 */
	public function subscriber_transition( $new_status, $old_status, $post ) {

		// Check post type before getting Subscriber object because all post types call this function.
		if ( 'bm_subscriber' !== $post->post_type ) {
			return;
		}

		// Return if doing CRON or AJAX to avoid conflicts with updates.
		if ( wp_doing_cron() || wp_doing_ajax() ) {
			return;
		}

		/**
		 * Get subscriber object.
		 *
		 * The only time the plugin has failed to get the subscriber was when
		 * the class definition file was not included.
		 *
		 * @var   Boldermail_Subscriber|null|false $subscriber
		 * @since 1.0.0
		 */
		$subscriber = boldermail_get_subscriber( $post );

		if ( ! $subscriber ) {
			wp_safe_redirect( add_query_arg( 'error', 'no_object', wp_get_referer() ) );
			exit;
		}

		$subscriber_statuses = array( 'publish', 'subscribed', 'unconfirmed', 'unsubscribed', 'bounced', 'complained' );

		/**
		 * WordPress inserts a post on the database when a user clicks on
		 * "Add New" as `auto-draft`. Status changes from `new` to `auto-draft`.
		 *   - `wp-admin/post-new.php:66` calls `get_default_post_to_edit`
		 *   - `wp-admin/includes/post.php:667` calls `wp_insert_post`
		 *
		 * When the post is published, the status changes from `auto-draft`
		 * to `publish`. The post is not inserted in the database again,
		 * but rather its status is updated.
		 *
		 * When status changes from `*_to_publish`, the Subscriber gets
		 * added to Boldermail. When the status changes from `publish_to_publish`,
		 * the subscriber information gets updated.
		 *
		 * @since 1.0.0
		 */
		if ( in_array( $new_status, $subscriber_statuses, true ) ) {

			$subscribe_params = array_merge(
				[
					'name'    => boldermail_sanitize_text( $subscriber->get_name() ),
					'list'    => boldermail_sanitize_text( $subscriber->get_list_id() ),
					'email'   => boldermail_sanitize_email( $subscriber->get_email() ),
					'gdpr'    => ( $subscriber->is_gdpr() ) ? 'true' : 'false', // Use strings (see API file).
					'silent'  => ( $subscriber->skip_opt_in_confirm() ) ? 'true' : 'false', // Use strings (see API file).
					'boolean' => 'true', // Use string (see API file).
				],
				$subscriber->get_custom_fields( 'api' )
			);

			$response = boldermail()->api->subscribe( $subscribe_params );

			if ( is_wp_error( $response ) ) {

				$subscriber->wpdb_update( array( 'post_status' => $old_status ) );
				wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), wp_get_referer() ) );
				exit;

			} else {

				/**
				 * After adding or updating the subscriber on the Boldermail
				 * server, update the subscription status on WordPress.
				 *
				 * @since 1.0.0
				 */
				$get_params = array(
					'email'   => boldermail_sanitize_email( $subscriber->get_email() ),
					'list_id' => boldermail_sanitize_text( $subscriber->get_list_id() ),
				);

				$subscriber_data = boldermail()->api->get_subscriber_data( $get_params );

				if ( ! is_wp_error( $subscriber_data ) ) {
					$subscriber->save( $subscriber_data );
				}

				/**
				 * When a user adds a Subscriber that already exists,
				 * Boldermail will recognize the duplicate, and simply
				 * update its information. However, we will end up with
				 * two duplicate Subscribers in the WordPress database.
				 *
				 * In an initial draft of this code, we hooked onto
				 * `pre_post_update` to check if the Subscriber was a
				 * duplicate, then we would update the original Subscriber
				 * with the new meta data, update Boldermail, and finally
				 * delete the duplicate Subscriber from WordPress. However,
				 * this approach was flawed because if the Boldermail
				 * update did not work, we ended up with a Subscriber in
				 * WordPress with updated data, and a Subscriber in Boldermail
				 * with old data.
				 *
				 * In this new approach, we update the Subscriber in
				 * Boldermail first, just as usual. Then, if the update
				 * was successful, we copy the meta data from the duplicate
				 * Subscriber to the original one, and then delete the
				 * duplicate. This way, if the Boldermail update fails,
				 * we end up with a duplicate Subscriber in `auto-draft`
				 * status, which gets ignored, and the original Subscriber
				 * not updated, just like Boldermail's data.
				 *
				 * @since 1.0.0
				 */
				$subscriber_duplicate = boldermail_maybe_get_subscriber_duplicate( $subscriber );

				// Return if there was an error checking for duplicate.
				if ( is_wp_error( $subscriber_duplicate ) ) {
					$subscriber->wpdb_update( array( 'post_status' => $old_status ) );
					wp_safe_redirect( add_query_arg( 'error', $subscriber_duplicate->get_error_code(), wp_get_referer() ) );
					exit;
				}

				// If a duplicate was found -- duplicate is original subscriber.
				if ( $subscriber_duplicate ) {

					// Copy data from duplicate onto original.
					$subscriber_duplicate->copy( $subscriber );

					// Delete this new post if duplicate exists.
					wp_delete_post( $subscriber->get_post_id(), true );

					// Redirect to duplicate subscriber.
					wp_safe_redirect( add_query_arg( 'message', '11', get_edit_post_link( $subscriber_duplicate->get_post_id(), 'url' ) ) );
					exit;

				}
			}
		}

		if ( 'trash' === $new_status ) {

			$delete_params = [
				'email'   => boldermail_sanitize_email( $subscriber->get_email() ),
				'list_id' => boldermail_sanitize_text( $subscriber->get_list_id() ),
			];

			$response = boldermail()->api->delete_subscriber( $delete_params );

			if ( is_wp_error( $response ) ) {

				if ( $response->get_error_code() === 'no_email_in_list' ) {

					/**
					 * If deletion attempt throws an error, and the Subscriber
					 * does not exist in Boldermail, delete in WordPress.
					 *
					 * @since 1.4.0
					 */
					wp_delete_post( $post->ID, true );
					wp_safe_redirect( add_query_arg( 'deleted', 1, admin_url( 'edit.php?post_type=bm_subscriber' ) ) );
					exit;

				}

				/**
				 * If Subscriber exists in Boldermail, but the deletion threw an
				 * error and the Subscriber was not deleted in Boldermail, revert
				 * post status and redirect to "Edit Page".
				 *
				 * @since 1.4.0
				 */
				$subscriber->wpdb_update( array( 'post_status' => $old_status ) );
				wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post, 'url' ) ) );
				exit;

			} else {

				/**
				 * Delete Subscriber in WordPress after it has been deleted in Boldermail.
				 * Just FYI, since we redirect here, the function `wp_trash_post_comments`
				 * does not get called. See `wp_trash_post`.
				 *
				 * @since 1.4.0
				 */
				wp_delete_post( $post->ID, true );
				wp_safe_redirect( add_query_arg( 'deleted', 1, admin_url( 'edit.php?post_type=bm_subscriber' ) ) );
				exit;

			}
		}

	}

	/**
	 * Unsubscribe admin action.
	 *
	 * @since 1.2.3
	 * @param int $post_id Post ID.
	 */
	public function unsubscribe_post_action( $post_id ) {

		$this->subscriber_actions( 'unsubscribe', $post_id );

	}

	/**
	 * Resubscribe admin action.
	 *
	 * @since 1.2.3
	 * @param int $post_id Post ID.
	 */
	public function resubscribe_post_action( $post_id ) {

		$this->subscriber_actions( 'resubscribe', $post_id );

	}

	/**
	 * Subscriber admin actions.
	 *
	 * @since 1.0.0
	 * @param string $action  Action.
	 * @param int    $post_id The post ID.
	 */
	public function subscriber_actions( $action, $post_id ) {

		if ( empty( $post_id ) || ( 'unsubscribe' !== $action && 'resubscribe' !== $action ) ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=bm_subscriber' ) );
			exit;
		}

		check_admin_referer( "{$action}_{$post_id}" );

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to edit this subscriber.', 'boldermail' ) );
		}

		$post = get_post();

		if ( ! $post || 'bm_subscriber' !== $post->post_type ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=bm_subscriber' ) );
			exit();
		}

		$subscriber = boldermail_get_subscriber( $post );

		if ( ! $subscriber ) {
			wp_die( esc_html__( 'Sorry, there was an error accessing the subscriber data.', 'boldermail' ) );
		}

		$response = null;

		if ( 'resubscribe' === $action ) {

			$resubscribe_params = array_merge(
				[
					'name'    => boldermail_sanitize_text( $subscriber->get_name() ),
					'list'    => boldermail_sanitize_text( $subscriber->get_list_id() ),
					'email'   => boldermail_sanitize_email( $subscriber->get_email() ),
					'gdpr'    => ( $subscriber->is_gdpr() ) ? 'true' : 'false', // Use strings (see API file).
					'silent'  => ( $subscriber->skip_opt_in_confirm() ) ? 'true' : 'false', // Use strings (see API file).
					'boolean' => 'true', // Use string (see API file).
				],
				$subscriber->get_custom_fields( 'api' )
			);

			$response = boldermail()->api->subscribe( $resubscribe_params );

		}

		if ( 'unsubscribe' === $action ) {

			$unsubscribe_params = [
				'email'   => boldermail_sanitize_email( $subscriber->get_email() ),
				'list'    => boldermail_sanitize_text( $subscriber->get_list_id() ),
				'boolean' => 'true',
			];

			$response = boldermail()->api->unsubscribe( $unsubscribe_params );

		}

		/**
		 * If there is an error in re/unsubscribing the user, redirect the
		 * user back to the post with an error message. Do NOT change the status of the
		 * subscriber as it wouldn't have changed in Boldermail.
		 *
		 * If there is no error in re/unsubscribing the user, get the updated
		 * post status from Boldermail using `Boldermail_API::get_subscriber_data`,
		 * and if there is no error in getting this information, consider the
		 * Subscriber re/unsubscribed and redirect with a success message.
		 *
		 * @since 1.0.0
		 */
		if ( is_wp_error( $response ) ) {

			$sendback = add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post, 'url' ) );

		} else {

			$sendback = remove_query_arg( boldermail_removable_query_args(), wp_get_referer() );

			$get_params = [
				'email'   => boldermail_sanitize_email( $subscriber->get_email() ),
				'list_id' => boldermail_sanitize_text( $subscriber->get_list_id() ),
			];

			$subscriber_data = boldermail()->api->get_subscriber_data( $get_params );

			if ( ! is_wp_error( $subscriber_data ) ) {

				$subscriber->save( $subscriber_data );

				if ( 'resubscribe' === $action ) {
					$sendback = add_query_arg( array( 'resubscribed' => 1 ), $sendback );
				}

				if ( 'unsubscribe' === $action ) {
					$sendback = add_query_arg( array( 'unsubscribed' => 1 ), $sendback );
				}
			}
		}

		wp_safe_redirect( $sendback );
		exit;

	}

	/**
	 * Add or update a list.
	 *
	 * @since 1.0.0
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Post object.
	 */
	public function list_transition( $new_status, $old_status, $post ) {

		if ( 'publish' === $new_status && in_array( $post->post_type, array( 'bm_list' ), true ) ) {

			$list = boldermail_get_list( $post );

			if ( ! $list ) {
				wp_safe_redirect( add_query_arg( 'error', 'no_object', wp_get_referer() ) );
				exit;
			}

			$response = null;

			$list_params = [
				'list_id'                => boldermail_sanitize_text( $list->get_list_id() ),
				'list_name'              => boldermail_sanitize_text( $list->get_name() ),
				'opt_in'                 => ( $list->get_opt_in_type() === 'single' ) ? 0 : 1,
				'subscribed_url'         => ( $list->get_subscribe_page() ) ? boldermail_sanitize_url( get_permalink( $list->get_subscribe_page() ) ) : '',
				'already_subscribed_url' => ( $list->get_already_subscribed_page() ) ? boldermail_sanitize_url( get_permalink( $list->get_already_subscribed_page() ) ) : '',
				'thankyou'               => boldermail_sanitize_int( $list->send_thank_you_email() ),
				'thankyou_subject'       => boldermail_sanitize_text( $list->get_thank_you_email_subject() ),
				'thankyou_message'       => $list->get_filtered_thank_you_email_content( 'api' ), // Already sanitized!
				'confirm_url'            => ( $list->get_confirmation_page() ) ? boldermail_sanitize_url( get_permalink( $list->get_confirmation_page() ) ) : '',
				'confirmation_subject'   => boldermail_sanitize_text( $list->get_confirmation_email_subject() ),
				'confirmation_email'     => $list->get_filtered_confirmation_email_content( 'api' ), // Already sanitized!
				'opt_out'                => ( $list->get_opt_out_type() === 'single' ) ? 0 : 1,
				'unsubscribe_all_list'   => boldermail_sanitize_int( $list->do_unsubscribe_all_lists() ),
				'unsubscribed_url'       => ( $list->get_unsubscribe_page() ) ? boldermail_sanitize_url( get_permalink( $list->get_unsubscribe_page() ) ) : '',
				'goodbye'                => boldermail_sanitize_int( $list->send_unsubscribe_email() ),
				'goodbye_subject'        => boldermail_sanitize_text( $list->get_unsubscribe_email_subject() ),
				'goodbye_message'        => $list->get_filtered_unsubscribe_email_content( 'api' ), // Already sanitized!
				'gdpr_enabled'           => boldermail_sanitize_int( $list->is_gdpr_enabled() ),
				'custom_fields'          => boldermail_format_custom_fields( array_merge( Boldermail_List::get_default_fields(), $list->get_custom_fields() ) ),
			];

			if ( 'publish' !== $old_status ) {

				$response = boldermail()->api->add_list( $list_params );

				if ( ! is_wp_error( $response ) ) {

					$list->save_meta(
						[
							'id'        => boldermail_sanitize_text( wp_remote_retrieve_body( $response ) ),
							'timestamp' => time(),
						]
					);

				}
			} else {
				$response = boldermail()->api->update_list( $list_params );
			}

			if ( is_wp_error( $response ) ) {
				$list->wpdb_update( array( 'post_status' => $old_status ) );
				wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post, 'url' ) ) );
				exit;
			}
		}

	}

	/**
	 * Import subscribers.
	 *
	 * @since 1.0.0
	 * @param string $post_id Post ID.
	 */
	public function import_subscribers( $post_id ) {

		if ( ! $post_id ) {
			return;
		}

		global $title, $parent_file, $submenu_file, $post_type, $post_type_object, $post;

		$post = get_post( $post_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		if ( ! $post ) {
			wp_die( esc_html__( 'The list you are trying to edit no longer exists.', 'boldermail' ) );
		}

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to edit this list.', 'boldermail' ) );
		}

		if ( 'trash' === $post->post_status ) {
			wp_die( esc_html__( 'You can&#8217;t edit this List because it is in the Trash. Please restore it and try again.', 'boldermail' ) );
		}

		$user_id = wp_check_post_lock( $post_id );
		if ( $user_id ) {
			$user = get_userdata( $user_id );
			/* translators: The user's display name. */
			wp_die( esc_html( sprintf( __( 'You cannot edit this list. %s is currently editing.', 'boldermail' ), $user->display_name ) ) );
		}

		$post_type        = $post->post_type; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$post_type_object = get_post_type_object( $post_type ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		if ( isset( $post_type_object ) && $post_type_object->show_in_menu && true !== $post_type_object->show_in_menu ) {
			$parent_file = $post_type_object->show_in_menu; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		} else {
			$parent_file = "edit.php?post_type=$post_type"; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
		$submenu_file = "edit.php?post_type=$post_type"; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		$title = __( 'Import Subscribers', 'boldermail' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		$list = boldermail_get_list( $post_id );

		if ( ! $list ) {
			return;
		}

		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), "import_subscribers-list_{$post_id}" ) ) {
			return;
		}

		include BOLDERMAIL_PLUGIN_DIR . 'partials/list/html-boldermail-list-bulk-subscriber-actions.php';

	}

	/**
	 * Add, update, or delete an autoresponder.
	 *
	 * @since 1.0.0
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Post object.
	 */
	public function autoresponder_transition( $new_status, $old_status, $post ) {

		if ( 'bm_autoresponder' !== $post->post_type ) {
			return;
		}

		$autoresponder = boldermail_get_autoresponder( $post );

		if ( ! $autoresponder ) {
			wp_safe_redirect( add_query_arg( 'error', 'no_object', wp_get_referer() ) );
			exit;
		}

		// If publishing for the first time...
		if ( 'publish' === $new_status && 'publish' !== $old_status ) {

			/**
			 * Check if an Autoresponder with the same list, type, and custom
			 * meta key already exists. Boldermail only allows one
			 * autoresponder per list, type, and custom meta key combination.
			 *
			 * @since 1.4.0
			 */
			$autoresponder_duplicate = boldermail_maybe_get_autoresponder_duplicate( $autoresponder );

			// Return if there was an error checking for duplicate.
			if ( is_wp_error( $autoresponder_duplicate ) ) {
				$autoresponder->wpdb_update( [ 'post_status' => $old_status ] );
				wp_safe_redirect( add_query_arg( 'error', $autoresponder_duplicate->get_error_code(), wp_get_referer() ) );
				exit;
			}

			// If a duplicate was found... duplicate is original autoresponder!
			if ( $autoresponder_duplicate ) {

				// Delete this new post if duplicate exists.
				wp_delete_post( $autoresponder->get_post_id(), true );

				// Redirect to duplicate autoresponder.
				wp_safe_redirect( add_query_arg( 'message', '11', get_edit_post_link( $autoresponder_duplicate->get_post_id(), 'url' ) ) );
				exit;

			}

			$add_params = [
				'autoresponder_id'   => boldermail_sanitize_int( $autoresponder->get_autoresponder_id() ),
				'autoresponder_name' => boldermail_sanitize_text( $autoresponder->get_name() ),
				'autoresponder_type' => boldermail_sanitize_int( $autoresponder->get_type() ),
				'list_id'            => boldermail_sanitize_text( $autoresponder->get_list_id() ),
			];

			// Add autoresponder if not duplicate.
			$response = boldermail()->api->add_autoresponder( $add_params );

			if ( is_wp_error( $response ) ) {

				$autoresponder->wpdb_update( array( 'post_status' => $old_status ) );
				wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), wp_get_referer() ) );
				exit;

			} else {

				$autoresponder->save_meta( array( 'id' => boldermail_sanitize_int( $response['body'] ) ) );

			}
		}

		// If moving to trash... delete the autoresponder!
		if ( 'trash' === $new_status ) {

			if ( 'publish' === $old_status ) {

				$delete_params = [
					'autoresponder_id' => boldermail_sanitize_int( $autoresponder->get_autoresponder_id() ),
					'list_id'          => boldermail_sanitize_text( $autoresponder->get_list_id() ),
				];

				$response = boldermail()->api->delete_autoresponder( $delete_params );

				if ( is_wp_error( $response ) ) {
					$autoresponder->wpdb_update( [ 'post_status' => $old_status ] );
					wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post, 'url' ) ) );
					exit;
				}
			}

			wp_delete_post( $post->ID, true );
			wp_safe_redirect( add_query_arg( 'deleted', 1, admin_url( 'edit.php?post_type=bm_autoresponder' ) ) );
			exit;

		}

	}

	/**
	 * Send, update, or delete a campaign.
	 *
	 * @since 1.0.0
	 * @param string  $new_status New post status.
	 * @param string  $old_status Old post status.
	 * @param WP_Post $post       Post object.
	 */
	public function newsletter_transition( $new_status, $old_status, $post ) {

		if ( ! in_array( $post->post_type, [ 'bm_newsletter', 'bm_newsletter_rss', 'bm_newsletter_ares' ], true ) ) {
			return;
		}

		/**
		 * Setup the `global $post` data if doing cron or AJAX.
		 *
		 * Our newsletters include shortcodes that must utilize the `global $post`
		 * variable. When doing a cron job (for example, when an RSS campaign
		 * publishes a new newsletter), the `global $post` variable is not setup
		 * unless we call `boldermail_setup_postdata` here.
		 *
		 * @since 1.0.0
		 */
		if ( wp_doing_cron() || wp_doing_ajax() ) {
			boldermail_setup_postdata( $post );
		}

		$newsletter = boldermail_get_newsletter( $post );

		if ( ! $newsletter ) {
			wp_safe_redirect( add_query_arg( 'error', 'no_object', wp_get_referer() ) );
			exit;
		}

		/**
		 * Validate newsletter data if publishing or scheduling, but not updating.
		 *
		 * That way, when the user schedules a newsletter or starts an RSS
		 * campaign, they don't have th wait until the newsletter tries to
		 * publish to see if there is an error or not.
		 *
		 * @since 1.0.0
		 */
		if ( ( 'publish' === $new_status || 'future' === $new_status ) && 'publish' !== $old_status ) {

			$validate_params = [
				'from_name'  => boldermail_sanitize_text( $newsletter->get_from_name() ),
				'from_email' => boldermail_sanitize_email( $newsletter->get_from_email() ),
				'reply_to'   => boldermail_sanitize_email( $newsletter->get_reply_to() ),
				'subject'    => boldermail_sanitize_text( $newsletter->get_filtered_subject( 'utf-8', 'api' ) ),
				'html_text'  => $newsletter->get_filtered_html( 'api' ), // Already sanitized!
			];

			$response = boldermail()->api->validate_campaign( $validate_params );

			if ( is_wp_error( $response ) ) {
				$newsletter->wpdb_update( [ 'post_status' => $old_status ] );

				if ( isset( $_POST['action'] ) && boldermail_sanitize_key( $_POST['action'] ) === 'editpost' ) { /* phpcs:ignore WordPress.Security.NonceVerification.Missing */
					wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post->ID, 'url' ) ) );
					exit;
				}
			}
		}

		/**
		 * Handle post type-specific transitions.
		 *
		 * @since 1.4.0
		 */
		switch ( $post->post_type ) {

			case 'bm_newsletter':
				$this->newsletter_regular_transition( $new_status, $old_status, $post, $newsletter );
				return;

			case 'bm_newsletter_rss':
				$this->newsletter_rss_transition( $new_status, $old_status, $post, $newsletter );
				return;

			case 'bm_newsletter_ares':
				$this->newsletter_ares_transition( $new_status, $old_status, $post, $newsletter );
				return;

		}

	}

	/**
	 * Send, update, or delete a regular campaign.
	 *
	 * @since 1.4.0
	 * @param string                        $new_status New post status.
	 * @param string                        $old_status Old post status.
	 * @param WP_Post                       $post       Post object.
	 * @param Boldermail_Newsletter_Regular $newsletter Newsletter object.
	 */
	public function newsletter_regular_transition( $new_status, $old_status, $post, $newsletter ) {

		// If publishing for the first time...
		if ( 'publish' === $new_status && 'publish' !== $old_status ) {

			$send_params = [
				'from_name'     => boldermail_sanitize_text( $newsletter->get_from_name() ),
				'from_email'    => boldermail_sanitize_email( $newsletter->get_from_email() ),
				'reply_to'      => boldermail_sanitize_email( $newsletter->get_reply_to() ),
				'title'         => boldermail_sanitize_text( $newsletter->get_filtered_subject( 'raw', 'api' ) ),
				'subject'       => boldermail_sanitize_text( $newsletter->get_filtered_subject( 'utf-8', 'api' ) ),
				'plain_text'    => boldermail_sanitize_textarea( $newsletter->get_filtered_plain_text( 'api' ) ),
				'html_text'     => $newsletter->get_filtered_html( 'api' ), // Already sanitized!
				'list_ids'      => boldermail_sanitize_text( implode( ',', $newsletter->get_list_id() ) ),
				'query_string'  => 'utm_source=newsletter&utm_medium=boldermail&utm_campaign=' . esc_attr( get_post_field( 'post_name', $newsletter->get_post_id() ) ),
				'send_campaign' => 1,
				'json'          => 1,
			];

			$response = boldermail()->api->send_campaign( $send_params );

			if ( is_wp_error( $response ) ) {

				// If newsletter has a post parent ID (from an RSS campaign) revert to draft.
				if ( wp_get_post_parent_id( $post ) ) {
					$newsletter->wpdb_update( array( 'post_status' => 'draft' ) );
				} else {
					$newsletter->wpdb_update( array( 'post_status' => $old_status ) );
				}

				if ( isset( $_POST['action'] ) && boldermail_sanitize_key( $_POST['action'] ) === 'editpost' ) { /* phpcs:ignore WordPress.Security.NonceVerification.Missing */
					wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post->ID, 'url' ) ) );
					exit;
				}

			} else {

				// Save campaign ID.
				$json_response = json_decode( $response['body'], true );
				$newsletter->save_meta( [ 'id' => boldermail_sanitize_int( $json_response['campaign_id'] ) ] );

				// Update status.
				$newsletter->wpdb_update( [ 'post_status' => 'preparing' ] );

				// Update the campaign data after insertion.
				$get_params = [
					'campaign_id' => boldermail_sanitize_int( $newsletter->get_campaign_id() ),
				];

				$newsletter_data = boldermail()->api->get_campaign_data( $get_params );

				if ( ! is_wp_error( $newsletter_data ) ) {
					$newsletter->save( $newsletter_data );
				}

				// Redirect to "Newsletters" page for better UX if the transition was
				// initiated from the "Add New" of "Edit Post" pages (not the cron job
				// for RSS feeds).
				if ( isset( $_POST['action'] ) && boldermail_sanitize_key( $_POST['action'] ) === 'editpost' ) { /* phpcs:ignore WordPress.Security.NonceVerification.Missing */
					wp_safe_redirect( add_query_arg( 'created', 1, admin_url( 'edit.php?post_type=bm_newsletter' ) ) );
					exit;
				}

			}

		}

		// If reverting from published to draft.
		if ( ! in_array( $new_status, [ 'preparing', 'sending', 'sent', 'trash' ], true ) && in_array( $old_status, [ 'preparing', 'sending', 'sent' ], true ) ) {

			// Invalid transition -- revert.
			$newsletter->wpdb_update( [ 'post_status' => $old_status ] );
			wp_safe_redirect( add_query_arg( 'error', 'invalid_post_transition', get_edit_post_link( $post->ID, 'url' ) ) );
			exit;

		}

		// If moving to Trash.
		if ( 'trash' === $new_status ) {

			if ( in_array( $old_status, [ 'preparing', 'sending', 'sent' ], true ) ) {

				$delete_params = [
					'campaign_id' => boldermail_sanitize_int( $newsletter->get_campaign_id() ),
				];

				$response = boldermail()->api->delete_campaign( $delete_params );

				if ( is_wp_error( $response ) ) {
					$newsletter->wpdb_update( array( 'post_status' => $old_status ) );
					wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post, 'url' ) ) );
					exit;
				}

			}

			if ( $newsletter->get_block_template_post_id() ) {
				wp_delete_post( $newsletter->get_block_template_post_id(), true );
			}

			wp_delete_post( $post->ID, true );
			wp_safe_redirect( add_query_arg( 'deleted', 1, admin_url( 'edit.php?post_type=bm_newsletter' ) ) );
			exit;

		}

	}

	/**
	 * Send, update, or delete an RSS campaign.
	 *
	 * @since 1.4.0
	 * @param string                         $new_status New post status.
	 * @param string                         $old_status Old post status.
	 * @param WP_Post                        $post       Post object.
	 * @param Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
	 */
	public function newsletter_rss_transition( $new_status, $old_status, $post, $newsletter ) {

		// If publishing for the first time.
		if ( 'publish' === $new_status && 'publish' !== $old_status ) {

			$response = Boldermail_Cron::schedule_rss_campaign( $newsletter );

			if ( is_wp_error( $response ) ) {

				$newsletter->wpdb_update( [ 'post_status' => $old_status ] );
				wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post->ID, 'url' ) ) );
				exit;

			} else {
				$newsletter->wpdb_update( [ 'post_status' => 'enabled' ] );
			}

		}

		// If pausing the campaign.
		if ( ( 'paused' === $new_status ) && in_array( $old_status, [ 'publish', 'enabled' ], true ) ) {

			Boldermail_Cron::clear_scheduled_rss_campaign( $newsletter );
			$newsletter->wpdb_update( array( 'post_status' => 'paused' ) );

		}

		// If moving to Trash.
		if ( 'trash' === $new_status ) {

			if ( 'publish' === $old_status ) {
				Boldermail_Cron::clear_scheduled_rss_campaign( $newsletter );
			}

			wp_delete_post( $post->ID, true );
			wp_safe_redirect( add_query_arg( 'deleted', 1, admin_url( 'edit.php?post_type=bm_newsletter_rss' ) ) );
			exit;

		}

	}

	/**
	 * Send, update, or delete an automated campaign.
	 *
	 * @since 1.4.0
	 * @param string                              $new_status New post status.
	 * @param string                              $old_status Old post status.
	 * @param WP_Post                             $post       Post object.
	 * @param Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
	 */
	public function newsletter_ares_transition( $new_status, $old_status, $post, $newsletter ) {

		$autoresponder = $newsletter->get_autoresponder();

		// if publishing for the first time.
		if ( 'publish' === $new_status && in_array( $old_status, [ 'auto-draft', 'draft' ], true ) ) {

			$create_params = array(
				'list_id'                    => ( $autoresponder ) ? boldermail_sanitize_text( $autoresponder->get_list_id() ) : '',
				'ares'                       => boldermail_sanitize_int( $newsletter->get_autoresponder_id() ),
				'ares_type'                  => ( $autoresponder ) ? boldermail_sanitize_int( $autoresponder->get_type() ) : '',
				'subject'                    => boldermail_sanitize_text( $newsletter->get_filtered_subject( 'utf-8', 'api' ) ),
				'from_name'                  => boldermail_sanitize_text( $newsletter->get_from_name() ),
				'from_email'                 => boldermail_sanitize_email( $newsletter->get_from_email() ),
				'reply_to'                   => boldermail_sanitize_email( $newsletter->get_reply_to() ),
				'plain_text'                 => boldermail_sanitize_textarea( $newsletter->get_filtered_plain_text( 'api' ) ),
				'html_text'                  => $newsletter->get_filtered_html( 'api' ), // Already sanitized!
				'query_string'               => 'utm_source=newsletter&utm_medium=boldermail&utm_campaign=' . esc_attr( get_post_field( 'post_name', $newsletter->get_post_id() ) ),
				'time_condition_number'      => boldermail_sanitize_int( $newsletter->get_trigger_number() ),
				'time_condition_intervals'   => boldermail_sanitize_text( $newsletter->get_trigger_interval() ),
				'time_condition_beforeafter' => boldermail_sanitize_text( $newsletter->get_trigger_beforeafter() ),
				'enabled'                    => ( $newsletter->is_enabled() ) ? '1' : '0',
			);

			$response = boldermail()->api->add_ares_email( $create_params );

			if ( is_wp_error( $response ) ) {

				$newsletter->wpdb_update( array( 'post_status' => $old_status ) );
				wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post, 'url' ) ) );
				exit;

			} else {

				$newsletter->save_meta(
					[
						'id' => boldermail_sanitize_text( $response['body'] ),
					]
				);

				// Update opens + clicks data.
				$get_params = [
					'ares_email' => boldermail_sanitize_int( $newsletter->get_ares_email_id() ),
					'ares'       => $newsletter->get_autoresponder_id(),
				];

				$newsletter_data = boldermail()->api->get_ares_email_data( $get_params );

				if ( ! is_wp_error( $newsletter_data ) ) {
					$newsletter->save( $newsletter_data );
				}

			}

		}

		$updating_campaign = in_array( $new_status, [ 'publish', 'enabled' ], true ) && in_array( $old_status, [ 'publish', 'enabled', 'paused' ], true );
		$pausing_campaign  = ( 'paused' === $new_status ) && in_array( $old_status, [ 'publish', 'enabled' ], true );

		// If updating or enabling the campaign again, or if reverting from enabled to paused.
		if ( $updating_campaign || $pausing_campaign ) {

			$update_params = [
				'list_id'                    => ( $autoresponder ) ? boldermail_sanitize_text( $autoresponder->get_list_id() ) : '',
				'ares'                       => boldermail_sanitize_int( $newsletter->get_autoresponder_id() ),
				'ares_type'                  => ( $autoresponder ) ? boldermail_sanitize_int( $autoresponder->get_type() ) : '',
				'ares_email'                 => boldermail_sanitize_int( $newsletter->get_ares_email_id() ),
				'subject'                    => boldermail_sanitize_text( $newsletter->get_filtered_subject( 'utf-8', 'api' ) ),
				'from_name'                  => boldermail_sanitize_text( $newsletter->get_from_name() ),
				'from_email'                 => boldermail_sanitize_email( $newsletter->get_from_email() ),
				'reply_to'                   => boldermail_sanitize_email( $newsletter->get_reply_to() ),
				'plain_text'                 => boldermail_sanitize_textarea( $newsletter->get_filtered_plain_text( 'api' ) ),
				'html_text'                  => $newsletter->get_filtered_html( 'api' ), // Already sanitized!
				'query_string'               => 'utm_source=newsletter&utm_medium=boldermail&utm_campaign=' . esc_attr( get_post_field( 'post_name', $newsletter->get_post_id() ) ),
				'time_condition_number'      => boldermail_sanitize_int( $newsletter->get_trigger_number() ),
				'time_condition_intervals'   => boldermail_sanitize_text( $newsletter->get_trigger_interval() ),
				'time_condition_beforeafter' => boldermail_sanitize_text( $newsletter->get_trigger_beforeafter() ),
				'enabled'                    => ( $newsletter->is_enabled() ) ? '1' : '0',
			];

			$response = boldermail()->api->update_ares_email( $update_params );

			if ( is_wp_error( $response ) ) {

				$newsletter->wpdb_update( array( 'post_status' => $old_status ) );
				wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post, 'url' ) ) );
				exit;

			} else {

				// Update opens + clicks data.
				$get_params = [
					'ares_email' => boldermail_sanitize_int( $newsletter->get_ares_email_id() ),
					'ares'       => $newsletter->get_autoresponder_id(),
				];

				$newsletter_data = boldermail()->api->get_ares_email_data( $get_params );

				if ( ! is_wp_error( $newsletter_data ) ) {
					$newsletter->save( $newsletter_data );
				}

			}

		}

		// If moving to Trash.
		if ( 'trash' === $new_status ) {

			if ( in_array( $old_status, [ 'enabled', 'paused' ], true ) ) {

				$autoresponder = $newsletter->get_autoresponder();

				$delete_params = [
					'list_id'    => ( $autoresponder ) ? boldermail_sanitize_text( $autoresponder->get_list_id() ) : '',
					'ares_email' => boldermail_sanitize_int( $newsletter->get_ares_email_id() ),
					'ares'       => $newsletter->get_autoresponder_id(),
				];

				$response = boldermail()->api->delete_ares_email( $delete_params );

				// If error, or autoresponder parent was NOT already deleted.
				if ( is_wp_error( $response ) && ! in_array( $response->get_error_code(), [ 'no_parent', 'invalid_ares_email' ], true ) ) {
					$newsletter->wpdb_update( array( 'post_status' => $old_status ) );
					wp_safe_redirect( add_query_arg( 'error', $response->get_error_code(), get_edit_post_link( $post, 'url' ) ) );
					exit;
				}
			}

			if ( $newsletter->get_block_template_post_id() ) {
				wp_delete_post( $newsletter->get_block_template_post_id(), true );
			}

			wp_delete_post( $post->ID, true );
			wp_safe_redirect( add_query_arg( 'deleted', 1, admin_url( 'edit.php?post_type=bm_newsletter_ares' ) ) );
			exit;

		}

	}

	/**
	 * Pause an automated newsletter.
	 *
	 * @since 1.4.0
	 * @param int $post_id Post ID.
	 */
	public function newsletter_pause_post_action( $post_id ) {

		if ( empty( $post_id ) ) {
			wp_safe_redirect( admin_url( 'post.php' ) );
			exit;
		}

		check_admin_referer( "pause_{$post_id}" );

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to edit this newsletter.', 'boldermail' ) );
		}

		$post = get_post();

		if ( ! $post ) {
			wp_die( esc_html__( 'The newsletter you are trying to duplicate no longer exists.', 'boldermail' ) );
		}

		if ( ! in_array( $post->post_type, array( 'bm_newsletter_rss', 'bm_newsletter_ares' ), true ) ) {
			wp_die( esc_html__( 'Invalid newsletter type.', 'boldermail' ) );
		}

		$newsletter = boldermail_get_newsletter( $post );

		if ( ! $newsletter ) {
			wp_die( esc_html__( 'Sorry, there was an error accessing the newsletter data.', 'boldermail' ) );
		}

		if ( ! in_array( $post->post_status, array( 'publish', 'enabled' ), true ) ) {
			wp_safe_redirect( get_edit_post_link( $post, 'url' ) );
			exit;
		}

		$newsletter->wpdb_update( [ 'post_status' => 'paused' ] );

		clean_post_cache( $post->ID );

		$old_status        = $post->post_status;
		$post->post_status = 'paused';
		wp_transition_post_status( 'paused', $old_status, $post );

		wp_safe_redirect( add_query_arg( [ 'message' => 1 ], wp_get_referer() ) );
		exit;

	}

	/**
	 * Newsletter duplicate actions.
	 *
	 * @see   https://rudrastyh.com/wordpress/duplicate-post.html
	 * @since 1.0.0
	 * @param int $post_id Post ID.
	 */
	public function newsletter_duplicate_post_action( $post_id ) {

		global $wpdb;

		if ( empty( $post_id ) ) {
			wp_safe_redirect( admin_url( 'post.php' ) );
			exit();
		}

		check_admin_referer( "duplicate_{$post_id}" );

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to duplicate this newsletter.', 'boldermail' ) );
		}

		$post = get_post( $post_id );

		if ( ! $post ) {
			wp_die( esc_html__( 'The newsletter you are trying to duplicate no longer exists.', 'boldermail' ) );
		}

		$user_id = wp_check_post_lock( $post_id );
		if ( $user_id ) {
			$user = get_userdata( $user_id );
			/* translators: %s: The user's display name. */
			wp_die( esc_html( sprintf( __( 'You cannot duplicate this newsletter. %s is currently editing.', 'boldermail' ), $user->display_name ) ) );
		}

		$newsletter = boldermail_get_newsletter( $post );

		if ( ! $newsletter ) {
			wp_die( esc_html__( 'Sorry, there was an error accessing the newsletter data.', 'boldermail' ) );
		}

		// User who duplicates the newsletter is the author.
		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		// Post data.
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		);

		// Insert new post.
		remove_action( 'save_post', array( $this, 'add_default_meta' ), 10 );
		$new_post_id = wp_insert_post( $args );
		add_action( 'save_post', array( $this, 'add_default_meta' ), 10, 3 );

		// Insert block template.
		if ( $newsletter->use_block_editor() ) {
			$block_template_post_id = $newsletter->get_block_template_post_id();
			$block_template_post    = get_post( $block_template_post_id );

			remove_action( 'save_post', array( $this, 'add_default_meta' ), 10 );
			$new_block_template_post_id = wp_insert_post(
				array(
					/* translators: %s: Post ID. */
					'post_title'   => boldermail_sanitize_text( sprintf( __( 'Block Template %s', 'boldermail' ), $new_post_id ) ),
					'post_content' => $block_template_post->post_content,
					'post_status'  => 'publish',
					'post_type'    => 'bm_block_template',
					'post_author'  => $new_post_author,
					'post_parent'  => $new_post_id,
					'meta_input'   => [
						'_preview'         => get_post_meta( $block_template_post_id, '_preview', true ),
						'_parent_meta_key' => get_post_meta( $block_template_post_id, '_parent_meta_key', true ),
					],
				)
			);
			add_action( 'save_post', array( $this, 'add_default_meta' ), 10, 3 );

			add_post_meta( $new_post_id, '_use_block_editor', true );
			add_post_meta( $new_post_id, '_block_template_post_id', $new_block_template_post_id );

			add_post_meta( $new_block_template_post_id, '_template_style', get_post_meta( $block_template_post_id, '_template_style', true ) );
		}

		// Get all current post terms ad set them to the new post draft.
		$taxonomies = get_object_taxonomies( $post->post_type );

		foreach ( $taxonomies as $taxonomy ) {
			$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
			wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
		}

		// Duplicate all post meta just in two SQL queries.
		$post_meta_infos = $wpdb->get_results( /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching */
			$wpdb->prepare(
				"SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=%s",
				$post_id
			)
		);

		if ( count( $post_meta_infos ) !== 0 ) {

			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";

			$sql_query_sel = [];

			foreach ( $post_meta_infos as $meta_info ) {

				$meta_key = $meta_info->meta_key;

				if ( in_array(
					$meta_key,
					[
						'_wp_old_slug',
						'_id',
						'_to_send',
						'_recipients',
						'_opens',
						'_links',
						'_unique_opens',
						'_unique_clicks',
						'_use_block_editor',
						'_block_template_post_id',
					],
					true
				) ) {
					continue;
				}

				$meta_value = addslashes( $meta_info->meta_value );

				$sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";

			}

			$sql_query .= implode( ' UNION ALL ', $sql_query_sel );
			$wpdb->query( $wpdb->prepare( $sql_query ) ); /* phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared */
		}

		// Redirect to the edit post screen for the new draft.
		wp_safe_redirect( get_edit_post_link( $new_post_id, 'url' ) );
		exit;

	}



	/**
	 * Add or update a template.
	 *
	 * @since  1.0.0
	 * @param  string  $new_status New post status.
	 * @param  string  $old_status Old post status.
	 * @param  WP_Post $post       Post object.
	 */
	public function template_transition( $new_status, $old_status, $post ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed */

		if ( 'bm_template' !== $post->post_type ) {
			return;
		}

		$template = boldermail_get_template( $post );

		if ( ! $template ) {
			wp_safe_redirect( add_query_arg( 'error', 'no_object', wp_get_referer() ) );
			exit;
		}

		// If publishing, updating, or saving as draft or pending.
		if ( 'trash' !== $new_status ) {

			/**
			 * Only sync the content once.
			 *
			 * The Gutenberg editor calls this function twice. The second pass
			 * of `transition_post_status` happens only if there is a plugin that
			 * adds meta boxes to the post editor. A second pass is needed to save
			 * the custom meta box data because those can not use the new AJAX call
			 * that the new Gutenberg editor uses. This is by design, for backward
			 * compatibility with plugins that use meta box fields.
			 * It cannot be avoided. $_POST is not available here.
			 *
			 * @see   https://github.com/WordPress/gutenberg/issues/15094.
			 * @since 2.2.0
			 */
			if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {

				$wp_block_post = $template->get_wp_block_post();

				if ( ! $wp_block_post ) {
					return;
				}

				wp_update_post(
					[
						'ID'           => $wp_block_post->ID,
						'post_status'  => $post->post_status,
						'post_title'   => $post->post_title,
						'post_content' => $post->post_content,
					],
					true
				);

			}

		}

		// If moving to Trash.
		if ( 'trash' === $new_status ) {

			if ( $wp_block_post = $template->get_wp_block_post() ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */
				wp_delete_post( $wp_block_post->ID, true );
			}

			wp_delete_post( $post->ID, true );
			wp_safe_redirect( add_query_arg( 'deleted', 1, admin_url( 'edit.php?post_type=bm_template' ) ) );
			exit;

		}

	}

	/**
	 * Add default post metadata when a post is added.
	 *
	 * @since 1.0.0
	 * @param int     $post_id The post ID.
	 * @param WP_Post $post    The post object.
	 * @param bool    $update  Whether this is an existing post being updated or not.
	 */
	public function add_default_meta( $post_id, $post, $update ) {

		if ( $update || ! $post || ! $post_id ) {
			return;
		}

		switch ( $post->post_type ) {

			case 'bm_list':
				$list = boldermail_get_list( $post );

				if ( ! $list ) {
					return;
				}

				$list->save_meta(
					array(
						'subscribed'   => 0,
						'unsubscribed' => 0,
						'unconfirmed'  => 0,
						'bounced'      => 0,
						'complained'   => 0,
						'opt_in_type'  => 'single',
						'opt_out_type' => 'single',
					)
				);

				break;

			case 'bm_newsletter':
			case 'bm_newsletter_ares':
				$newsletter = boldermail_get_newsletter( $post );

				if ( ! $newsletter ) {
					return;
				}

				/**
				 * For all new blog posts, set a meta variable that indicates
				 * the newsletter was created after Boldermail version 2.0.0.
				 *
				 * @since 2.0.0
				 */
				$newsletter->set_meta( 'use_block_editor', true );

				/**
				 * Insert a child Block Template post that will be used to design
				 * the HTML template.
				 *
				 * @since 2.0.0
				 */
				$block_template_post_id = wp_insert_post(
					[
						/* translators: Post ID. */
						'post_title'  => boldermail_sanitize_text( sprintf( __( 'Block Template %s', 'boldermail' ), $post->ID ) ),
						'post_status' => 'publish',
						'post_type'   => 'bm_block_template',
						'post_author' => $post->post_author,
						'post_parent' => $post->ID,
						'meta_input'  => array(
							'_preview' => array(
								'filter'          => 'display',
								'parent_meta_key' => 'html',
							),
						),
					]
				);

				$newsletter->set_meta( 'block_template_post_id', $block_template_post_id );

				break;

			case 'bm_newsletter_rss':
				$newsletter = boldermail_get_newsletter( $post );

				if ( ! $newsletter ) {
					return;
				}

				/**
				 * Stores the last time the plugin checked for new content
				 * when creating a campaign.
				 *
				 * @since 1.0.0
				 */
				$newsletter->set_meta( 'last_rss_check_time', current_time( 'mysql' ) );

				break;

			case 'bm_template':
				$template = boldermail_get_template( $post );

				if ( ! $template ) {
					return;
				}

				$this->add_default_template_meta( $template, $post );

				break;

			case 'bm_block_template':
				$block_template = boldermail_get_block_template( $post );

				if ( ! $block_template ) {
					return;
				}

				$block_template->set_meta( 'template_style', $this->get_template_style() );

				break;

		}

	}

	/**
	 * Add default post metadata when a template is added.
	 *
	 * @since 2.1.0
	 * @param Boldermail_Template $template The template object.
	 * @param WP_Post             $post     The post object.
	 */
	private function add_default_template_meta( $template, $post ) {

		/**
		 * The "Add New Block Template" button includes a `bm_block_template` request,
		 * like so: `wp-admin/post-new.php?post_type=bm_template&bm_block_template`.
		 * If this request is used, set the template to use the block editor.
		 *
		 * We use a sibling WP Block to copy the content of this post to.
		 * That way the block is available in the newsletter editor as a
		 * Reusable Block.
		 *
		 * @since 2.2.0
		 */
		if ( isset( $_GET['bm_block_template'] ) ) { /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */

			$template->set_meta( 'use_block_editor', true );

			// Insert a WP Block to synchronize this template to and use as a Reusable Block.
			$wp_block_post_id = wp_insert_post(
				[
					/* translators: Post ID. */
					'post_title'  => boldermail_sanitize_text( sprintf( __( 'Template %s', 'boldermail' ), $post->ID ) ),
					'post_type'   => 'wp_block',
					'post_status' => $post->post_status,
					'post_author' => $post->post_author,
					'post_parent' => $post->ID,
				]
			);

			$template->set_meta( 'wp_block_post_id', $wp_block_post_id );
			$template->set_meta( 'template_style', $this->get_template_style() );

		}

	}

	/**
	 * Save post during transition.
	 *
	 * For custom post types that do not support revisions, the transitions
	 * happen before saving the post, so we include this "hack" here to
	 * fix that issue. Otherwise, no data is saved and/or available to send
	 * to Boldermail.
	 *
	 * As of version 1.3.0, we do not check whether the post status is
	 * `published` to save the post. That's because version 1.3.0 introduced
	 * post statuses for newsletters and subscribers, so we have more cases
	 * where the post should be saved before sending the data to the mailing
	 * server. Just to be safe, we save the post data for any post transition.
	 *
	 * @since  1.0.0
	 * @param  string  $new_status New post status.
	 * @param  string  $old_status Old post status.
	 * @param  WP_Post $post       Post object.
	 */
	public function save_post_on_transition_post_status( $new_status, $old_status, $post ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundBeforeLastUsed */

		do_action( "save_post_{$post->post_type}", $post->ID, $post, true ); /* phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound */

	}

	/**
	 * Get the template styling (includes styling for preheader, header, body, and footer).
	 *
	 * @since  2.2.0
	 * @return string
	 */
	private function get_template_style() {

		return '\
		#bodyTable h1 {\
			color: #202020;\
			font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif;\
			font-size: 26px;\
			font-style: normal;\
			font-weight: bold;\
			line-height: 125%;\
			letter-spacing: normal;\
			text-align: center;\
		}\
		#bodyTable h2 {\
			color: #202020;\
			font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif;\
			font-size: 22px;\
			font-style: normal;\
			font-weight: bold;\
			line-height: 125%;\
			letter-spacing: normal;\
			text-align: left;\
		}\
		#bodyTable h3 {\
			color: #202020;\
			font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif;\
			font-size: 20px;\
			font-style: normal;\
			font-weight: bold;\
			line-height: 125%;\
			letter-spacing: normal;\
			text-align: left;\
		}\
		#bodyTable h4 {\
			color: #202020;\
			font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif;\
			font-size: 18px;\
			font-style: normal;\
			font-weight: bold;\
			line-height: 125%;\
			letter-spacing: normal;\
			text-align: left;\
		}\
		#templatePreheader .bmTextContent,\
		#templatePreheader .bmTextContent p {\
			color: #656565;\
			font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif;\
			font-size: 12px;\
			line-height: 150%;\
			text-align: left;\
		}\
		#templatePreheader .bmTextContent a,\
		#templatePreheader .bmTextContent p a {\
			color: #656565;\
			font-weight: normal;\
			text-decoration: underline;\
		}\
		#templateHeader .bmTextContent,\
		#templateHeader .bmTextContent p {\
			color: #202020;\
			font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif;\
			font-size: 16px;\
			line-height: 150%;\
			text-align: left;\
		}\
		#templateHeader .bmTextContent a,\
		#templateHeader .bmTextContent p a {\
			color: #2baadf;\
			font-weight: normal;\
			text-decoration: underline;\
		}\
		#templateBody .bmTextContent,\
		#templateBody .bmTextContent p {\
			color: #202020;\
			font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif;\
			font-size: 16px;\
			line-height: 150%;\
			text-align: left;\
		}\
		#templateBody .bmTextContent a,\
		#templateBody .bmTextContent p a {\
			color: #2baadf;\
			font-weight: normal;\
			text-decoration: underline;\
		}\
		#templateFooter .bmTextContent,\
		#templateFooter .bmTextContent p {\
			color: #656565;\
			font-family: Helvetica Neue, Helvetica, Arial, Verdana, sans-serif;\
			font-size: 12px;\
			line-height: 150%;\
			text-align: center;\
		}\
		#templateFooter .bmTextContent a,\
		#templateFooter .bmTextContent p a {\
			color: #656565;\
			font-weight: normal;\
			text-decoration: underline;\
		}\
		@media only screen and (max-width: 480px) {\
			#bodyTable h1 {\
				font-size: 22px !important;\
				line-height: 125% !important;\
			}\
			#bodyTable h2 {\
				font-size: 20px !important;\
				line-height: 125% !important;\
			}\
			#bodyTable h3 {\
				font-size: 18px !important;\
				line-height: 125% !important;\
			}\
			#bodyTable h4 {\
				font-size: 16px !important;\
				line-height: 150% !important;\
			}\
			#templatePreheader .bmTextContent,\
			#templatePreheader .bmTextContent p {\
				font-size: 14px !important;\
				line-height: 150% !important;\
			}\
			#templateHeader .bmTextContent,\
			#templateHeader .bmTextContent p {\
				font-size: 16px !important;\
				line-height: 150% !important;\
			}\
			#templateBody .bmTextContent,\
			#templateBody .bmTextContent p {\
				font-size: 16px !important;\
				line-height: 150% !important;\
			}\
			#templateFooter .bmTextContent,\
			#templateFooter .bmTextContent p {\
				font-size: 14px !important;\
				line-height: 150% !important;\
			}\
		}';

	}

}

Boldermail_Transitions::instance();
