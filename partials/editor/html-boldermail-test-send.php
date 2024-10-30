<?php
/**
 * Send a test email.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="test-send-email-<?php echo esc_attr( $this->editor_id ); ?>" class="test-send-email boldermail-editor-meta-box">

	<button type="button" class="boldermail-handlediv">
		<span class="screen-reader-text"><?php esc_html_e( 'Close panel: Send a Test Email', 'boldermail' ); ?></span>
		<span class="boldermail-close-indicator"></span>
	</button>
	<h3 class="boldermail-hndle"><span><?php esc_html_e( 'Send a Test Email', 'boldermail' ); ?></span></h3>

	<div class="boldermail-editor-meta-box-inside">

		<div class="boldermail-options-panel">

			<?php echo '<p>' . wp_kses_post( __( 'Subscriber-related shortcodes like <code>[boldermail_name]</code> or <code>[boldermail_email]</code> will not render in previews or test emails. If you want to see how shortcodes will render for subscribers, create a test list, and send a test campaign with your template.', 'boldermail' ) ) . '</p>'; ?>

			<p class="form-field">
				<label for="test_email"><?php esc_html_e( 'Send a test email to:', 'boldermail' ); ?></label>
				<input type="text" id="test_email" name="_test_email" value="" placeholder="Ex: hernan@boldermail.com, lindsey@thepostmansknock.com..." class="regular-text" />
				<?php echo boldermail_help_tip( __( 'Use commas to separate multiple emails', 'boldermail' ) ); ?>
			</p>

			<p class="form-field">
				<input type="submit" name="save" class="button" value="<?php esc_html_e( 'Send Test Email', 'boldermail' ); ?>" data-editor="<?php echo esc_attr( $this->editor_id ); ?>" data-preview="<?php echo esc_attr( serialize( $this->preview_meta ) ); /* phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize */ ?>">
				<span class="spinner"></span>
			</p>

			<div class="test-send-response">
			</div>

		</div>
	</div>
</div>
<?php
