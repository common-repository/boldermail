// Internal dependencies.
import INITIAL_ATTRIBUTES from './attributes';

// Wrapper function.
export default function ButtonWrapper( { children, ...props } ) {
	const {
		attributes: {
			blockAlignment,
			fontFamily,
			fontSize,
			borderStyle,
			borderWidth,
			borderColor,
			backgroundColor,
			borderRadius,
			padding,
		},
	} = props;

	const align = ( blockAlignment !== 'wide' ) ? blockAlignment : 'center';
	const wide = ( blockAlignment === 'wide' ) ? blockAlignment : undefined;

	return (
		<table border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmButtonBlock" style={ { minWidth: '100%' } }>
			<tbody className="bmButtonBlockOuter">
				<tr>
					<td align={ `${ align }` } style={ { paddingTop: '9px', paddingRight: '18px', paddingBottom: '9px', paddingLeft: '18px' } } valign="top" className="bmButtonBlockInner">
						<table border="0" cellPadding="0" cellSpacing="0" { ...( wide ? { width: '100%' } : {} ) } className="bmButtonContentContainer" style={ {
							borderCollapse: 'separate',
							...( borderWidth && borderStyle && borderColor ) ? { borderWidth, borderStyle, borderColor } : null,
							borderRadius,
							backgroundColor,
						} }>
							<tbody>
								<tr>
									<td align="center" valign="middle" className="bmButtonContent" style={ {
										fontFamily,
										fontSize: fontSize ? fontSize + 'px' : INITIAL_ATTRIBUTES.fontSize.default + 'px',
										padding,
									} }>
										{ children }
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
