<?php
/**
 * Block Template class.
 *
 * The Boldermail block template class.
 *
 * @link       https://www.boldermail.com/about/
 * @since      1.0.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<!--[if gte mso 15]>
		<xml>
			<o:OfficeDocumentSettings>
			<o:AllowPNG/>
			<o:PixelsPerInch>96</o:PixelsPerInch>
			</o:OfficeDocumentSettings>
		</xml>
		<![endif]-->
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>[boldermail_title]</title>
		<?php wp_site_icon(); ?>
		<style type="text/css">
		<?php $bm_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min'; ?>
		[boldermail_block_template_style file="<?php echo esc_url_raw( BOLDERMAIL_PLUGIN_DIR . "assets/css/boldermail-editor-styles-gutenberg$bm_suffix.css" ); ?>"]
		[boldermail_block_template_style meta="template_style"]
		[boldermail_block_template_style meta="preheader_style"]
		[boldermail_block_template_style meta="header_style"]
		[boldermail_block_template_style meta="body_style"]
		[boldermail_block_template_style meta="footer_style"]
		</style>
	</head>
	<body id="body">
		[boldermail_block_template_body]
	</body>
</html>
