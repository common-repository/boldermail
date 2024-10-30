<?php
/**
 * List Details meta box panel.
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
<div id="details_panel" class="boldermail-options-panel">

	<p><strong><?php esc_html_e( "Who is sending the newsletters?", 'boldermail' ); ?></strong></p>

	<p><?php esc_html_e( "Enter the default values for your list. These values can be changed in each campaign.", 'boldermail' ); ?></p>

	<p class="form-field">
		<label for="from_name"><?php esc_html_e( 'Default "From name"', 'boldermail' ); ?></label>
		<input type="text" id="from_name" name="_from_name" value="<?php echo esc_attr( $list->get_from_name() ); ?>" class="regular-text" />
		<?php echo boldermail_help_tip( esc_html__( "Use a name your subscribers will instantly recognize, like your company name.", 'boldermail' ) ); ?>
	</p>

	<p class="form-field">
		<label for="from_email"><?php esc_html_e( 'Default "From email"', 'boldermail' ); ?></label>
		<input type="email" id="from_email" name="_from_email" value="<?php echo esc_attr( $list->get_from_email() ); ?>" class="regular-text" />
		<?php echo boldermail_help_tip( esc_html__( "Your email address must match the email address associated with your Boldermail account.", 'boldermail' ) ); ?>
	</p>

	<p class="form-field">
		<label for="reply_to"><?php esc_html_e( 'Default "Reply to"', 'boldermail' ); ?></label>
		<input type="email" id="reply_to" name="_reply_to" value="<?php echo esc_attr( $list->get_reply_to() ); ?>" class="regular-text" />
		<?php echo boldermail_help_tip( __( "Where should the replies to your campaign email be sent? You can use the same email as in the <code>From email</code> field.", 'boldermail' ) ); ?>
	</p>

	<!--<fieldset class="form-field">
		<label for="gdpr_enabled-wrap"><?php esc_html_e( 'Enable GDPR?', 'boldermail' ); ?></label>
		<div id="gdpr_enabled-wrap" class="boldermail-inline-checkboxes">
			<input type="checkbox" id="gdpr_enabled" name="_gdpr_enabled" value="1" <?php checked( '1', $list->is_gdpr_enabled() ); ?> />
			<label for="gdpr_enabled"><?php esc_html_e( 'The General Data Protection Regulation (GDPR) is a regulation in EU law on data protection and privacy for all individuals within the European Union. The GDPR regulation affects anyone in the world who collect and process the personal data of EU users. If you collect and process data of EU users, consider enabling GDPR fields.', 'boldermail' ); ?></label>
		</div>
	</fieldset>-->

</div>
<?php
