<?php
/**
 * Template HTML meta box.
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
 * Boldermail_Meta_Box_Template_HTML class.
 *
 * @since 1.0.0
 */
class Boldermail_Meta_Box_Template_HTML {

	/**
	 * Output the HTML meta box.
	 *
	 * @since  1.0.0
	 * @param  WP_Post $post Post object.
	 * @return void
	 */
	public static function output( $post ) {

		if ( ! $post ) {
			return;
		}

		$template = boldermail_get_template( $post->ID );

		if ( ! $template ) {
			return;
		}

		wp_nonce_field( 'boldermail_template_html_meta_box', 'boldermail_template_html_nonce' );

		boldermail_editor(
			$template->get_html(),
			'html',
			array(
				'preview_meta' => array(
					'subject' => 'title',
					'content' => 'html',
					'filter'  => 'raw',
				),
			)
		);

	}

	/**
	 * Save HTML meta box data.
	 *
	 * @since  1.0.0
	 * @param  int     $post_id Post ID.
	 * @param  WP_Post $post    Post object.
	 * @return int|void
	 */
	public static function save( $post_id, $post ) {

		if ( ! isset( $_POST['boldermail_template_html_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['boldermail_template_html_nonce'] ), 'boldermail_template_html_meta_box' ) ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$template = boldermail_get_template( $post );

		if ( ! $template ) {
			return;
		}

		if ( isset( $_POST['_html'] ) ) {

			$html = boldermail_kses_template( $_POST['_html'] );

			$template->save_meta(
				array(
					'html' => preg_replace( '/\s+/', '', $html ) === '<!DOCTYPEhtml><html><head></head><body></body></html>' ? '' : $html,
				)
			);

		}

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

		$template = boldermail_get_template( $post_id );

		if ( ! $template ) {
			return $response;
		}

		if ( isset( $data['html'] ) ) {

			$html = boldermail_kses_template( $data['html'] );

			$template->save_meta(
				array(
					'html' => preg_replace( '/\s+/', '', $html ) === '<!DOCTYPEhtml><html><head></head><body></body></html>' ? '' : $html,
				)
			);

		}

		return $response;

	}

}
