<?php
/**
 * Contact Information meta box panel.
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
<div id="footer_panel" class="boldermail-options-panel">

	<div class="options_group">
		<p><strong><?php esc_html_e( "How can recipients contact you?", 'boldermail' ); ?></strong></p>

		<div class="notice notice-info notice-alt inline"><p><?php boldermail_kses_post_e( "International spam law requires that all marketing emails include your physical mailing address and contact information. We'll automatically place this in your templates wherever you see the <code>[boldermail_company_name]</code> and <code>[boldermail_company_address]</code> shortcodes.", 'boldermail' ); ?></p></div>

		<p><?php esc_html_e( "Enter the contact information and physical mailing address for the owner of this list. This is required by law. If you are an agency sending on behalf of a client, enter your client's information.", 'boldermail' ); ?></p>

		<p class="form-field">
			<label for="company_name"><?php esc_html_e( 'Company Name', 'boldermail' ); ?></label>
			<input type="text" id="company_name" name="_company_name" value="<?php echo esc_attr( $list->get_company_name() ); ?>" class="regular-text" />
			<?php echo boldermail_help_tip( esc_html__( "Your company/organization name.", 'boldermail' ) ); ?>
		</p>

		<p class="form-field">
			<label for="company_address"><?php esc_html_e( 'Company Address', 'boldermail' ); ?></label>
			<input type="text" id="company_address" name="_company_address" value="<?php echo esc_attr( $list->get_company_address() ); ?>" class="regular-text" />
			<?php echo boldermail_help_tip( esc_html__( "Your company/organization address.", 'boldermail' ) ); ?>
		</p>
	</div>

	<div class="options_group">
		<p><strong><?php esc_html_e( "Permission Reminder", 'boldermail' ); ?></strong></p>

		<p><?php boldermail_kses_post_e( "Sometimes people forget they signed up for an email newsletter. To prevent false spam reports, it's best to briefly remind your recipients how they got on your list (e.g. <i>&quot;You are receiving this email because you opted in at our website...&quot; or &quot;We send special offers to customers who opted in at...&quot;</i>). We'll automatically place this in your templates wherever you see the <code>[boldermail_permission]</code> shortcode.", 'boldermail' ); ?></p>

		<p class="form-field">
			<label for="permission"><?php esc_html_e( 'Reminder', 'boldermail' ); ?></label>
			<textarea id="permission" name="_permission" class="short" rows="3"><?php echo esc_textarea( $list->get_permission() ); ?></textarea>
			<?php echo boldermail_help_tip( esc_html__( "Remind people how they signed up to your list. Do not include unsubscribe instructions from any other service. Recipients can only unsubscribe by clicking the link provided in your email.", 'boldermail' ) ); ?>
		</p>
	</div>

</div>
<?php
