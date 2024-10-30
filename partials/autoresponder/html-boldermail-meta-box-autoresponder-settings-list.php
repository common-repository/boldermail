<?php
/**
 * "List:" meta box panel.
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
$list_post_id = ( 'post-new.php' === $pagenow && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'bm_autoresponder' && isset( $_GET['list'] ) && is_numeric( $_GET['list'] ) && empty( $autoresponder->get_list_post_id() ) ) ? absint( $_GET['list'] ) : $autoresponder->get_list_post_id();

?>
<div id="autoresponder_list_panel" class="panel boldermail-options-panel">

	<p><?php esc_html_e( 'Who are you sending these automated emails to?', 'boldermail' ); ?></p>

	<p class="form-field">
		<label for="list"><?php esc_html_e( 'List', 'boldermail' ); ?></label>

		<select id="list" name="_list" required>
			<option value="" <?php echo ( $is_editable ) ? '' : 'disabled'; ?>><?php esc_html_e( 'Select list', 'boldermail' ); ?></option>

			<?php $lists = boldermail_get_lists(); ?>

			<?php foreach ( $lists as $list ) : ?>
				<option value="<?php echo esc_attr( $list->get_post_id() ); ?>" <?php boldermail_selected_or_disabled( $is_editable, $list->get_post_id(), $list_post_id ); ?>><?php echo esc_html( $list->get_name() ); ?></option>
			<?php endforeach; ?>

		</select>
	</p>

</div>
<?php
