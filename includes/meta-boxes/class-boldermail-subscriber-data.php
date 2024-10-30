<?php
/**
 * Subscriber Data meta box.
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
 * Boldermail_Meta_Box_Subscriber_Data class.
 *
 * @since   1.0.0
 */
class Boldermail_Meta_Box_Subscriber_Data {

	/**
	 * Output the meta box.
	 *
	 * @since   1.0.0
	 * @param   WP_Post     $post
	 * @return  void
	 */
	public static function output( $post ) {

		$subscriber = boldermail_get_subscriber( $post );

		if ( ! $subscriber ) {
			return;
		}

		wp_nonce_field( 'bm_subscriber_data_meta_box', 'bm_subscriber_data_nonce' );

		?>
		<div class="boldermail-panel-wrap subscriber-data">

			<ul class="subscriber-data-tabs boldermail-tabs">
				<?php foreach ( self::get_subscriber_data_tabs( $post ) as $key => $tab ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo esc_attr( isset( $tab['class'] ) ? implode( ' ', (array) $tab['class'] ) : '' ); ?>">
						<a href="#<?php echo esc_attr( $key ); ?>_panel"><span><?php echo esc_html( $tab['label'] ); ?></span></a>
					</li>
				<?php endforeach; ?>

				<?php do_action( 'bm_subscriber_data_tabs' ); ?>
			</ul>

			<?php self::output_tabs( $subscriber ); ?>

			<?php do_action( 'bm_subscriber_settings_panels' ); ?>

			<div class="clear"></div>
		</div>
		<?php

	}

	/**
	 * Return array of tabs to show.
	 *
	 * @since   1.0.0
	 * @param   WP_Post     $post
	 * @return  array
	 */
	private static function get_subscriber_data_tabs( $post ) {

		$tabs = apply_filters( 'bm_subscriber_data_tabs_args', array(
			'subscriber_data' => array(
				'label'    => __( 'Subscriber Data', 'boldermail' ),
				'class'    => array(),
				'priority' => 10,
			),
		), $post );

		// Sort tabs based on priority.
		uasort( $tabs, array( 'Boldermail_Meta_Boxes', 'tabs_sort_priority' ) );

		return $tabs;

	}

	/**
	 * Show tab content/settings.
	 *
	 * @since   1.0.0
	 * @param   Boldermail_Subscriber   $subscriber
	 * @return  void
	 */
	private static function output_tabs( $subscriber ) {

		include BOLDERMAIL_PLUGIN_DIR . 'partials/subscriber/html-boldermail-meta-box-subscriber-data.php';

	}

	/**
	 * Save meta box data.
	 *
	 * @since   1.0.0
	 * @param   int       $post_id
	 * @param   WP_Post   $post
	 * @return  void
	 */
	public static function save( $post_id, $post ) {

		if ( ! isset( $_POST['bm_subscriber_data_nonce'] ) || ! wp_verify_nonce( $_POST['bm_subscriber_data_nonce'], 'bm_subscriber_data_meta_box' ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$subscriber = boldermail_get_subscriber( $post );

		if ( ! $subscriber ) {
			return $post_id;
		}

		$name = boldermail_sanitize_text( $_POST['_name'] );
		$last_name = boldermail_sanitize_text( $_POST['_last_name'] );
		$email = boldermail_sanitize_email( $_POST['_email'] );
		$list_post_id = boldermail_sanitize_int( $_POST['_list'] );

		if ( isset ( $_POST['_custom_fields'] ) ) {

			$custom_fields = $_POST['_custom_fields'];
			$custom_fields_types = $_POST['_custom_fields_type'];

			foreach ( $custom_fields as $key => &$value ) {

				switch ( $custom_fields_types[ $key ] ) {

					case 'Text':
						$value = boldermail_sanitize_text( $value );
						break;

					case 'Number':
						$value = boldermail_sanitize_int( $value );
						break;

					case 'Date':
						$value = boldermail_sanitize_date( $value, 'Y-m-d' );
						break;

					default:
						$value = '';
						break;

				}

			}

		}

		$meta_data = array(
			'name' => $name,
			'custom_fields' => $custom_fields,
			'country' => boldermail_sanitize_text( $_POST['_country'] ),
			'skip_opt_in_confirm' => boldermail_sanitize_option( $_POST['_skip_opt_in_confirm'], array( 0, 1 ) ),
			'gdpr' => boldermail_sanitize_option( $_POST['_gdpr'], array( 0, 1 ) ),
		);

		$post_data = array(
			'post_title' => $email,
			'post_parent' => $list_post_id,
			'post_content' => $name . ' ' . $last_name . ' (' . $email . ')',
		);

		$subscriber->save_meta( $meta_data );
		$subscriber->wpdb_update( $post_data );

		do_action( 'boldermail_save_subscriber_settings', $post );

	}

}
