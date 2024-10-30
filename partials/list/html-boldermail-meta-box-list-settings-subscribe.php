<?php
/**
 * Subscribe settings meta box panel.
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
<div id="subscribe_panel" class="boldermail-options-panel">

	<p><?php _e( "Define the way in which people will subscribe to your list.", 'boldermail' ); ?></p>

	<div class="options_group">
		<fieldset class="form-field">
			<label for="opt_in_type"><?php esc_html_e( 'Opt-in Type', 'boldermail' ); ?></label>
			<div id="opt_in_type" class="boldermail-inline-radios">
				<input type="radio" id="single_opt_in" name="_opt_in_type" value="single" <?php checked( 'single', $list->get_opt_in_type() ); ?> />
				<label for="single_opt_in"><?php esc_html_e( 'Single Opt-in', 'boldermail' ); ?></label>

				<?php echo boldermail_help_tip( esc_html__(
					"Single opt-in is when subscribers sign up via a subscription form, and are immediately added to your email list.
					 There is no need for further confirmation, and they’ll start getting your emails straight away.", 'boldermail' ) ); ?>

				<input type="radio" id="double_opt_in" name="_opt_in_type" value="double" <?php checked( 'double', $list->get_opt_in_type() ); ?> />
				<label for="double_opt_in"><?php esc_html_e( 'Double Opt-in', 'boldermail' ); ?></label>

				<?php echo boldermail_help_tip( esc_html__(
					"Double opt-in is when subscribers sign up via your subscription form,
					 and then get an email with a link they have to click to confirm that they want to get emails from you.
					 Subscribers must confirm, or they won’t get your emails.", 'boldermail' ) ); ?>

			</div>
		</fieldset>
	</div>

	<div class="options_group">
		<p class="form-field">
			<label for="subscribe_page"><?php esc_html_e( 'Subscribe Page', 'boldermail' ); ?></label>

			<select id="subscribe_page" name="_subscribe_page" class="short">
				<option value=""><?php echo esc_html_e( 'Select page', 'boldermail' ); ?></option>

				<?php $pages = get_pages(); ?>

				<?php foreach ( $pages as $page ) : ?>
					<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $page->ID, $list->get_subscribe_page() ); ?>><?php echo esc_html( $page->post_title ); ?></option>
				<?php endforeach; ?>

			</select>

			<?php echo boldermail_help_tip( __(
				"When users subscribe through the subscribe form, they'll be sent to a generic subscription confirmation page.
				 Select a page to redirect users to a page of your preference. If you chose double opt-in as your Opt-in Type,
				 this page should tell them a confirmation email has been sent to them.", 'boldermail' ) ); ?>
		</p>

		<p class="form-field">
			<label for="already_subscribed_page"><?php esc_html_e( 'Already Subscribed Page', 'boldermail' ); ?></label>

			<select id="already_subscribed_page" name="_already_subscribed_page" class="short">
				<option value=""><?php echo esc_html_e( 'Select page', 'boldermail' ); ?></option>

				<?php $pages = get_pages(); ?>

				<?php foreach ( $pages as $page ) : ?>
					<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $page->ID, $list->get_already_subscribed_page() ); ?>><?php echo esc_html( $page->post_title ); ?></option>
				<?php endforeach; ?>

			</select>

			<?php echo boldermail_help_tip( __(
				"When users that are already subscribed resubscribe again through a subscription form, they'll be sent to a generic confirmation page.
				 Select a page to redirect users to a page of your preference. If you chose double opt-in as your Opt-in Type,
				 this page should tell them a confirmation email has been sent to them.", 'boldermail' ) ); ?>
		</p>
	</div>

	<div class="options_group">
		<fieldset class="form-field">
			<label for="send_thank_you_email-wrap"><?php esc_html_e( 'Send Thank You Email', 'boldermail' ); ?></label>
			<div id="send_thank_you_email-wrap" class="boldermail-inline-checkboxes">
				<input type="checkbox" id="send_thank_you_email" name="_send_thank_you_email" value="1" <?php checked( '1', $list->send_thank_you_email() ); ?> />
				<label for="send_thank_you_email"><?php esc_html_e( 'Send contact a thank you email after they either subscribe through the subscribe form (if single opt-in) or confirm their subscription (if double opt-in)?', 'boldermail' ); ?></label>
			</div>
		</fieldset>

		<p class="form-field" data-boldermail-hide-if="input#send_thank_you_email:not(:checked)">
			<label for="thank_you_email_subject"><?php esc_html_e( 'Thank You Subject', 'boldermail' ); ?></label>
			<input type="text" id="thank_you_email_subject" name="_thank_you_email_subject" value="<?php echo esc_attr( $list->get_thank_you_email_subject() ); ?>" class="regular-text" />
			<?php echo boldermail_help_tip( esc_html__( "Enter the subject line for the email.", 'boldermail' ) ); ?>
		</p>

		<fieldset class="form-field" data-boldermail-hide-if="input#send_thank_you_email:not(:checked)">
			<label for="thank_you_email_content"><?php esc_html_e( 'Thank You Message', 'boldermail' ); ?></label>
			<?php boldermail_editor( $list->get_thank_you_email_content(), 'thank_you_email_content', array(
				'preview_meta' => array(
					'from_name' => 'from_name',
					'from_email' => 'from_email',
					'reply_to' => 'reply_to',
					'subject' => 'thank_you_email_subject',
					'content' => 'thank_you_email_content',
				),
			) ); ?>
		</fieldset>
	</div>

	<div class="options_group">
		<p class="form-field" data-boldermail-hide-if="input#single_opt_in:checked">
			<label for="confirmation_page"><?php esc_html_e( 'Confirmation Page', 'boldermail' ); ?></label>

			<select id="confirmation_page" name="_confirmation_page" class="short">
				<option value=""><?php echo esc_html_e( 'Select page', 'boldermail' ); ?></option>

				<?php $pages = get_pages(); ?>

				<?php foreach ( $pages as $page ) : ?>
					<option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $page->ID, $list->get_confirmation_page() ); ?>><?php echo esc_html( $page->post_title ); ?></option>
				<?php endforeach; ?>

			</select>

			<?php echo boldermail_help_tip( esc_html__( "If your List type is double opt-in, users who clicked the confirmation URL will be sent to a generic confirmation page. To redirect users to a page of your preference, enter the link above.", 'boldermail' ) ); ?>
		</p>

		<p class="form-field" data-boldermail-hide-if="input#single_opt_in:checked">
			<label for="confirmation_email_subject"><?php esc_html_e( 'Confirmation Subject', 'boldermail' ); ?></label>
			<input type="text" id="confirmation_email_subject" name="_confirmation_email_subject" value="<?php echo esc_attr( $list->get_confirmation_email_subject() ); ?>" class="regular-text" />
			<?php echo boldermail_help_tip( esc_html__( "Enter the subject line for the confirmation email.", 'boldermail' ) ); ?>
		</p>

		<fieldset class="form-field" data-boldermail-hide-if="input#single_opt_in:checked">
			<label for="confirmation_email_content"><?php esc_html_e( 'Confirmation Message', 'boldermail' ); ?></label>
			<?php boldermail_editor( $list->get_confirmation_email_content(), 'confirmation_email_content', array(
				'preview_meta' => array(
					'from_name' => 'from_name',
					'from_email' => 'from_email',
					'reply_to' => 'reply_to',
					'subject' => 'confirmation_email_subject',
					'content' => 'confirmation_email_content',
				),
			) ); ?>
			<span class="description"><?php boldermail_kses_post_e( "A generic email message will be used if you leave this field empty. <strong>Don't forget to include the shortcode <code>[boldermail_confirm]</code> somewhere in your message!</strong>", 'boldermail' ); ?></span>
		</fieldset>
	</div>

</div>
<?php
