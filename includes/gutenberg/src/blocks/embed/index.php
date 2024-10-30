<?php
/**
 *  Server-side rendering of the `boldermail/embed` block.
 *
 * @link       https://www.boldermail.com/about/
 * @since      2.3.0
 *
 * @package    Boldermail
 * @subpackage Boldermail/admin
 * @author     Hernan Villanueva <hernan@boldermail.com>
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'boldermail_register_block_embed' );
/**
 * Registers the `boldermail/embed` block.
 *
 * @since 2.3.0
 */
function boldermail_register_block_embed() {

	register_block_type_from_metadata(
		BOLDERMAIL_PLUGIN_DIR . 'includes/gutenberg/src/blocks/embed/block.json',
		array(
			'render_callback' => 'boldermail_render_block_embed',
		)
	);

}

/**
 * Renders the `boldermail/embed` block on server.
 *
 * @since  2.3.0
 * @param  array $attributes The block attributes.
 * @return string            The embed content.
 */
function boldermail_render_block_embed( $attributes = array() ) {

	$service  = $attributes['service'];
	$url      = $attributes['url'];
	$callback = $service ? BOLDERMAIL_EMBED_HANDLERS[ $service ]['callback'] : null;

	if ( ! is_callable( $callback ) ) {
		return '<!-- Boldermail embed error: Service not supported -->';
	}

	ob_start();

	?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bmEmbedBlock" style="min-width: 100%">
		<tbody class="bmEmbedBlockOuter">
			<tr>
				<td valign="top" class="bmEmbedBlockInner" style="padding-top: 9px">
					<!--[if mso]>
					<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%">
						<tr>
							<td valign="top" width="600" style="width:600px;">
					<![endif]-->
					<table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width: 100%; min-width: 100%" width="100%" class="bmEmbedContentContainer">
						<tbody>
							<tr>
								<td valign="top" class="bmEmbedContent" style="padding: 0 18px 9px 18px">
									<?php echo $attributes['url'] ? boldermail_kses_post( call_user_func( $callback, [ $url ] ) ) : '<!-- Invalid URL -->'; ?>
								</td>
							</tr>
						</tbody>
					</table>
					<!--[if mso]>
							</td>
						</tr>
					</table>
					<![endif]-->
				</td>
			</tr>
		</tbody>
	</table>
	<?php

	return ob_get_clean();

}
