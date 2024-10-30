<?php
/**
 * "Autoresponder" meta box panel.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 *
 * @var        Boldermail_Newsletter_Autoresponder $newsletter Newsletter object.
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="autoresponder_panel" class="panel boldermail-options-panel">
	<?php global $pagenow; ?>

	<p><?php esc_html_e( 'To which automation configuration are you adding this automating email to?', 'boldermail' ); ?></p>

	<?php
	boldermail_wp_select(
		[
			'id'                => 'autoresponder',
			'label'             => __( 'Autoresponder', 'boldermail' ),
			'name'              => '_autoresponder',
			'value'             => ( 'post-new.php' === $pagenow && isset( $_GET['post_type'] ) && 'bm_newsletter_ares' === $_GET['post_type'] && isset( $_GET['autoresponder'] ) && empty( $newsletter->get_autoresponder_post_id() ) ) ? boldermail_sanitize_int( $_GET['autoresponder'] ) : $newsletter->get_autoresponder_post_id(), /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
			'options'           => array_replace( // Use `array_replace` instead of `array_merge` to preserve keys.
				[
					'' => __( 'Select autoresponder', 'boldermail' ),
				],
				boldermail_get_autoresponders_names()
			),
			'editable'          => ! $newsletter->is_published(), // Use `is_published` only for this input instead of `is_editable` because once the automated email is inserted in the Sendy database, we can't change its autoresponder parent easily.
			'custom_attributes' => array( 'required' => '' ),
		]
	);
	?>
</div>
<?php
