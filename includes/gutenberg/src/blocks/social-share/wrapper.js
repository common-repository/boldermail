// Internal dependencies.
import INITIAL_ATTRIBUTES from '../social-shares/icon-attributes';

// WordPress dependencies.
const { Fragment } = wp.element;

// Wrapper function.
export default function SocialShareWrapper( props ) {
	const { attributes: {
		service,
		label,
		url,
		shareContent,
		customURL,
		urlDesc,
		iconStyle,
		iconColor,
		fontFamily,
		fontSize,
		textColor,
		buttonBorderWidth,
		buttonBorderStyle,
		buttonBorderColor,
		buttonBorderRadius,
		buttonBackgroundColor,
	}, save } = props;

	// Get CDN and icon size width and height
	const CDN_URL = 'https://i0.wp.com/boldermail.com/wp-content/plugins/boldermail/assets/images/social/';
	const parsedUrl = url.replace( /u0026/g, '&amp;' ); // Fix issue with encoding that causes block invalidation.
	const shareLink = ( shareContent === 'campaign' ) ? parsedUrl : parsedUrl.replace( '[boldermail_permalink]', customURL ).replace( '[boldermail_title]', urlDesc );

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
						<td valign="top" style={ { paddingRight: '9px', paddingBottom: '9px' } } className="bmShareContentItemContainer">
							<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmShareContentItem" style={ {
								borderCollapse: 'separate',
								borderWidth: buttonBorderWidth,
								borderStyle: buttonBorderStyle,
								borderColor: buttonBorderColor,
								borderRadius: buttonBorderRadius + INITIAL_ATTRIBUTES.buttonBorderRadius.unit,
								backgroundColor: buttonBackgroundColor,
							} }>
								<tbody>
									<tr>
										<td align="left" valign="middle" style={ { paddingTop: '5px', paddingRight: '9px', paddingBottom: '5px', paddingLeft: '9px' } }>
											<table align="left" border="0" cellPadding="0" cellSpacing="0" width="">
												<tbody>
													<tr>
														<td align="center" valign="middle" width="24" className="bmShareIconContent">
															<a href={ shareLink && `${ shareLink }` } target="_blank" rel="noopener noreferrer"> { /* target="_blank" must include rel="noopener noreferrer" or it breaks validation -- @see https://github.com/WordPress/gutenberg/issues/14934 */ }
																<img src={ iconStyle ? `${ CDN_URL }${ iconStyle }-${ iconColor }-${ service }-48.png` : `${ CDN_URL }${ iconColor }-${ service }-48.png` } alt={ label } style={ { display: 'block' } } height="24" width="24" className="" />
															</a>
														</td>
														<td align="left" valign="middle" className="bmShareTextContent" style={ { paddingLeft: '5px' } }>
															<a
																href={ shareLink && `${ shareLink }` }
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
