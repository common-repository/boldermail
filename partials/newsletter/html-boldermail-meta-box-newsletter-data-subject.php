<?php
/**
 * "Subject Line" meta box panel.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 *
 * @var        Boldermail_Newsletter $newsletter Newsletter object.
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="subject_panel" class="panel boldermail-options-panel">
	<h3><?php esc_html_e( 'What is the subject line for this campaign?', 'boldermail' ); ?></h3>

	<?php
	boldermail_wp_emoji_text_input(
		array(
			'id'          => 'subject',
			'label'       => __( 'Email subject', 'boldermail' ),
			'name'        => '_subject',
			'value'       => $newsletter->get_subject(),
			'editable'    => $newsletter->is_editable(),
			'description' => __( 'A brief summary of the topic of the newsletter. You can use our shortcodes here to pull information from your blog.', 'boldermail' ),
		)
	);

	boldermail_wp_text_input(
		array(
			'id'          => 'preview_text',
			'label'       => __( 'Preview text', 'boldermail' ),
			'name'        => '_preview_text',
			'value'       => $newsletter->get_preview_text(),
			'editable'    => $newsletter->is_editable(),
			'description' => __( 'This snippet will appear in the inbox after the subject line.', 'boldermail' ),
		)
	);
	?>
</div>
<?php
