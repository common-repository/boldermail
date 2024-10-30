// WordPress dependencies.
const { Fragment } = wp.element;

// Wrapper function.
export default function TextWrapper( { children, ...props } ) {
	const { save } = props;

	return (
		<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmTextBlock" style={ { minWidth: '100%' } }>
			<tbody className="bmTextBlockOuter">
				<tr>
					<td valign="top" className="bmTextBlockInner" style={ { paddingTop: '9px' } }>
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
						<table align="left" border="0" cellPadding="0" cellSpacing="0" style={ { maxWidth: '100%', minWidth: '100%' } } width="100%" className="bmTextContentContainer">
							<tbody>
								<tr>
									<td valign="top" className="bmTextContent" style={ { paddingTop: '0px', paddingRight: '18px', paddingBottom: '9px', paddingLeft: '18px' } }>
										{ children }
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
