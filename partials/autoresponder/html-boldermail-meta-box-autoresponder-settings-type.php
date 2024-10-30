<?php
/**
 * "Type:" meta box panel.
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
<div id="autoresponder_type_panel" class="panel boldermail-options-panel">

	<p><?php esc_html_e( 'What type of automated email is this autoresponder?', 'boldermail' ); ?></p>

	<?php $type = $autoresponder->get_type(); ?>

	<fieldset class="form-field">
		<label for="autoresponder_type"><?php esc_html_e( 'Type', 'boldermail' ); ?></label>

		<div id="autoresponder_type">
			<input type="radio" <?php echo ( $is_editable ) ? '' : 'readonly'; ?> id="type" name="_type" value="1" required <?php if ( $type == 1 ) echo 'checked="checked"'; ?> />
			<label for="type" class="inline"><?php esc_html_e( 'Drip Campaign', 'boldermail' ); ?></label>
			<span class="description"><?php esc_html_e( "Create an email or a sequence of emails that automatically sends to subscribers after they sign up. For example, an onboarding series to share tips and resources, or an educational series to manage an online course.", 'boldermail' ); ?></span>
		</div>

	</fieldset>

</div>
<?php
