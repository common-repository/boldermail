<?php
/**
 * Subscriber data meta box panel.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

global $pagenow;
$list_post_id = ( 'post-new.php' === $pagenow && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'bm_subscriber' && isset( $_GET['list'] ) && is_numeric( $_GET['list'] ) && ! $subscriber->get_list_post_id() ) ? absint( $_GET['list'] ) : absint( $subscriber->get_list_post_id() );

$subscriber_status = $subscriber->get_status();
$is_published = $subscriber->is_published();

?>
<div id="subscriber_data_panel" class="boldermail-options-panel">

	<div class="options_group">
		<?php if ( $is_published ) : ?>
		<fieldset class="form-field">
			<label><?php esc_html_e( 'Profile Picture', 'boldermail' ); ?></label>
			<?php echo get_avatar( $subscriber->get_email() ); ?>
			<!-- <span class="description"><?php boldermail_kses_post_e( 'Profile pictures are downloaded from <a href="https://en.gravatar.com/">Gravatar</a>.', 'boldermail' );?></span> -->
		</fieldset>
		<?php endif; ?>

		<?php if ( $is_published ) : ?>
		<fieldset class="form-field">
			<legend><?php esc_html_e( 'Status', 'boldermail' ); ?></legend>
			<?php include_once BOLDERMAIL_PLUGIN_DIR . 'includes/list-tables/class-boldermail-subscribers-list-table.php'; ?>
			<?php echo Boldermail_Subscribers_List_Table::get_status_column_html( $subscriber ); ?>
		</fieldset>
		<?php endif; ?>

		<p class="form-field">
			<label for="email"><?php esc_html_e( 'Email', 'boldermail' ); ?></label>
			<input type="email" id="email" required name="_email" <?php echo ( $is_published ) ? 'readonly' : ''; ?> value="<?php echo esc_attr( $subscriber->get_email() ); ?>" class="regular-text" />
			<?php echo boldermail_help_tip( __( "Enter your subscriber's email address. Use the <code>[boldermail_email]</code> shortcode to use it in a newsletter.", 'boldermail' ) ); ?>
		</p>

		<p class="form-field">
			<label for="list"><?php esc_html_e( 'List', 'boldermail' ); ?></label>

			<select id="list" name="_list" required>
				<option value="" <?php echo ( $is_published ) ? 'disabled' : ''; ?>><?php esc_html_e( 'Select list', 'boldermail' ); ?></option>

				<?php $lists = boldermail_get_lists(); ?>

				<?php foreach ( $lists as $list ) : ?>
					<option value="<?php echo esc_attr( $list->get_post_id() ); ?>" <?php boldermail_selected_or_disabled( ! $is_published, $list->get_post_id(), $list_post_id ); ?>><?php echo esc_html( $list->get_name() ); ?></option>
				<?php endforeach; ?>

			</select>
			<?php echo boldermail_help_tip( esc_html__( "Select a list for your subscriber.", 'boldermail' ) ); ?>
		</p>

		<p class="form-field">
			<label for="name"><?php esc_html_e( 'Name', 'boldermail' ); ?></label>
			<input type="text" id="name" name="_name" value="<?php echo esc_attr( $subscriber->get_name() ); ?>" class="regular-text" />
			<?php echo boldermail_help_tip( __( "Enter your subscriber's first name or the full name. Use the <code>[boldermail_name]</code> shortcode to use it in a newsletter.", 'boldermail' ) ); ?>
		</p>

		<?php foreach ( Boldermail_List::get_default_fields() as $default_field ) : ?>
		<p class="form-field">
			<label for="<?php echo esc_attr( boldermail_string_to_attr( $default_field['name'] ) ); ?>"><?php echo esc_html( $default_field['label'] ); ?></label>
			<input type="<?php echo esc_attr( $default_field['type'] )?>" id="<?php echo boldermail_string_to_attr( $default_field['name'] ); ?>" name="_custom_fields[<?php echo esc_textarea( $default_field['name'] ); ?>]" value="<?php echo esc_attr( $subscriber->get_custom_field( $default_field['name'] ) ); ?>" class="regular-text" />
			<input type="hidden" name="_custom_fields_type[<?php echo esc_textarea( $default_field['name'] ); ?>]" value="<?php echo esc_attr( $default_field['type'] ); ?>">
			<?php echo boldermail_help_tip( $default_field['tip'] ); ?>
		</p>
		<?php endforeach; ?>
	</div>

	<div id="custom-fields" class="options_group">
	</div>

	<?php if ( $is_published ) : ?>
	<div class="options_group">
		<fieldset class="form-field">
			<legend><?php esc_html_e( 'Sign Up IP Address', 'boldermail' ); ?></legend>
			<span><?php echo esc_html( $subscriber->get_ip_address() ); ?></span>
			<span title="<?php echo esc_attr( boldermail()->countries->to_country( $subscriber->get_country() ) ); ?>"><?php echo esc_html( boldermail()->countries->to_emoji( $subscriber->get_country() ) ); ?></span>
		</fieldset>
	</div>
	<?php endif; ?>

	<?php if ( ! $is_published || $subscriber_status === 'unsubscribed' ) : ?>
	<fieldset class="form-field">
		<label for="skip_opt_in_confirm-wrap"><?php esc_html_e( 'Skip Double Opt-in Confirm', 'boldermail' ); ?></label>
		<div id="skip_opt_in_confirm-wrap" class="boldermail-inline-checkboxes">
			<input type="checkbox" id="skip_opt_in_confirm" name="_skip_opt_in_confirm" value="1" <?php checked( '1', $subscriber->skip_opt_in_confirm() ); ?> />
			<label for="skip_opt_in_confirm"><?php esc_html_e( "Check this box if your list has double opt-in, but you wish to subscribe this user as single opt-in (without a confirmation email).", 'boldermail' ); ?></label>
		</div>
	</fieldset>
	<?php endif; ?>

	<?php if ( ! $is_published || $subscriber_status === 'unsubscribed' ) : ?>
	<fieldset class="form-field">
		<label for="gpdr-wrap"><?php esc_html_e( 'GDPR', 'boldermail' ); ?></label>
		<div id="gdpr-wrap" class="boldermail-inline-checkboxes">
			<input type="checkbox" id="gdpr" name="_gdpr" value="1" <?php checked( '1', $subscriber->is_gdpr() ); ?> />
			<label for="gdpr"><?php esc_html_e( "Check this box if you're signing up an EU user in a GDPR compliant manner", 'boldermail' ); ?></label>
		</div>
	</fieldset>
	<?php endif; ?>

</div>
<?php
