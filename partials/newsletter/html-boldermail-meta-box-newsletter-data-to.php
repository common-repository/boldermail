<?php
/**
 * "To" meta box panel.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 *
 * @var        Boldermail_Newsletter_RSS_Feed $newsletter Newsletter object.
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="to_panel" class="panel boldermail-options-panel">
	<h3><?php esc_html_e( 'Who are you sending this campaign to?', 'boldermail' ); ?></h3>

	<?php
	boldermail_wp_select(
		[
			'id'                => 'list_id',
			'label'             => __( 'List', 'boldermail' ),
			'name'              => '_list_id[]',
			'class'             => 'boldermail-select2 short',
			'placeholder'       => __( 'Click here to select one or more lists', 'boldermail' ),
			'value'             => $newsletter->get_list_id(),
			'options'           => boldermail_get_lists_names(),
			'editable'          => $newsletter->is_editable(),
			'description'       => __( 'Calculating recipients...', 'boldermail' ),
			'desc_tip'          => false,
			'custom_attributes' => array( 'multiple' => '' ),
		]
	);
	?>
</div>
<?php
