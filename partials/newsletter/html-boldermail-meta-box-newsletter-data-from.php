<?php
/**
 * "From" meta box panel.
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
<div id="from_panel" class="panel boldermail-options-panel">
	<h3><?php esc_html_e( 'Who is sending this campaign?', 'boldermail' ); ?></h3>

	<?php
	boldermail_wp_text_input(
		array(
			'id'          => 'from_name',
			'label'       => __( 'From name', 'boldermail' ),
			'name'        => '_from_name',
			'value'       => $newsletter->get_from_name(),
			'editable'    => $newsletter->is_editable(),
			'description' => __( 'Use a name your subscribers will instantly recognize, like your company name.', 'boldermail' ),
		)
	);

	boldermail_wp_text_input(
		array(
			'id'          => 'from_email',
			'label'       => __( 'From email', 'boldermail' ),
			'name'        => '_from_email',
			'value'       => $newsletter->get_from_email(),
			'editable'    => $newsletter->is_editable(),
			'description' => __( 'Your email address must match the email address associated with your Boldermail account.', 'boldermail' ),
		)
	);

	boldermail_wp_text_input(
		array(
			'id'          => 'reply_to',
			'label'       => __( 'Reply to', 'boldermail' ),
			'name'        => '_reply_to',
			'value'       => $newsletter->get_reply_to(),
			'editable'    => $newsletter->is_editable(),
			'description' => __( 'Where should the replies to your campaign email be sent? You can use the same email as in the <code>From email</code> field.', 'boldermail' ),
		)
	);

	boldermail_wp_text_input(
		array(
			'id'          => 'company_name',
			'label'       => __( 'Company Name', 'boldermail' ),
			'name'        => '_company_name',
			'value'       => $newsletter->get_company_name(),
			'editable'    => $newsletter->is_editable(),
			'description' => __( 'Your company/organization name.', 'boldermail' ),
		)
	);

	boldermail_wp_text_input(
		array(
			'id'          => 'company_address',
			'label'       => __( 'Company Address', 'boldermail' ),
			'name'        => '_company_address',
			'value'       => $newsletter->get_company_address(),
			'editable'    => $newsletter->is_editable(),
			'description' => __( 'Your company/organization address.', 'boldermail' ),
		)
	);

	boldermail_wp_textarea_input(
		array(
			'id'          => 'permission',
			'label'       => __( 'Permission Reminder', 'boldermail' ),
			'name'        => '_permission',
			'value'       => $newsletter->get_permission(),
			'editable'    => $newsletter->is_editable(),
			'description' => __( 'Explain to your users why they are receiving this email (e.g. <i>&quot;You are receiving this email because you opted in on our signup form in The Postman&#39;s Knock blog (www.thepostmansknock.com)&quot;</i>).', 'boldermail' ),
			'rows'        => 3,
		)
	);
	?>

</div>
<?php
