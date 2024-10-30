// WordPress dependencies.
const { Fragment } = wp.element;

// Wrapper function.
export default function TemplatePartWrapper( { children, ...props } ) {
	const { attributes: {
		part,
		isWideWidth,
		backgroundColor,
		borderTopWidth,
		borderTopStyle,
		borderTopColor,
		borderBottomWidth,
		borderBottomStyle,
		borderBottomColor,
		paddingTop,
		paddingBottom,
	}, save } = props;

	const id = 'template' + part.charAt( 0 ).toUpperCase() + part.slice( 1 ); // i.e. templatePreheader
	const className = part + 'Container'; // i.e. preheaderContainer
	const style = {
		backgroundColor,
		...( borderTopWidth && borderTopStyle && borderTopColor ) ? { borderTopWidth, borderTopStyle, borderTopColor } : null,
		...( borderBottomWidth && borderBottomStyle && borderBottomColor ) ? { borderBottomWidth, borderBottomStyle, borderBottomColor } : null,
		paddingTop,
		paddingBottom,
	};

	return (
		<Fragment>
			{ isWideWidth ? (
				<table border="0" cellPadding="0" cellSpacing="0" width="100%">
					<tbody>
						<tr>
							<td align="center" valign="top" id={ id } style={ style }>
								{ save ? (
									<Fragment>
										{ '[boldermail_html_comment]' }
										{ '[if (gte mso 9)|(IE)]>' }
										{ '<table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">' }
										{ '<tr>' }
										{ '<td align="center" valign="top" width="600" style="width:600px;">' }
										{ '<![endif]' }
										{ '[/boldermail_html_comment]' }
									</Fragment>
								) : null }
								<table align="center" border="0" cellPadding="0" cellSpacing="0" width="100%" className="templateContainer">
									<tbody>
										<tr>
											<td valign="top" className={ className }>
												{ children }
											</td>
										</tr>
									</tbody>
								</table>
								{ save ? (
									<Fragment>
										{ '[boldermail_html_comment]' }
										{ '[if (gte mso 9)|(IE)]>' }
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
			) : (
				<Fragment>
					{ save ? (
						<Fragment>
							{ '[boldermail_html_comment]' }
							{ '[if (gte mso 9)|(IE)]>' }
							{ '<table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">' }
							{ '<tr>' }
							{ '<td align="center" valign="top" width="600" style="width:600px;">' }
							{ '<![endif]' }
							{ '[/boldermail_html_comment]' }
						</Fragment>
					) : null }
					<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="templateContainer">
						<tbody>
							<tr>
								<td valign="top" id={ id } style={ style }>
									{ children }
								</td>
							</tr>
						</tbody>
					</table>
					{ save ? (
						<Fragment>
							{ '[boldermail_html_comment]' }
							{ '[if (gte mso 9)|(IE)]>' }
							{ '</td>' }
							{ '</tr>' }
							{ '</table>' }
							{ '<![endif]' }
							{ '[/boldermail_html_comment]' }
						</Fragment>
					) : null }
				</Fragment>
			) }
		</Fragment>
	);
}
