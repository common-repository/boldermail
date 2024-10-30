<?php
/**
 * "Design" meta box panel.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 *
 * @var        Boldermail_Newsletter $newsletter Newsletter object.
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="design_panel" class="panel boldermail-options-panel">
	<?php
	if ( $newsletter->use_block_editor() ) {
		echo '<h3>' . esc_html__( 'Design your Email', 'boldermail' ) . '</h3>';
		echo '<p>' . esc_html__( 'Design the content of your email using our custom Block Editor. Click on &quot;Design Email&quot; to get started!', 'boldermail' ) . '</p>';
	}

	boldermail_editor(
		$newsletter->use_block_editor() ? '' : $newsletter->get_html(),
		'html',
		array(
			'block_template' => $newsletter->use_block_editor() ? $newsletter->get_block_template_post_id() : false,
			'readonly'       => $newsletter->is_editable() ? false : true,
			'preview_meta'   => array(
				'from_name'  => 'from_name',
				'from_email' => 'from_email',
				'reply_to'   => 'reply_to',
				'subject'    => 'subject',
				'content'    => 'html',
				'filter'     => ( $newsletter->get_type() === 'rss-feed' ) ? 'preview' : 'display',
			),
		)
	);
	?>
</div>
<?php
