// WordPress dependencies.
const { Fragment } = wp.element;

// Wrapper function.
export default function BoxedTextWrapper( { children, ...props } ) {
	const {
		attributes: {
			isWideWidth,
			borderStyle,
			borderWidth,
			borderColor,
			paddingTop,
			paddingBottom,
			backgroundUrl,
			backgroundColor,
			backgroundRepeat,
			backgroundPosition,
			backgroundSize
		},
		save,
	} = props;

	return (
		<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmBoxedTextBlock" style={ { minWidth: '100%' } }>
			<tbody className="bmBoxedTextBlockOuter">
				<tr>
					<td valign="top" className="bmBoxedTextBlockInner">
						{ save ? (
							<Fragment>
								{ '[boldermail_html_comment]' }
								{ '[if gte mso 9]>' }
								{ '<table align="center" border="0" cellSpacing="0" cellPadding="0" width="100%">' }
								{ '<td align="center" valign="top">' }
								{ '<![endif]' }
								{ '[/boldermail_html_comment]' }
							</Fragment>
						) : null }
						<table align="left" border="0" cellPadding="0" cellSpacing="0" width="100%" style={ { minWidth: '100%' } } className="bmBoxedTextContentContainer">
							<tbody>
								<tr>
									<td style={ { padding: isWideWidth ? '9px 0 9px' : '9px 18px' } }>
										<table
											border="0"
											cellSpacing="0"
											className="bmTextContentContainer"
											width="100%"
											style={ {
												minWidth: '100%',
												...( backgroundColor && backgroundUrl ? { background: `${ backgroundColor } url( ${ backgroundUrl } )` } : null ),
												...( !! backgroundColor && ! backgroundUrl ? { backgroundColor } : null ),
												...( backgroundUrl ? { backgroundImage: `url( ${ backgroundUrl } )` } : null ),
												...( borderWidth && borderStyle && borderColor ? { borderWidth, borderStyle, borderColor } : null ),
												...( backgroundUrl ? { backgroundRepeat, backgroundPosition, backgroundSize } : null ),
											} }
										>
											<tbody>
												<tr>
													<td valign="top" className="bmTextContent" style={ { paddingTop, paddingBottom } }>
														{ children }
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
								{ '[if gte mso 9]>' }
								{ '</table>' }
								{ '</td>' }
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
