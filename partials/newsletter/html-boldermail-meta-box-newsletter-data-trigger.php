<?php
/**
 * "Trigger" meta box panel.
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
<div id="trigger_panel" class="panel boldermail-options-panel">

	<?php
	/* phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound */
	$autoresponder_post_id = isset( $_REQUEST['autoresponder'] ) ? boldermail_sanitize_int( $_REQUEST['autoresponder'] ) : 0; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */
	$autoresponder         = boldermail_get_autoresponder( $autoresponder_post_id );
	$autoresponder_type    = ( $autoresponder ) ? $autoresponder->get_type() : '';
	?>

	<?php if ( $autoresponder_type ) : ?>
	<p><?php esc_html_e( 'When should this automated email be sent to the subscriber?', 'boldermail' ); ?></p>

	<p class="form-field">
		<label><?php esc_html_e( 'Send email', 'boldermail' ); ?></label>

		<!--suppress HtmlFormInputWithoutLabel -->
		<input type="number" data-boldermail-hide-if="#trigger_interval option[value='immediately']:checked" class="sized" id="trigger_number" name="_trigger_number" value="<?php echo esc_attr( $newsletter->get_trigger_number() ); ?>" placeholder="10" min="0" max="1000">

		<?php $trigger_interval = $newsletter->get_trigger_interval(); ?>

		<?php if ( 1 === $autoresponder_type ) : ?>
		<!--suppress HtmlFormInputWithoutLabel -->
		<select id="trigger_interval" name="_trigger_interval" class="sized">
			<option value="immediately" <?php selected( $trigger_interval, 'immediately' ); ?>><?php esc_html_e( 'immediately', 'boldermail' ); ?></option>
			<option value="minutes" <?php selected( $trigger_interval, 'minutes' ); ?>><?php esc_html_e( 'minutes', 'boldermail' ); ?></option>
			<option value="hours" <?php selected( $trigger_interval, 'hours' ); ?>><?php esc_html_e( 'hours', 'boldermail' ); ?></option>
			<option value="days" <?php selected( $trigger_interval, 'days' ); ?>><?php esc_html_e( 'days', 'boldermail' ); ?></option>
			<option value="weeks" <?php selected( $trigger_interval, 'weeks' ); ?>><?php esc_html_e( 'weeks', 'boldermail' ); ?></option>
			<option value="months" <?php selected( $trigger_interval, 'months' ); ?>><?php esc_html_e( 'months', 'boldermail' ); ?></option>
		</select>
		<?php endif; ?>

		<?php $trigger_beforeafter = $newsletter->get_trigger_beforeafter(); ?>

		<?php if ( 1 !== $autoresponder_type ) : ?>
		<!--suppress HtmlFormInputWithoutLabel -->
		<select id="trigger_beforeafter" name="_trigger_beforeafter" class="sized">
			<option value="on" <?php selected( $trigger_beforeafter, 'on' ); ?>><?php esc_html_e( 'on', 'boldermail' ); ?></option>
			<option value="before" <?php selected( $trigger_beforeafter, 'before' ); ?>><?php esc_html_e( 'before', 'boldermail' ); ?></option>
			<option value="after" <?php selected( $trigger_beforeafter, 'after' ); ?>><?php esc_html_e( 'after', 'boldermail' ); ?></option>
		</select>
		<?php endif; ?>

		<?php if ( 1 === $autoresponder_type ) : ?>
		<input type="hidden" name="_trigger_beforeafter" id="trigger_beforeafter" value="after">
		<?php endif; ?>

		<?php if ( 1 === $autoresponder_type ) : ?>
		<span class="show-if-type-1"><?php esc_html_e( 'after they subscribe', 'boldermail' ); ?></span>
		<?php endif; ?>
	</p>

	<?php else : ?>
	<div class="notice notice-warning inline"><p><?php boldermail_kses_post_e( 'Select an <a href="#autoresponder_panel" class="boldermail-error-link">autoresponder</a> first to display these options.', 'boldermail' ); ?></p></div>
	<?php endif; ?>
</div>
<?php
