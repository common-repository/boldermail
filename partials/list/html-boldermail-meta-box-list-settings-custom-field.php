<?php
/**
 * List Custom Field meta box panel.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

$addon_title = ! empty( $custom_field['name'] ) ? $custom_field['name'] : '';
$addon_type = ! empty( $custom_field['type'] ) ? $custom_field['type'] : 'Text';
$addon_type_name = ! empty( $addon_type ) ? boldermail_get_list_custom_field_type_name( $addon_type ) : __( 'Text', 'boldermail' );

?>
<div class="boldermail-cf-addon closed">
	<div class="boldermail-cf-addon-header">
		<div class="boldermail-cf-col1">
			<h2 class="boldermail-cf-addon-name"><?php echo esc_html( $addon_title ); ?></h2>
			<span class="boldermail-cf-addon-code"><code>[boldermail_custom_field name="<span class="boldermail-cf-addon-code-name"><?php echo esc_html( boldermail_custom_field_to_tag( $addon_title ) ); ?></span>"]</code></span>
		</div>

		<div class="boldermail-cf-col2">
			<small class="boldermail-cf-addon-type"><?php echo esc_html( $addon_type_name ); ?></small>
			<button type="button" class="boldermail-cf-remove-addon button"><?php esc_html_e( 'Remove', 'boldermail' ); ?></button>
			<span class="boldermail-cf-addon-toggle" title="<?php esc_attr_e( 'Click to toggle', 'boldermail' ); ?>" aria-hidden="true"></span>
		</div>
	</div>

	<div class="boldermail-cf-addon-content">
		<p class="form-field">
			<label for="boldermail-cf-addon-content-type-<?php echo esc_attr( $loop ); ?>"><?php esc_html_e( 'Type', 'boldermail' ); ?></label>
			<select id="boldermail-cf-addon-content-type-<?php echo esc_attr( $loop ); ?>" required name="product_addon_type[<?php echo esc_attr( $loop ); ?>]" class="boldermail-cf-addon-type-select">
				<option <?php selected( 'Text', $addon_type ); ?> value="Text"><?php esc_html_e( 'Text', 'boldermail' ); ?></option>
				<option <?php selected( 'Number', $addon_type ); ?> value="Number"><?php esc_html_e( 'Number', 'boldermail' ); ?></option>
				<option <?php selected( 'Date', $addon_type ); ?> value="Date"><?php esc_html_e( 'Date', 'boldermail' ); ?></option>
			</select>
		</p>

		<p class="form-field">
			<label for="boldermail-cf-addon-content-name-<?php echo esc_attr( $loop ); ?>"><?php esc_html_e( 'Title', 'boldermail' ); ?></label>
			<input type="text" class="boldermail-cf-addon-content-name" id="boldermail-cf-addon-content-name-<?php echo esc_attr( $loop ); ?>" required name="product_addon_name[<?php echo esc_attr( $loop ); ?>]" value="<?php echo esc_attr( $addon_title ); ?>" />
		</p>

		<p class="form-field">
			<label><?php esc_html_e( 'Shortcode', 'boldermail' ); ?></label>
			<span class="boldermail-cf-addon-code"><code>[boldermail_custom_field name="<span class="boldermail-cf-addon-code-name"><?php echo esc_html( boldermail_custom_field_to_tag( $addon_title ) ); ?></span>"]</code></span>
		</p>
	</div>
</div>
