// Wrapper function.
export default function SocialLinksWrapper( { children, ...props } ) {
	const { attributes: {
		isFullWidth,
		blockAlignment,
		borderWidth,
		borderStyle,
		borderColor,
		backgroundColor,
	}, save } = props;

	return (
		<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmFollowBlock" style={ { minWidth: '100%' } }>
			<tbody className="bmFollowBlockOuter">
				<tr>
					<td align="center" valign="top" style={ { padding: '9px' } } className="bmFollowBlockInner">
						<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmFollowContentContainer" style={ { minWidth: '100%' } }>
							<tbody>
								<tr>
									<td align={ `${ blockAlignment }` } style={ { paddingLeft: '9px', paddingRight: '9px' } }>
										<table border="0" cellPadding="0" cellSpacing="0" { ...( isFullWidth ? { width: '100%' } : {} ) } style={ {
											minWidth: '100%',
											backgroundColor,
											borderWidth,
											borderStyle,
											borderColor,
										} } className="bmFollowContent">
											<tbody>
												<tr>
													<td align={ `${ blockAlignment }` } valign="top" style={ { paddingTop: '9px', paddingRight: '9px', paddingLeft: '9px' } }>
														<table align={ `${ blockAlignment }` } border="0" cellPadding="0" cellSpacing="0">
															<tbody>
																<tr>
																	<td align={ `${ blockAlignment }` } valign="top">
																		{ save ? (
																			// @todo
																			// Can't place strings around InnerBlocks.Content -- see https://github.com/WordPress/gutenberg/issues/10308
																			// For now we add this `div` tag and will do a string search and replace before displaying the newsletter.
																			<div className="boldermail-html-comment">
																				{ '[boldermail_html_comment]' }
																				{ '[if mso]>' }
																				{ `<table align="${ blockAlignment }" border="0" cellspacing="0" cellpadding="0">` }
																				{ '<tr>' }
																				{ '<![endif]' }
																				{ '[/boldermail_html_comment]' }
																			</div>
																		) : null }
																		{ children }
																		{ save ? (
																			<div className="boldermail-html-comment">
																				{ '[boldermail_html_comment]' }
																				{ '[if mso]>' }
																				{ '</tr>' }
																				{ '</table>' }
																				{ '<![endif]' }
																				{ '[/boldermail_html_comment]' }
																			</div>
																		) : null }
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
					</td>
				</tr>
			</tbody>
		</table>
	);
}
