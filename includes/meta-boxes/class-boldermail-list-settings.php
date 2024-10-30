<?php
/**
 * List Settings meta box.
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
 * Boldermail_Meta_Box_List_Settings class.
 *
 * @since 1.0.0
 */
class Boldermail_Meta_Box_List_Settings {

	/**
	 * Output the meta box.
	 *
	 * @since  1.0.0
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public static function output( $post ) {

		if ( ! $post ) {
			return;
		}

		$list = boldermail_get_list( $post );

		if ( ! $list ) {
			return;
		}

		wp_nonce_field( 'bm_list_settings_meta_box', 'bm_list_settings_nonce' );

		?>
		<div class="boldermail-panel-wrap list-settings">

			<ul class="list-settings-tabs boldermail-tabs">
				<?php foreach ( self::get_list_settings_tabs( $post ) as $key => $tab ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo esc_attr( isset( $tab['class'] ) ? implode( ' ', (array) $tab['class'] ) : '' ); ?>">
						<a href="#<?php echo esc_attr( $key ); ?>_panel"><span><?php echo esc_html( $tab['label'] ); ?></span></a>
					</li>
				<?php endforeach; ?>

				<?php do_action( 'bm_list_settings_tabs' ); ?>
			</ul>

			<?php self::output_tabs( $list ); ?>

			<?php do_action( 'boldermail_list_settings_panels', $list ); ?>

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
	private static function get_list_settings_tabs( $post ) {

		$tabs = apply_filters(
			'boldermail_list_settings_tabs_args',
			array(
				'details'       => array(
					'label'    => __( 'List Details', 'boldermail' ),
					'class'    => array(),
					'priority' => 10,
				),
				'subscribe'     => array(
					'label'    => __( 'Subscribe Settings', 'boldermail' ),
					'class'    => array(),
					'priority' => 20,
				),
				'unsubscribe'   => array(
					'label'    => __( 'Unsubscribe Settings', 'boldermail' ),
					'class'    => array(),
					'priority' => 30,
				),
				'footer'        => array(
					'label'    => __( 'Footer Content', 'boldermail' ),
					'class'    => array(),
					'priority' => 40,
				),
				'custom_fields' => array(
					'label'    => __( 'Fields', 'boldermail' ),
					'class'    => array(),
					'priority' => 50,
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
	 * @param  Boldermail_List $list List object.
	 * @return void
	 */
	private static function output_tabs( $list ) { /* phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found */

		include BOLDERMAIL_PLUGIN_DIR . 'partials/list/html-boldermail-meta-box-list-settings-details.php';
		include BOLDERMAIL_PLUGIN_DIR . 'partials/list/html-boldermail-meta-box-list-settings-subscribe.php';
		include BOLDERMAIL_PLUGIN_DIR . 'partials/list/html-boldermail-meta-box-list-settings-unsubscribe.php';
		include BOLDERMAIL_PLUGIN_DIR . 'partials/list/html-boldermail-meta-box-list-settings-footer.php';
		include BOLDERMAIL_PLUGIN_DIR . 'partials/list/html-boldermail-meta-box-list-settings-custom-fields.php';

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

		if ( ! isset( $_POST['bm_list_settings_nonce'] ) || ! wp_verify_nonce( boldermail_sanitize_key( $_POST['bm_list_settings_nonce'] ), 'bm_list_settings_meta_box' ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$list = boldermail_get_list( $post );

		if ( ! $list ) {
			return;
		}

		$meta_data = array(
			'from_name'                  => isset( $_POST['_from_name'] ) ? boldermail_sanitize_text( $_POST['_from_name'] ) : '',
			'from_email'                 => isset( $_POST['_from_email'] ) ? boldermail_sanitize_email( $_POST['_from_email'] ) : '',
			'reply_to'                   => isset( $_POST['_reply_to'] ) ? boldermail_sanitize_email( $_POST['_reply_to'] ) : '',

			'opt_in_type'                => boldermail_sanitize_option( $_POST['_opt_in_type'], array( 'single', 'double' ) ),
			'subscribe_page'             => boldermail_sanitize_int( $_POST['_subscribe_page'] ),
			'already_subscribed_page'    => boldermail_sanitize_int( $_POST['_already_subscribed_page'] ),
			'send_thank_you_email'       => boldermail_sanitize_option( $_POST['_send_thank_you_email'], array( 0, 1 ) ),
			'thank_you_email_subject'    => boldermail_sanitize_text( $_POST['_thank_you_email_subject'] ),
			'thank_you_email_content'    => ( preg_replace( '/\s+/', '', $_POST['_thank_you_email_content'] ) == '<!DOCTYPEhtml><html><head></head><body></body></html>' ) ? '' : boldermail_kses_template( $_POST['_thank_you_email_content'] ),
			'confirmation_page'          => boldermail_sanitize_int( $_POST['_confirmation_page'] ),
			'confirmation_email_subject' => boldermail_sanitize_text( $_POST['_confirmation_email_subject'] ),
			'confirmation_email_content' => ( preg_replace( '/\s+/', '', $_POST['_confirmation_email_content'] ) == '<!DOCTYPEhtml><html><head></head><body></body></html>' ) ? '' : boldermail_kses_template( $_POST['_confirmation_email_content'] ),

			'opt_out_type'               => boldermail_sanitize_option( $_POST['_opt_out_type'], array( 'single', 'double' ) ),
			'unsubscribe_page'           => boldermail_sanitize_int( $_POST['_unsubscribe_page'] ),
			'send_unsubscribe_email'     => boldermail_sanitize_option( $_POST['_send_unsubscribe_email'], array( 0, 1 ) ),
			'unsubscribe_email_subject'  => boldermail_sanitize_text( $_POST['_unsubscribe_email_subject'] ),
			'unsubscribe_email_content'  => ( preg_replace( '/\s+/', '', $_POST['_unsubscribe_email_content'] ) == '<!DOCTYPEhtml><html><head></head><body></body></html>' ) ? '' : boldermail_kses_template( $_POST['_unsubscribe_email_content'] ),

			'company_name'               => isset( $_POST['_company_name'] ) ? boldermail_sanitize_text( $_POST['_company_name'] ) : '',
			'company_address'            => isset( $_POST['_company_address'] ) ? boldermail_sanitize_text( $_POST['_company_address'] ) : '',
			'permission'                 => isset( $_POST['_permission'] ) ? boldermail_sanitize_textarea( $_POST['_permission'] ) : '',
		);

		/**
		 * Do not check these boxes if the user forgot to enter
		 * the subject line and the content.
		 *
		 * @since   2.0.0
		 */
		$meta_data['send_thank_you_email'] = ( $meta_data['thank_you_email_subject'] && $meta_data['thank_you_email_content'] ) ? $meta_data['send_thank_you_email'] : 0;
		$meta_data['send_unsubscribe_email'] = ( $meta_data['unsubscribe_email_subject'] && $meta_data['unsubscribe_email_content'] ) ? $meta_data['send_unsubscribe_email'] : 0;

		/**
		 * Put posted custom fields data into an array.
		 *
		 * @since   1.6.0
		 */
		$product_addons = array();

		if ( isset( $_POST['product_addon_name'] ) ) {

			$addon_name = $_POST['product_addon_name'];
			$addon_type = $_POST['product_addon_type'];

			for ( $i = 0; $i < count( $addon_name ); $i++ ) {

				if ( ! isset( $addon_name[ $i ] ) || ( '' == $addon_name[ $i ] ) || ! isset( $addon_type[ $i ] ) || ( '' == $addon_type[ $i ] ) ) {
					continue;
				}

				$data = array();
				$data['name'] = boldermail_sanitize_text( $addon_name[ $i ] );
				$data['type'] = boldermail_sanitize_option( $addon_type[ $i ], array( 'Text', 'Number', 'Date' ) );

				if ( in_array( $data['name'], array_column( array_merge( Boldermail_List::get_core_fields(), Boldermail_List::get_default_fields(), $product_addons ), 'name' ) ) ) {
					continue;
				}

				$product_addons[] = $data;

			}

		}

		if ( ! empty( $_POST['import_product_addon'] ) ) {

			$import_addons = maybe_unserialize( maybe_unserialize( stripslashes( trim( $_POST['import_product_addon'] ) ) ) );

			if ( is_array( $import_addons ) && sizeof( $import_addons ) > 0 ) {

				foreach ( $import_addons as $addon ) {

					if ( ! isset( $addon['name'] ) || ( '' == $addon['name'] ) || ! isset( $addon['type'] ) || ( '' == $addon['type'] ) ) {
						continue;
					}

					$addon['name'] = boldermail_sanitize_text( $addon['name'] );
					$addon['type'] = boldermail_sanitize_option( $addon['type'], array( 'Text', 'Number', 'Date' ) );

					if ( in_array( $addon['name'], array_column( array_merge( Boldermail_List::get_core_fields(), Boldermail_List::get_default_fields(), $product_addons ), 'name' ) ) ) {
						continue;
					}

					$product_addons[] = $addon;

				}

			}

		}

		uasort( $product_addons, array( __CLASS__, 'custom_fields_sort' ) );

		$meta_data['custom_fields'] = $product_addons;

		$list->save_meta( $meta_data );

		do_action( 'boldermail_save_list_settings', $post );

	}

	/**
	 * Sort custom fields alphabetically and case-insensitive.
	 *
	 * @since  1.6.0
	 * @param  array $a First item to compare.
	 * @param  array $b Second item to compare.
	 * @return bool
	 */
	protected static function custom_fields_sort( $a, $b ) {

		return strcasecmp( $a['name'], $b['name'] );

	}

}
