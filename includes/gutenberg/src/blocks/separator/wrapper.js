// Wrapper function.
export default function SeparatorWrapper( props ) {
	const {
		attributes: {
			paddingTop,
			paddingBottom,
			borderTopWidth,
			borderTopStyle,
			borderTopColor,
			backgroundColor
		},
	} = props;

	return (
		<table
			border="0"
			cellPadding="0"
			cellSpacing="0"
			width="100%"
			className="bmDividerBlock"
			style={ {
				minWidth: '100%',
				backgroundColor,
			} }
		>
			<tbody className="bmDividerBlockOuter">
				<tr>
					<td
						className="bmDividerBlockInner"
						style={ {
							minWidth: '100%',
							paddingTop,
							paddingRight: '18px',
							paddingBottom,
							paddingLeft: '18px',
						} }
					>
						<table
							className="bmDividerContent"
							border="0"
							cellPadding="0"
							cellSpacing="0"
							width="100%"
							style={ {
								minWidth: '100%',
								...( borderTopWidth && borderTopStyle && borderTopColor ? { borderTopWidth, borderTopStyle, borderTopColor } : null ),
							} }
						>
							<tbody>
								<tr>
									<td>
										<span></span>
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
