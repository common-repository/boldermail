// WordPress dependencies.
const { __ } = wp.i18n;
const { Fragment } = wp.element;

// Wrapper function.
export default function CountdownWrapper( props ) {
	const {
		attributes: { timestamp },
		save,
	} = props;

	const REST_API_ENDPOINT = window.boldermail.restUrl + 'boldermail/v1/countdown/';

	return (
		<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmCountdownBlock" style={ { minWidth: '100%' } }>
			<tbody className="bmCountdownBlockOuter">
				<tr>
					<td valign="top" className="bmCountdownBlockInner" style={ { paddingTop: '9px' } }>
						{ save ? (
							<Fragment>
								{ '[boldermail_html_comment]' }
								{ '[if mso]>' }
								{ '<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">' }
								{ '<tr>' }
								{ '<td valign="top" width="600" style="width:600px;">' }
								{ '<![endif]' }
								{ '[/boldermail_html_comment]' }
							</Fragment>
						) : null }
						<table align="left" border="0" cellPadding="0" cellSpacing="0" style={ { maxWidth: '100%', minWidth: '100%' } } width="100%" className="bmCountdownContentContainer">
							<tbody>
								<tr>
									<td align="center" valign="top" className="bmCountdownContent" style={ { paddingTop: '0px', paddingRight: '18px', paddingBottom: '0px', paddingLeft: '18px' } }>
										<img src={ `${ REST_API_ENDPOINT }${ timestamp }` } alt="" width="400" style={ { maxWidth: '400px', display: 'block' } } />
									</td>
								</tr>
								<tr>
									<td>
										<table
											align="center"
											border="0"
											cellPadding="0"
											cellSpacing="0"
											width="354"
											style={ {
												maxWidth: '400px',
												minWidth: '400px',
												paddingBottom: '9px',
												borderCollapse: 'separate',
											} }
										>
											<tbody>
												<tr>
													<td align="center" width="25%">
														<span
															style={ {
																fontFamily: 'Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif',
																fontSize: '12px',
																color: '#000000',
																lineHeight: '18px',
															} }
														>
															{ __( 'Days', 'boldermail' ) }
														</span>
													</td>
													<td align="center" width="25%">
														<span
															style={ {
																fontFamily: 'Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif',
																fontSize: '12px',
																color: '#000000',
																lineHeight: '18px',
															} }
														>
															{ __( 'Minutes', 'boldermail' ) }
														</span>
													</td>
													<td align="center" width="25%">
														<span
															style={ {
																fontFamily: 'Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif',
																fontSize: '12px',
																color: '#000000',
																lineHeight: '18px',
															} }
														>
															{ __( 'Hours', 'boldermail' ) }
														</span>
													</td>
													<td align="center" width="25%">
														<span
															style={ {
																fontFamily: 'Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif',
																fontSize: '12px',
																color: '#000000',
																lineHeight: '18px',
															} }
														>
															{ __( 'Seconds', 'boldermail' ) }
														</span>
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
								{ '</tr>' }
								{ '</table>' }
								{ '<![endif]' }
								{ '[/boldermail_html_comment]' }
							</Fragment>
						) : null }
					</td>
				</tr>
			</tbody>
		</table>
	);
}
