<?php
/**
 * Post Submit meta box.
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
 * Boldermail_Meta_Box_Submit class.
 *
 * @since 1.0.0
 */
class Boldermail_Meta_Box_Submit {

	/**
	 * Output the publishing actions.
	 *
	 * @since  1.0.0
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public static function output( $post ) {

		if ( ! $post ) {
			return;
		}

		/**
		 * Customize the publishing/deleting action.
		 *
		 * @since  1.7.0
		 */
		add_action( 'boldermail_submitbox_misc_actions', array( __CLASS__, 'submitbox_misc_actions' ), 10, 1 );

		?>
		<div class="submitbox" id="submitpost">

			<!-- Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key -->
			<div style="display:none;"><?php submit_button( __( 'Save', 'boldermail' ), '', 'save' ); ?></div>

			<!-- Miscellaneous publishing actions -->
			<div id="misc-publishing-actions">
				<?php do_action( 'boldermail_submitbox_misc_actions', $post ); ?>
			</div>

			<!-- Major publishing actions -->
			<div id="major-publishing-actions">
				<?php if ( 'auto-draft' !== $post->post_status ) : ?>
					<?php do_action( 'boldermail_submitbox_delete_action', $post ); ?>
				<?php endif; ?>
				<?php do_action( 'boldermail_submitbox_publishing_action', $post ); ?>
			</div>

		</div>
		<?php

	}

	/**
	 * Delete button.
	 *
	 * @since  1.0.0
	 * @param  WP_Post $post The post object.
	 * @return void
	 */
	public static function delete_action( $post ) {

		if ( ! $post ) {
			return;
		}

		echo '<!-- Delete action -->';
		echo '<div id="delete-action">';

		if ( current_user_can( 'delete_post', $post->ID ) ) {
			if ( ! EMPTY_TRASH_DAYS ) {
				$delete_text = __( 'Delete Permanently', 'boldermail' );
			} else {
				$delete_text = __( 'Move to Trash', 'boldermail' );
			}

			echo '<a class="submitdelete deletion button button-secondary boldermail-button dashicons-before dashicons-trash" href="' . esc_url( get_delete_post_link( $post->ID ) ) . '">' . esc_html( $delete_text ) . '</a>';
		}

		echo '</div>';

	}

	/**
	 * Publishing button.
	 *
	 * @since  1.0.0
	 * @param  WP_Post $post The post object.
	 * @return void
	 */
	public static function publishing_action( $post ) {

		if ( ! $post ) {
			return;
		}

		$post_type        = $post->post_type;
		$post_type_object = get_post_type_object( $post_type );
		$can_publish      = current_user_can( $post_type_object->cap->publish_posts );

		$post_statuses       = array( 'publish', 'future', 'private' );
		$newsletter_statuses = array( 'preparing', 'sending', 'sent', 'enabled' );  // Do not include 'paused'.
		$subscriber_statuses = array( 'subscribed', 'unconfirmed', 'unsubscribed', 'bounced', 'complained' );

		$post_statuses = array_merge( $post_statuses, $newsletter_statuses, $subscriber_statuses );

		?>
		<!-- Publishing actions -->
		<div id="publishing-action">
		<?php if ( ! in_array( $post->post_status, $post_statuses, true ) || 0 === $post->ID ) : ?>
				<?php if ( $can_publish ) : ?>
					<?php if ( ! empty( $post->post_date_gmt ) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php echo esc_attr_x( 'Schedule', 'post action/button label', 'boldermail' ); ?>" />
						<?php submit_button( _x( 'Schedule', 'post action/button label', 'boldermail' ), 'primary', 'publish', false ); ?>
					<?php else : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Publish', 'boldermail' ); ?>" />
						<?php submit_button( __( 'Publish', 'boldermail' ), 'primary', 'publish', false ); ?>
					<?php endif; ?>
				<?php else : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Submit for Review', 'boldermail' ); ?>" />
						<?php submit_button( __( 'Submit for Review', 'boldermail' ), 'primary', 'publish', false ); ?>
				<?php endif; ?>
			<?php else : ?>
			<input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update', 'boldermail' ); ?>" />
			<input name="save" type="submit" class="button button-primary" id="publish" value="<?php esc_attr_e( 'Update', 'boldermail' ); ?>" />
			<?php endif; ?>
			<span class="spinner"></span>
		</div>
		<?php

	}

	/**
	 * Modify the publishing actions.
	 *
	 * @since  1.7.0
	 * @param  WP_Post $post The post object.
	 * @return void
	 */
	public static function submitbox_misc_actions( $post ) {

		switch ( $post->post_type ) {

			case 'bm_list':
				$list = boldermail_get_list( $post );

				if ( ! $list ) {
					return;
				}

				self::submitbox_list_misc_actions( $list );
				break;

			case 'bm_template':
				$template = boldermail_get_template( $post );

				if ( ! $template ) {
					return;
				}

				self::submitbox_template_misc_actions( $template );
				break;

			case 'bm_newsletter':
			case 'bm_newsletter_rss':
			case 'bm_newsletter_ares':
				$newsletter = boldermail_get_newsletter( $post );

				if ( ! $newsletter ) {
					return;
				}

				self::submitbox_newsletter_misc_actions( $newsletter );
				break;

			case 'bm_autoresponder':
				$autoresponder = boldermail_get_autoresponder( $post );

				if ( ! $autoresponder ) {
					return;
				}

				self::submitbox_autoresponder_misc_actions( $autoresponder );
				break;

			case 'bm_subscriber':
				$subscriber = boldermail_get_subscriber( $post, 'update' );

				if ( ! $subscriber ) {
					return;
				}

				self::submitbox_subscriber_misc_actions( $subscriber );
				break;

		}

	}

	/**
	 * Modify the publishing actions for a newsletter.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Newsletter_Regular|Boldermail_Newsletter_RSS_Feed|Boldermail_Newsletter_Autoresponder $newsletter The newsletter object.
	 * @return void
	 */
	private static function submitbox_newsletter_misc_actions( $newsletter ) {

		include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-regular-list-table.php';
		include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-rss-feed-list-table.php';
		include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-newsletters-autoresponder-list-table.php';

		$newsletter_type = $newsletter->get_type();

		if ( 'rss-feed' === $newsletter_type && $newsletter->is_enabled() ) {
			remove_action( 'boldermail_submitbox_publishing_action', array( 'Boldermail_Meta_Box_Submit', 'publishing_action' ), 10 );
		}

		if ( in_array( $newsletter_type, array( 'rss-feed', 'autoresponder' ), true ) && $newsletter->is_enabled() ) {

			$pause_text = ( 'rss-feed' === $newsletter_type ) ? __( 'Pause Campaign', 'boldermail' ) : __( 'Pause Email', 'boldermail' );
			echo '<a href="' . esc_url( Boldermail_Newsletters_List_Table::get_pause_url( $newsletter ) ) . '" id="pause_newsletter" class="button button-secondary boldermail-button dashicons-before dashicons-controls-pause">' . esc_html( $pause_text ) . '</a> ';

		}

		if ( in_array( $newsletter_type, array( 'regular', 'autoresponder' ), true ) && $newsletter->is_published() ) {

			echo '<a href="' . esc_url( get_permalink( $newsletter->get_post_id() ) ) . '" id="preview_newsletter" class="button button-secondary boldermail-button dashicons-before dashicons-visibility">' . esc_html__( 'Preview', 'boldermail' ) . '</a> ';

		}

		if ( 'rss-feed' === $newsletter_type && $newsletter->is_published() ) {

			echo '<a href="' . esc_url( Boldermail_Newsletters_RSS_Feed_List_Table::get_view_emails_url( $newsletter ) ) . '" id="view_emails" class="button button-secondary boldermail-button dashicons-before dashicons-visibility">' . esc_html__( 'View Emails', 'boldermail' ) . '</a> ';

		}

		if ( $newsletter->is_published() ) {

			echo '<a href="' . esc_url( Boldermail_Newsletters_List_Table::get_duplicate_url( $newsletter ) ) . '" id="duplicate_newsletter" class="button button-secondary boldermail-button dashicons-before dashicons-admin-page">' . esc_html__( 'Duplicate', 'boldermail' ) . '</a> ';

			if ( 'regular' === $newsletter_type ) {
				echo '<a href="' . esc_url( Boldermail_Newsletters_Regular_List_Table::get_view_report_url( $newsletter ) ) . '" id="view-newsletter-report" class="button button-secondary boldermail-button dashicons-before dashicons-chart-bar" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View report', 'boldermail' ) . '</a> ';
			}

			if ( 'autoresponder' === $newsletter_type ) {
				echo '<a href="' . esc_url( Boldermail_Newsletters_Autoresponder_List_Table::get_view_report_url( $newsletter ) ) . '" id="view-newsletter-report" class="button button-secondary boldermail-button dashicons-before dashicons-chart-bar" target="_blank" rel="noopener noreferrer">' . esc_html__( 'View report', 'boldermail' ) . '</a> ';
			}

		}

		if ( 'rss-feed' === $newsletter_type && ! $newsletter->is_enabled() ) {
			echo '<input type="submit" name="save" value="' . esc_attr__( 'Save Changes', 'boldermail' ) . '" class="button button-secondary" />';
		}

		if ( 'autoresponder' === $newsletter_type && ! $newsletter->is_published() ) {
			echo '<input type="submit" name="save" value="' . esc_attr__( 'Save Changes', 'boldermail' ) . '" class="button button-secondary" />';
		}

	}

	/**
	 * Modify the publishing actions for a list.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_List $list The list object.
	 * @return void
	 */
	private static function submitbox_list_misc_actions( Boldermail_List $list ) {

		remove_action( 'boldermail_submitbox_delete_action', array( 'Boldermail_Meta_Box_Submit', 'delete_action' ), 10 );

		include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-lists-list-table.php';

		$add_subscriber_url     = Boldermail_Lists_List_Table::get_add_subscriber_url( $list );
		$import_subscribers_url = Boldermail_Lists_List_Table::get_import_subscribers_url( $list );
		$add_autoresponder_url  = Boldermail_Lists_List_Table::get_add_autoresponder_url( $list );

		if ( in_array( $list->get_status(), array( 'publish' ), true ) && 0 !== $list->get_post_id() ) {
			echo '<!-- Subscriber actions -->';
			echo '<a href="' . esc_url( $add_subscriber_url ) . '" id="add_subscriber" class="button button-secondary boldermail-button dashicons-before dashicons-plus-alt">' . esc_html__( 'Add Subscriber', 'boldermail' ) . '</a> ';
			echo '<a href="' . esc_url( $import_subscribers_url ) . '" id="import_subscribers" class="button button-secondary boldermail-button dashicons-before dashicons-upload">' . esc_html__( 'Import Subscribers', 'boldermail' ) . '</a> ';
			echo '<!--<button name="action" value="bulk_unsubscribe" class="button button-secondary boldermail-button dashicons-before dashicons-editor-removeformatting">' . esc_html__( 'Delete Subscribers', 'boldermail' ) . '</button>--> ';
			echo '<!--<button name="action" value="bulk_delete" class="button button-secondary boldermail-button dashicons-before dashicons-dismiss">' . esc_html__( 'Bulk Unsubscribe', 'boldermail' ) . '</button>--> ';
			echo '<a href="' . esc_url( $add_autoresponder_url ) . '" id="add_autoresponder" class="button button-secondary boldermail-button dashicons-before dashicons-update">' . esc_html__( 'Add Automation', 'boldermail' ) . '</a> ';
		}

	}

	/**
	 * Modify the publishing actions for a template.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Template $template The template object.
	 * @return void
	 */
	private static function submitbox_template_misc_actions( Boldermail_Template $template ) {

		if ( ! $template->is_published() ) {
			echo '<input type="submit" name="save" value="' . esc_attr__( 'Save Changes', 'boldermail' ) . '" class="button button-secondary" />';
		}

	}

	/**
	 * Modify the publishing actions for an autoresponder.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Autoresponder $autoresponder The autoresponder object.
	 * @return void
	 */
	private static function submitbox_autoresponder_misc_actions( Boldermail_Autoresponder $autoresponder ) {

		remove_action( 'boldermail_submitbox_delete_action', array( 'Boldermail_Meta_Box_Submit', 'delete_action' ), 10 );

		if ( $autoresponder->get_status() === 'publish' ) {

			remove_action( 'boldermail_submitbox_publishing_action', array( 'Boldermail_Meta_Box_Submit', 'publishing_action' ), 10 );

			include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-autoresponders-list-table.php';

			$add_emails_url  = Boldermail_Autoresponders_List_Table::get_add_email_url( $autoresponder );
			$view_emails_url = Boldermail_Autoresponders_List_Table::get_view_emails_url( $autoresponder );

			echo '<!-- Autoresponder actions -->';
			echo '<a href="' . esc_url( $add_emails_url ) . '" id="add_email" class="button button-secondary boldermail-button dashicons-before dashicons-update">' . esc_html__( 'Add Automated Email', 'boldermail' ) . '</a> ';
			echo '<a href="' . esc_url( $view_emails_url ) . '" id="view_emails" class="button button-secondary boldermail-button dashicons-before dashicons-visibility">' . esc_html__( 'View Automated Emails', 'boldermail' ) . '</a> ';

		}

	}

	/**
	 * Modify the publishing actions for a subscriber.
	 *
	 * @since  1.7.0
	 * @param  Boldermail_Subscriber $subscriber The subscriber object.
	 * @return void
	 */
	private static function submitbox_subscriber_misc_actions( Boldermail_Subscriber $subscriber ) {

		if ( $subscriber->get_status() === 'subscribed' ) {
			include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-subscribers-list-table.php';
			echo '<a href="' . esc_url( Boldermail_Subscribers_List_Table::get_unsubscribe_link( $subscriber ) ) . '" id="unsubscribe" class="button button-secondary boldermail-button dashicons-before dashicons-dismiss">' . esc_html__( 'Unsubscribe', 'boldermail' ) . '</a>';
		}

		// If user is unsubscribed, remove default "Publish" and "Update" buttons
		// because they will re-add the subscriber.
		if ( $subscriber->get_status() === 'unsubscribed' ) {
			remove_action( 'boldermail_submitbox_publishing_action', array( 'Boldermail_Meta_Box_Submit', 'publishing_action' ), 10 );
			echo '<input name="save" type="submit" class="button button-primary" id="publish" value="' . esc_attr__( 'Resubscribe', 'boldermail' ) . '" />';
		}

	}

}
