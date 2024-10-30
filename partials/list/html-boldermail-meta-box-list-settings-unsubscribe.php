<?php
/**
 * Unsubscribe settings meta box panel.
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
<div id="unsubscribe_panel" class="boldermail-options-panel">

	<p><?php _e( "Define the way in which people will unsubscribe from your list.", 'boldermail' ); ?></p>

	<div class="options_group">
		<fieldset class="form-field">
			<label for="opt_out_type"><?php esc_html_e( 'Opt-out Type', 'boldermail' ); ?></label>
			<div id="opt_out_type" class="boldermail-inline-radios">
				<input type="radio" id="single_opt_out" name="_opt_out_type" value="single" <?php checked( 'single', $list->get_opt_out_type() ); ?> />
				<label for="single_opt_out"><?php esc_html_e( 'Single Opt-out', 'boldermail' ); ?></label>
				<?php echo boldermail_help_tip( esc_html__( "Subscribers will be immediately unsubscribed upon clicking on an unsubscribe link.", 'boldermail' ) ); ?>

				<input type="radio" id="double_opt_out" name="_opt_out_type" value="double" <?php checked( 'double', $list->get_opt_out_type() ); ?> />
				<label for="double_opt_out"><?php esc_html_e( 'Double Opt-out', 'boldermail' ); ?></label>
				<?php echo boldermail_help_tip( esc_html__( "Subscribers will be required to click a confirmation link in the unsubscribe page to complete their unsubscription.", 'boldermail' ) ); ?>
			</div>
		</fieldset>
	</div>

	<div class="options_group">
		<p class="form-field">
			<label for="unsubscribe_page"><?php esc_html_e( 'Unsubscribe Page', 'boldermail' ); ?></label>

			<select id="unsubscribe_page" name="_unsubscribe_page" class="short">
				<option value=""><?php echo esc_html_e( 'Select page', 'boldermail' ); ?></option>

				<?php $pages = get_pages(); ?>

				<?php foreach ( $pages as $page ) : ?>
					<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $page->ID, $list->get_unsubscribe_page() ); ?>><?php echo esc_html( $page->post_title ); ?></option>
				<?php endforeach; ?>

			</select>

			<?php echo boldermail_help_tip( esc_html__( "When users unsubscribe from a newsletter, they'll be sent to a generic unsubscription confirmation page. To redirect users to a page of your preference, enter the link below.", 'boldermail' ) ); ?>
		</p>
	</div>

	<div class="options_group">
		<fieldset class="form-field">
			<label for="send_unsubscribe_email-wrap"><?php esc_html_e( 'Send Unsubscribe Email', 'boldermail' ); ?></label>
			<div id="send_unsubscribe_email-wrap" class="boldermail-inline-checkboxes">
				<input type="checkbox" id="send_unsubscribe_email" name="_send_unsubscribe_email" value="1" <?php checked( '1', $list->send_unsubscribe_email() ); ?> />
				<label for="send_unsubscribe_email"><?php esc_html_e( 'Send user a confirmation email after they unsubscribe from a newsletter or through the API?', 'boldermail' ); ?></label>
			</div>
		</fieldset>

		<p class="form-field" data-boldermail-hide-if="input#send_unsubscribe_email:not(:checked)">
			<label for="unsubscribe_email_subject"><?php esc_html_e( 'Goodbye Subject', 'boldermail' ); ?></label>
			<input type="text" id="unsubscribe_email_subject" name="_unsubscribe_email_subject" value="<?php echo esc_attr( $list->get_unsubscribe_email_subject() ); ?>" class="regular-text" />
			<?php echo boldermail_help_tip( esc_html__( "Enter the subject line for the unsubscribe email.", 'boldermail' ) ); ?>
		</p>

		<fieldset class="form-field" data-boldermail-hide-if="input#send_unsubscribe_email:not(:checked)">
			<label for="unsubscribe_email_content"><?php esc_html_e( 'Goodbye Message', 'boldermail' ); ?></label>
			<?php boldermail_editor( $list->get_unsubscribe_email_content(), 'unsubscribe_email_content', array(
				'preview_meta' => array(
					'from_name' => 'from_name',
					'from_email' => 'from_email',
					'reply_to' => 'reply_to',
					'subject' => 'unsubscribe_email_subject',
					'content' => 'unsubscribe_email_content',
				),
			) ); ?>
			<span class="description"><?php boldermail_kses_post_e( "Write a message to your contacts confirming they have been successfully unsubscribed. You can use the shortcode <code>[boldermail_resubscribe]</code> somewhere in your message.", 'boldermail' ); ?></span>
		</fieldset>
	</div>

</div>
<?php
