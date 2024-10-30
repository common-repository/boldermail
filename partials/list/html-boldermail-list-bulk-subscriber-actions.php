<?php
/**
 * Bulk subscriber actions page.
 *
 * Forms modeled after `wp-admin/plugin-install.php`.
 *
 * @see        http://www.php-guru.in/2013/upload-files-using-php-curl/
 * @see        https://gerhardpotgieter.com/2014/07/30/uploading-files-with-wp_remote_post-or-wp_remote_request/
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

$response = null;
$doing_import = isset( $_REQUEST['doing_import'] ) ? true : false;

if ( $doing_import ) {
	$response = boldermail()->api->import_subscribers( array(
		'list_id' => boldermail_sanitize_text( $list->get_list_id() ),
		'csv_file' => $_FILES['_import_data'],
	) );
}

// if doing import and no errors, consider success and redirect to List post page
if ( $doing_import && ! is_wp_error( $response ) ) {
	wp_redirect( add_query_arg( array( 'message' => 11 ), get_edit_post_link( $list->get_post_id(), 'url' ) ) );
	exit;
}

include( ABSPATH . 'wp-admin/admin-header.php' );
?>

<div class="wrap bulk-subscriber-actions">
	<h2><?php echo esc_html( sprintf( __( 'Import Subscribers to %s', 'boldermail' ), $list->get_name() ) ); ?></h2>

	<?php if ( ! $doing_import || is_wp_error( $response ) ) : ?>

		<?php if ( is_wp_error( $response ) ) : ?>
			<div class="notice notice-error is-dismissible"><?php echo wp_kses_post( $response->get_error_message() ); ?></div>
		<?php endif; ?>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<strong><?php esc_html_e( 'Import via CSV File', 'boldermail' ); ?></strong>
					</th>
					<td>
						<div class="notice notice-info notice-alt inline">
							<ul class="ul-disc">
								<li><?php esc_html_e( 'Format your CSV the same way as the example below.', 'boldermail' ); ?></li>
								<li><?php esc_html_e( 'Your CSV columns should be separated by commas, not semi-colons or any other characters.', 'boldermail' ); ?></li>
								<li><?php esc_html_e( 'The number of columns in your CSV should be the same as the example below.', 'boldermail' ); ?></li>
								<li><?php esc_html_e( 'Note that you can either use the Name and Last Name fields, or just the Name field. If you use only the Name field, leave the Last Name field blank.', 'boldermail' ); ?></li>
								<li><?php echo wp_kses_post( __( 'If your data is in an Excel spreadsheet (<code>.xlsx</code> file), you can use Excel to export the data to a CSV file.', 'boldermail' ) ); ?></li>
							</ul>
						</div>

						<table class="boldermail-list-table widefat fixed striped">
							<tbody>
								<?php $fields = array_merge( Boldermail_List::get_core_fields(), Boldermail_List::get_default_fields(), $list->get_custom_fields() ); ?>
								<tr>
									<?php foreach ( $fields as $field ) : ?>
									<th><?php echo esc_html( $field['name'] ); ?></th>
									<?php endforeach; ?>
								</tr>
								<tr>
									<?php foreach ( $fields as $field ) : ?>
									<td>
										<?php if ( $field['name'] == 'Name' ) {
											esc_html_e( 'Hernan', 'boldermail' );
										} else if ( $field['name'] == 'Email' ) {
											esc_html_e( 'hernan@boldermail.com', 'boldermail' );
										} else {
											echo '';
										} ?>
									</td>
									<?php endforeach; ?>
								</tr>
								<tr>
									<?php foreach ( $fields as $field ) : ?>
									<td>
										<?php if ( $field['name'] == 'Name' ) {
											esc_html_e( 'Lindsey Bugbee', 'boldermail' );
										} elseif ( $field['name'] == 'Email' ) {
											esc_html_e( 'lindsey@thepostmansknock.com', 'boldermail' );
										} else {
											echo '';
										} ?>
									</td>
									<?php endforeach; ?>
								</tr>
							</tbody>
						</table>

						<div class="notice notice-warning notice-alt inline">
							<p><?php esc_html_e( 'Duplicate addresses will not be imported. We do not send confirmation emails to imported addresses and trust that you’ve gathered proper permission to send to every address on your list.', 'boldermail' ); ?></p>
						</div>

						<form method="post" action="" enctype="multipart/form-data" class="boldermail-upload-form">
							<?php wp_nonce_field( "import_subscribers-list_{$list->get_post_id()}" ) ?>
							<label class="screen-reader-text" for="import_data"><?php _e( 'Subscribers CSV file' ); ?></label>
							<input name="_import_data" id="import_data" type="file" accept=".csv" />
							<?php submit_button( esc_attr__( 'Import Subscribers', 'boldermail' ), '', 'doing_import', false ); ?>
						</form>
					</td>

				</tr>
			</tbody>
		</table>

	<?php endif; ?>

</div>

<?php
include( ABSPATH . 'wp-admin/admin-footer.php' );
exit;
