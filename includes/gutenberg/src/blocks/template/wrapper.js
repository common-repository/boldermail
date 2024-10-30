// Internal dependencies.
import INITIAL_ATTRIBUTES from './attributes';

// WordPress dependencies.
const { Fragment } = wp.element;

// Wrapper function.
export default function TemplateWrapper( { children, ...props } ) {
	const { attributes: {
		blockAlignment,
		backgroundColor,
		borderTopStyle,
		borderTopWidth,
		borderTopColor,
	}, save } = props;

	const align = ( blockAlignment && blockAlignment !== 'wide' ) ? blockAlignment : INITIAL_ATTRIBUTES.blockAlignment.default;

	return (
		<Fragment>
			{ save ? (
				<Fragment>
					{ '[boldermail_html_comment]' }
					{ '[if !gte mso 9]><!--' }
					{ '[/boldermail_html_comment]' }
					<p
						className="bmPreviewText"
						style={ {
							display: 'none',
							fontSize: '0px',
							lineHeight: '0px',
							maxHeight: '0px',
							maxWidth: '0px',
							opacity: '0',
							overflow: 'hidden',
							visibility: 'hidden',
							msoHide: 'all',
						} }
					>
						{ '[boldermail_preview_text]' }
					</p>
					{ '[boldermail_html_comment]' }
					{ '<![endif]' }
					{ '[/boldermail_html_comment]' }
				</Fragment>
			) : null }
			<center>
				<table align="center" border="0" cellPadding="0" cellSpacing="0" height="100%" width="100%" id="bodyTable" style={ {
					backgroundColor,
				} }>
					<tbody>
						<tr>
							<td align={ `${ align }` } valign="top" id="bodyCell" style={ {
								...( blockAlignment !== 'wide' ? { padding: '10px' } : {} ),
								...( borderTopWidth && borderTopStyle && borderTopColor ) ? { borderTopWidth, borderTopStyle, borderTopColor } : null,
							} }>
								{ children }
							</td>
						</tr>
					</tbody>
				</table>
			</center>
		</Fragment>
	);
}
