<?php
/**
 * List Custom Fields meta box panel.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

$custom_fields = $list->get_custom_fields();
$has_addons = ( ! empty( $custom_fields ) && count( $custom_fields ) > 0 ) ? 'boldermail-cf-has-addons' : '';

?>
<div id="custom_fields_panel" class="boldermail-options-panel">

	<?php do_action( 'boldermail_custom_fields_panel_start' ); ?>

	<div class="boldermail-cf-field-header">
		<p><strong><?php esc_html_e( 'Custom Fields', 'boldermail' ); ?><?php echo boldermail_help_tip( __( 'Add fields to get additional information from your subscribers', 'boldermail' ) ); ?></strong></p>

		<p class="boldermail-cf-toolbar <?php echo esc_attr( $has_addons ); ?>">
			<a href="javascript:;" class="boldermail-cf-expand-all"><?php esc_html_e( 'Expand all', 'boldermail' ); ?></a>&nbsp;/&nbsp;<a href="javascript:;" class="boldermail-cf-close-all"><?php esc_html_e( 'Close all', 'boldermail' ); ?></a>
		</p>
	</div>

	<div class="boldermail-cf-addons <?php echo esc_attr( $has_addons ); ?>">

		<?php $loop = 0; ?>

		<?php foreach ( $custom_fields as $custom_field ) : ?>

			<?php include( BOLDERMAIL_PLUGIN_DIR . 'partials/list/html-boldermail-meta-box-list-settings-custom-field.php' ); ?>

			<?php $loop++; ?>

		<?php endforeach; ?>

	</div>

	<div class="boldermail-cf-actions">
		<button type="button" class="button boldermail-cf-add-field"><?php esc_html_e( 'Add Field', 'boldermail' ); ?></button>

		<div class="boldermail-cf-toolbar__import-export">
			<button type="button" class="button boldermail-cf-import-addons"><?php esc_html_e( 'Import', 'boldermail' ); ?></button>
			<button type="button" class="button boldermail-cf-export-addons"><?php esc_html_e( 'Export', 'boldermail' ); ?></button>
		</div>
	</div>

	<div class="boldermail-cf-import-export-container">
		<textarea name="export_product_addon" class="boldermail-cf-export-field" cols="20" rows="5" readonly="readonly"><?php echo esc_textarea( serialize( $custom_fields ) ); ?></textarea>
		<textarea name="import_product_addon" class="boldermail-cf-import-field" cols="20" rows="5" placeholder="<?php esc_attr_e( 'Paste exported form data here and then save to import fields. The imported fields will be appended.', 'boldermail' ); ?>"></textarea>
	</div>

	<div class="boldermail-cf-global-field">
		<strong><?php esc_html_e( 'Default fields', 'boldermail' ); ?></strong>
		<p><?php esc_html_e( 'These are defaults fields provided by Boldermail for all subscribers. Use the shortcodes to include them in your newsletters.', 'boldermail' ); ?></p>

		<?php $default_fields = array_merge( Boldermail_List::get_core_fields(), Boldermail_List::get_default_fields() ); ?>

		<?php foreach ( $default_fields as $default_field ) : ?>
			<div class="boldermail-cf-addon closed core-field">
				<div class="boldermail-cf-addon-header">
					<div class="boldermail-cf-col1">
						<h2 class="boldermail-cf-addon-name"><?php echo esc_html( $default_field['name'] ); ?></h2>
						<span class="boldermail-cf-addon-code"><code><?php echo esc_html( $default_field['shortcode'] ); ?></code></span>
					</div>

					<div class="boldermail-cf-col2">
						<small class="boldermail-cf-addon-type"><?php echo esc_html( boldermail_get_list_custom_field_type_name( $default_field['type'] ) ); ?></small>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

</div>
<?php
