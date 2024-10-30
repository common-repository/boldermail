<?php
/**
 * List Information meta box.
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
 * Boldermail_Meta_Box_List_Information class.
 *
 * @since   1.0.0
 */
class Boldermail_Meta_Box_List_Information {

	/**
	 * Output the meta box.
	 *
	 * @since   1.0.0
	 * @param   WP_Post   $post
	 * @return  void
	 */
	public static function output( $post ) {

		$list = boldermail_get_list( $post );

		if ( ! $list ) {
			return;
		}

		$list->update_counts();

		?><div class="list-info-section list-info-api"><?php echo wp_kses_post( sprintf( '%1$s: <span class="list-id">%2$s</span>', __( 'List ID', 'boldermail' ), esc_html( $list->get_list_id() ) ) ); ?></div><?php

		$statuses = array( 'publish', 'subscribed', 'unsubscribed', 'unconfirmed', 'bounced', 'complained' );

		$count = array();

		foreach ( $statuses as $status ) {
			$count[$status] = $list->get_subscribers_count( $status );
		}

		include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-lists-list-table.php';

		?><div class="list-info-section list-info-all"><?php esc_html_e( 'Contacts', 'boldermail' ); ?>: <a href="<?php echo esc_url( Boldermail_Lists_List_Table::get_view_subscribers_url( $list ) ); ?>"><?php echo array_sum( $count ); ?></a></div><?php

		foreach ( $statuses as $status ) {

			if ( $status == 'publish' ) continue;

			?><div class="list-info-section list-info-<?php echo esc_attr( $status ); ?>"><?php echo esc_html( boldermail_get_subscriber_status_name( $status ) ); ?>: <a href="<?php echo esc_url( Boldermail_Lists_List_Table::get_view_subscribers_url( $list, $status ) ); ?>"><?php echo $count[$status]; ?></a><span class="list-info-percentage"><?php echo ( array_sum( $count ) ) ? number_format( $count[$status] / array_sum( $count ) * 100, 0 ) : 0; ?>%</span></div><?php
		}

	}

}
