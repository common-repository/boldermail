<?php
/**
 * Autoresponder Settings meta box.
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
 * Autoresponder Settings meta box class.
 *
 * @since   1.0.0
 */
class Boldermail_Meta_Box_Autoresponder_Settings {

	/**
	 * Output the meta box.
	 *
	 * @since   1.0.0
	 * @param   WP_Post     $post
	 */
	public static function output( $post ) {

		if ( ! $post ) {
			return;
		}

		$autoresponder = boldermail_get_autoresponder( $post );

		if ( ! $autoresponder ) {
			return;
		}

		wp_nonce_field( 'bm_autoresponder_settings_meta_box', 'bm_autoresponder_settings_nonce' );

		?>
		<div class="boldermail-panel-wrap autoresponder-settings">

			<ul class="autoresponder-settings-tabs boldermail-tabs">
				<?php foreach ( self::get_autoresponder_settings_tabs( $post ) as $key => $tab ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab <?php echo esc_attr( isset( $tab['class'] ) ? implode( ' ', (array) $tab['class'] ) : '' ); ?>">
						<a href="#<?php echo esc_attr( $key ); ?>_panel"><span><?php echo esc_html( $tab['label'] ); ?></span></a>
					</li>
				<?php endforeach; ?>

				<?php do_action( 'bm_autoresponder_settings_tabs' ); ?>
			</ul>

			<?php self::output_tabs( $autoresponder ); ?>

			<?php do_action( 'bm_autoresponder_settings_panels' ); ?>

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
	private static function get_autoresponder_settings_tabs( $post ) {

		$tabs = apply_filters( 'bm_autoresponder_settings_tabs_args', array(
			'autoresponder_list' => array(
				'label'    => __( 'List', 'boldermail' ),
				'class'    => array(),
				'priority' => 10,
			),
			'autoresponder_type' => array(
				'label'    => __( 'Type', 'boldermail' ),
				'class'    => array(),
				'priority' => 20,
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
	 * @param   Boldermail_Autoresponder    $autoresponder
	 */
	private static function output_tabs( $autoresponder ) {

		$is_editable = ( $autoresponder->get_status() === 'publish' ) ? false : true;

		include BOLDERMAIL_PLUGIN_DIR . 'partials/autoresponder/html-boldermail-meta-box-autoresponder-settings-list.php';
		include BOLDERMAIL_PLUGIN_DIR . 'partials/autoresponder/html-boldermail-meta-box-autoresponder-settings-type.php';

	}

	/**
	 * Save meta box data.
	 *
	 * @param int       $post_id
	 * @param WP_Post   $post
	 */
	public static function save( $post_id, $post ) {

		if ( ! isset( $_POST['bm_autoresponder_settings_nonce'] ) || ! wp_verify_nonce( $_POST['bm_autoresponder_settings_nonce'], 'bm_autoresponder_settings_meta_box' ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$autoresponder = boldermail_get_autoresponder( $post );

		if ( ! $autoresponder ) {
			return;
		}

		$meta_data = array(
			'type' => boldermail_sanitize_int( $_POST['_type'] ),
		);

		$post_data = array(
			'post_parent' => boldermail_sanitize_int( $_POST['_list'] ),
		);

		$autoresponder->save_meta( $meta_data );
		$autoresponder->wpdb_update( $post_data );

		do_action( 'boldermail_save_autoresponder_settings', $post );

	}

}
