<?php
/**
 * Newsletter data meta box.
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
 * Boldermail_Meta_Box_Newsletter_Data class.
 *
 * @since 1.0.0
 */
class Boldermail_Meta_Box_Newsletter_Data {

	/**
	 * Output the meta box.
	 *
	 * @since  1.0.0
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public static function output( $post ) {

		$newsletter = boldermail_get_newsletter( $post );

		if ( ! $newsletter ) {
			return;
		}

		wp_nonce_field( 'bm_newsletter_options_meta_box', 'bm_newsletter_options_nonce' );

		$newsletter_type = $newsletter->get_type();

		?>
		<div class="boldermail-panel-wrap newsletter-data">

			<ul class="newsletter-data-tabs boldermail-tabs">
				<?php foreach ( self::get_newsletter_data_tabs( $post ) as $key => $tab ) : ?>
					<?php if ( in_array( $newsletter_type, $tab['type'], true ) ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo esc_attr( isset( $tab['class'] ) ? implode( ' ', (array) $tab['class'] ) : '' ); ?>">
						<a href="#<?php echo esc_attr( $key ); ?>_panel"><span><?php echo esc_html( $tab['label'] ); ?></span></a>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php do_action( 'bm_newsletter_data_tabs' ); ?>
			</ul>

			<?php self::output_tabs( $newsletter ); ?>

			<?php do_action( 'bm_newsletter_data_panels' ); ?>

			<div class="clear"></div>
		</div>
		<?php

	}

	/**
	 * Return array of tabs to show.
	 *
	 * @since  1.0.0
	 * @param  WP_Post $post Post object.
	 * @return array
	 */
	private static function get_newsletter_data_tabs( $post ) {

		$tabs = apply_filters(
			'bm_newsletter_data_tabs_args',
			array(
				'autoresponder' => array(
					'label'    => __( 'Autoresponder', 'boldermail' ),
					'class'    => array(),
					'type'     => array( 'autoresponder' ),
					'priority' => 20,
				),
				'to'            => array(
					'label'    => __( 'To', 'boldermail' ),
					'class'    => array(),
					'type'     => array( 'regular', 'rss-feed' ),
					'priority' => 20,
				),
				'from'          => array(
					'label'    => __( 'From', 'boldermail' ),
					'class'    => array(),
					'type'     => array( 'regular', 'rss-feed', 'autoresponder' ),
					'priority' => 30,
				),
				'subject'       => array(
					'label'    => __( 'Subject Line', 'boldermail' ),
					'class'    => array(),
					'type'     => array( 'regular', 'rss-feed', 'autoresponder' ),
					'priority' => 40,
				),
				'design'        => array(
					'label'    => __( 'Design', 'boldermail' ),
					'class'    => array(),
					'type'     => array( 'regular', 'rss-feed', 'autoresponder' ),
					'priority' => 50,
				),
				'feed'          => array(
					'label'    => __( 'Feed', 'boldermail' ),
					'class'    => array(),
					'type'     => array( 'rss-feed' ),
					'priority' => 70,
				),
				'trigger'       => array(
					'label'    => __( 'Trigger', 'boldermail' ),
					'class'    => array(),
					'type'     => array( 'autoresponder' ),
					'priority' => 80,
				),
			),
			$post
		);

		// Sort tabs based on priority.
		uasort( $tabs, array( 'Boldermail_Meta_Boxes', 'tabs_sort_priority' ) );

		return $tabs;

	}

	/**
	 * Show tab content/settings.
	 *
	 * @since  1.0.0
	 * @param  Boldermail_Newsletter $newsletter Newsletter object.
	 * @return void
	 */
	private static function output_tabs( $newsletter ) {

		if ( $newsletter->get_type() === 'autoresponder' ) {
			include BOLDERMAIL_PLUGIN_DIR . 'partials/newsletter/html-boldermail-meta-box-newsletter-data-autoresponder.php';
		}

		include BOLDERMAIL_PLUGIN_DIR . 'partials/newsletter/html-boldermail-meta-box-newsletter-data-to.php';
		include BOLDERMAIL_PLUGIN_DIR . 'partials/newsletter/html-boldermail-meta-box-newsletter-data-from.php';
		include BOLDERMAIL_PLUGIN_DIR . 'partials/newsletter/html-boldermail-meta-box-newsletter-data-subject.php';
		include BOLDERMAIL_PLUGIN_DIR . 'partials/newsletter/html-boldermail-meta-box-newsletter-data-design.php';

		if ( $newsletter->get_type() === 'rss-feed' ) {
			include BOLDERMAIL_PLUGIN_DIR . 'partials/newsletter/html-boldermail-meta-box-newsletter-data-feed.php';
		}

		if ( $newsletter->get_type() === 'autoresponder' ) {
			include BOLDERMAIL_PLUGIN_DIR . 'partials/newsletter/html-boldermail-meta-box-newsletter-data-trigger.php';
		}

	}

	/**
	 * Save meta box data.
	 *
	 * @since  1.0.0
	 * @param  int     $post_id Post ID.
	 * @param  WP_Post $post    Post object.
	 * @return int|void
	 */
	public static function save( $post_id, $post ) {

		if ( ! isset( $_POST['bm_newsletter_options_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['bm_newsletter_options_nonce'] ), 'bm_newsletter_options_meta_box' ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$newsletter = boldermail_get_newsletter( $post );

		if ( ! $newsletter ) {
			return;
		}

		$meta_data = array();

		if ( in_array( $newsletter->get_type(), array( 'regular', 'rss-feed' ), true ) ) {

			// `select` with attribute `multiple` does not send a $_POST request if the user did not select any options.
			if ( isset( $_POST['_list_id'] ) ) {
				$list_id = is_array( $_POST['_list_id'] ) ? array_map( 'boldermail_sanitize_text', $_POST['_list_id'] ) : boldermail_sanitize_text( $_POST['_list_id'] );
			} else {
				$list_id = [];
			}

			$meta_data = array_merge(
				$meta_data,
				array(
					'list_id' => $list_id,
				)
			);

		}

		$post_data = array();

		if ( $newsletter->get_type() === 'autoresponder' ) {

			$post_data = array(
				'post_parent' => isset( $_POST['_autoresponder'] ) ? boldermail_sanitize_int( $_POST['_autoresponder'] ) : '',
			);

		}

		$meta_data = array_merge(
			$meta_data,
			array(
				'from_name'       => isset( $_POST['_from_name'] ) ? boldermail_sanitize_text( $_POST['_from_name'] ) : '',
				'from_email'      => isset( $_POST['_from_email'] ) ? boldermail_sanitize_email( $_POST['_from_email'] ) : '',
				'reply_to'        => isset( $_POST['_reply_to'] ) ? boldermail_sanitize_email( $_POST['_reply_to'] ) : '',
				'company_name'    => isset( $_POST['_company_name'] ) ? boldermail_sanitize_text( $_POST['_company_name'] ) : '',
				'company_address' => isset( $_POST['_company_address'] ) ? boldermail_sanitize_text( $_POST['_company_address'] ) : '',
				'permission'      => isset( $_POST['_permission'] ) ? boldermail_kses_post( $_POST['_permission'] ) : '',
				'subject'         => isset( $_POST['_subject'] ) ? boldermail_sanitize_text( $_POST['_subject'] ) : '',
				'preview_text'    => isset( $_POST['_preview_text'] ) ? boldermail_sanitize_text( $_POST['_preview_text'] ) : '',
			)
		);

		/**
		 * Switching is only possible from the Block Editor to the
		 * Classic Editor because we can import the HTML. Classic Editor
		 * to Block Editor is not possible because there is no way
		 * to generate the HTML markup necessary to describe the
		 * attributes of the blocks.
		 *
		 * When we switch from the Block Editor to the Classic Editor
		 * we also import the block HTML markup.
		 *
		 * @since 2.0.0
		 */
		if ( isset( $_POST['_use_block_editor'] ) && boldermail_sanitize_int( $_POST['_use_block_editor'] ) === 0 ) {

			$html = $newsletter->get_filtered_html( 'raw' );

			$meta_data = array_merge(
				$meta_data,
				array(
					'use_block_editor' => false,
					'html'             => ( preg_replace( '/\s+/', '', $html ) === '<!DOCTYPEhtml><html><head></head><body></body></html>' ) ? '' : $html,
				)
			);

		}

		if ( isset( $_POST['_html'] ) ) {

			$html = boldermail_kses_template( $_POST['_html'] );

			$meta_data = array_merge(
				$meta_data,
				array(
					'html' => ( preg_replace( '/\s+/', '', $html ) === '<!DOCTYPEhtml><html><head></head><body></body></html>' ) ? '' : $html,
				)
			);

		}

		if ( $newsletter->get_type() === 'rss-feed' ) {

			$meta_data = array_merge(
				$meta_data,
				array(
					'when_to_send'   => isset( $_POST['_when_to_send'] ) ? boldermail_sanitize_text( $_POST['_when_to_send'] ) : '',
					'which_days'     => isset( $_POST['_which_days'] ) ? array_map( 'boldermail_sanitize_text', (array) $_POST['_which_days'] ) : [ '' ],
					'what_day'       => isset( $_POST['_what_day'] ) ? boldermail_sanitize_text( $_POST['_what_day'] ) : '',
					'which_date'     => isset( $_POST['_which_date'] ) ? boldermail_sanitize_text( $_POST['_which_date'] ) : '',
					'what_time'      => isset( $_POST['_what_time'] ) ? boldermail_sanitize_text( $_POST['_what_time'] ) : '',
					'post_type'      => isset( $_POST['_post_type'] ) ? boldermail_sanitize_text( $_POST['_post_type'] ) : '',
					'taxonomy'       => isset( $_POST['_taxonomy'] ) ? boldermail_sanitize_text( $_POST['_taxonomy'] ) : '',
					'term__includes' => isset( $_POST['_term__includes'] ) ? array_map( 'boldermail_sanitize_int', (array) $_POST['_term__includes'] ) : [],
					'term__excludes' => isset( $_POST['_term__excludes'] ) ? array_map( 'boldermail_sanitize_int', (array) $_POST['_term__excludes'] ) : [],
				)
			);

		}

		if ( $newsletter->get_type() === 'autoresponder' ) {

			$meta_data = array_merge(
				$meta_data,
				array(
					'trigger_number'      => isset( $_POST['_trigger_number'] ) ? boldermail_sanitize_int( $_POST['_trigger_number'] ) : '',
					'trigger_interval'    => isset( $_POST['_trigger_interval'] ) ? boldermail_sanitize_option( $_POST['_trigger_interval'], array( 'immediately', 'minutes', 'hours', 'days', 'weeks', 'months' ) ) : '',
					'trigger_beforeafter' => isset( $_POST['_trigger_beforeafter'] ) ? boldermail_sanitize_option( $_POST['_trigger_beforeafter'], array( 'on', 'before', 'after' ) ) : '',
				)
			);

			$meta_data['trigger_number'] = ( 'immediately' === $meta_data['trigger_interval'] ) ? 0 : $meta_data['trigger_number'];

		}

		$newsletter->save_meta( $meta_data );
		$newsletter->wpdb_update( $post_data );

		do_action( 'boldermail_save_newsletter_meta', $post );

	}

	/**
	 * Save HTML meta box data on heartbeat.
	 *
	 * @since  1.0.0
	 * @param  array $response The Heartbeat response.
	 * @param  array $data     The $_POST data sent.
	 * @return array
	 */
	public static function save_on_heartbeat( $response, $data ) {

		$post_id = isset( $data['post'] ) ? boldermail_sanitize_int( $data['post'] ) : false;

		$newsletter = boldermail_get_newsletter( $post_id );

		if ( ! $newsletter ) {
			return $response;
		}

		if ( isset( $data['html'] ) ) {

			$html = boldermail_kses_template( $data['html'] );

			$newsletter->save_meta(
				array(
					'html' => preg_replace( '/\s+/', '', $html ) === '<!DOCTYPEhtml><html><head></head><body></body></html>' ? '' : $html,
				)
			);

		}

		return $response;

	}

}
