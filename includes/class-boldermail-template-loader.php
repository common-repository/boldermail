<?php
/**
 * Template loader.
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
 * Boldermail_Template_Loader class.
 *
 * @since 1.7.0
 */
class Boldermail_Template_Loader {

	/**
	 * Initialize the hooks.
	 *
	 * @since  1.7.0
	 * @return void
	 */
	public static function init() {

		add_action( 'template_redirect', array( __CLASS__, 'template_redirect' ) );

	}

	/**
	 * Redirect the public template for the newsletters.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function template_redirect() {

		global $post;

		if ( is_singular( [ 'bm_newsletter', 'bm_newsletter_ares', 'bm_block_template', 'bm_template' ] ) ) {

			$object = boldermail_get_object( $post );

			if ( ! $object ) {
				return;
			}

			echo $object->get_filtered_html(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */

			exit;

		}

	}

}

add_action( 'init', array( 'Boldermail_Template_Loader', 'init' ) );
