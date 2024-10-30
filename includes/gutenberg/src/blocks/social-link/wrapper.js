// Internal dependencies.
import INITIAL_ATTRIBUTES from '../social-shares/icon-attributes';

// WordPress dependencies.
const { Fragment } = wp.element;

// Wrapper function.
export default function SocialLinkWrapper( props ) {
	const { attributes: {
		url,
		service,
		label,
		display,
		iconStyle,
		iconSize,
		iconColor,
		fontFamily,
		fontSize,
		textColor,
	}, save } = props;

	// Get CDN and icon size width and height
	const CDN_URL = 'https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/';
	const retinaSize = iconSize / 2;
	const href = ( service === 'forwardtofriend' && url ) ? `mailto:${ url }` : url;

	return (
		<Fragment>
			{ save ? (
				<Fragment>
					{ '[boldermail_html_comment]' }
					{ '[if mso]>' }
					{ '<td align="center" valign="top">' }
					{ '<![endif]' }
					{ '[/boldermail_html_comment]' }
				</Fragment>
			) : null }
			<table align="left" border="0" cellPadding="0" cellSpacing="0" style={ { display: 'inline' } }>
				<tbody>
					<tr>
						<td valign="top" style={ { paddingRight: '10px', paddingBottom: '9px' } } className="bmFollowContentItemContainer">
							<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmFollowContentItem">
								<tbody>
									<tr>
										<td align="left" valign="middle" style={ { paddingTop: '5px', paddingRight: '10px', paddingBottom: '5px', paddingLeft: '9px' } }>
											<table align="left" border="0" cellPadding="0" cellSpacing="0" width="">
												<tbody>
													<tr>
														{ display === 'icon' || display === 'both' ? (
															<td align="center" valign="middle" width={ `${ retinaSize }` } className="bmFollowIconContent">
																<a href={ href ? `${ href }` : '#' } target="_blank" rel="noopener noreferrer"> { /* target="_blank" must include rel="noopener noreferrer" or it breaks validation -- @see https://github.com/WordPress/gutenberg/issues/14934 */ }
																	<img src={ iconStyle ? `${ CDN_URL }${ iconStyle }-${ iconColor }-${ service }-${ iconSize }.png` : `${ CDN_URL }${ iconColor }-${ service }-${ iconSize }.png` } alt={ label && `${ label }` } style={ { display: 'block' } } height={ `${ retinaSize }` } width={ `${ retinaSize }` } className="" />
																</a>
															</td>
														) : null }
														{ display === 'text' || display === 'both' ? (
															<td align="left" valign="middle" className="bmFollowTextContent" style={ { paddingLeft: '5px' } }>
																<a
																	href={ href && `${ href }` }
																	target="_blank"
																	rel="noopener noreferrer"
																	style={ {
																		fontFamily,
																		fontSize: fontSize ? fontSize + 'px' : INITIAL_ATTRIBUTES.fontSize.default + 'px',
																		color: textColor,
																	} }
																>
																	{ label }
																</a>
															</td>
														) : null }
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			{ save ? (
				<Fragment>
					{ '[boldermail_html_comment]' }
					{ '[if mso]>' }
					{ '</td>' }
					{ '<![endif]' }
					{ '[/boldermail_html_comment]' }
				</Fragment>
			) : null }
		</Fragment>
	);
}
