<?php
/**
 * Autoresponder class.
 *
 * The Boldermail Autoresponder class.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

require_once BOLDERMAIL_PLUGIN_DIR . 'includes/posts/class-boldermail-post.php';

/**
 * Boldermail_Autoresponder class.
 *
 * @since 1.4.0
 */
class Boldermail_Autoresponder extends Boldermail_Post {

	/**
	 * Get the autoresponder ID for the Boldermail server.
	 *
	 * @since  1.4.0
	 * @return int Autoresponder ID.
	 */
	public function get_autoresponder_id() {

		return absint( $this->get_meta( 'id' ) );

	}

	/**
	 * Get the list ID for the Boldermail server.
	 *
	 * @since  1.4.0
	 * @return string
	 */
	public function get_list_id() {

		$list_post_id = wp_get_post_parent_id( $this->get_post_id() );

		if ( $list_post_id && $list = boldermail_get_list( $list_post_id ) ) { /* phpcs:ignore WordPress.CodeAnalysis.AssignmentInCondition.Found */
			return $list->get_list_id();
		}

		return '';

	}

	/**
	 * Get the list post ID.
	 *
	 * @since  1.4.0
	 * @return int
	 */
	public function get_list_post_id() {

		return wp_get_post_parent_id( $this->get_post_id() );

	}

	/**
	 * Get the autoresponder name.
	 *
	 * @since  1.4.0
	 * @return string Autoresponder name.
	 */
	public function get_name() {

		return get_the_title( $this->ID );

	}

	/**
	 * Get the autoresponder type.
	 *
	 * @since  1.4.0
	 * @return int Autoresponder type.
	 */
	public function get_type() : int {

		return absint( $this->get_meta( 'type' ) );

	}

	/**
	 * Get the automated newsletter objects.
	 *
	 * @since  1.4.0
	 * @return Boldermail_Newsletter[]
	 */
	public function get_newsletters() {

		return array_map( 'boldermail_get_newsletter', $this->get_children() );

	}

}
