// External dependencies.
import classnames from "classnames";

// WordPress dependencies.
const { RichText } = wp.blockEditor;

// Save function.
export default function save( props ) {
	const { attributes: {
		url,
		alt,
		caption,
		align,
		href,
		rel,
		linkClass,
		width,
		height,
		id,
		linkTarget,
		sizeSlug,
		title,
		textColor,
		fontFamily,
		fontSize,
		lineHeight,
		letterSpacing,
		textAlign,
		borderWidth,
		borderStyle,
		borderColor,
		borderRadius,
	} } = props;

	const newRel = ! rel ? undefined : rel;

	const classes = classnames( {
		[ `align${ align }` ]: align,
		[ `size-${ sizeSlug }` ]: sizeSlug,
		'is-resized': width || height,
	} );

	const image = (
		<img
			src={ url }
			alt={ alt }
			className={ id ? `wp-image-${ id }` : null }
			width={ width }
			height={ height }
			title={ title }
			style={ {
				/* maxWidth: '600px', */
				width: width ? width + 'px' : undefined,
				height: height ? height + 'px' : undefined,
				...( borderWidth && borderStyle && borderColor ) ? { borderWidth, borderStyle, borderColor } : null,
				borderRadius,
			} }
		/>
	);

	return (
		<table border="0" cellPadding="0" cellSpacing="0" width="100%" className={ classnames( classes, 'bmCaptionBlock' ) }>
			<tbody className="bmCaptionBlockOuter">
				<tr>
					<td className="bmCaptionBlockInner" valign="top" style={ { padding: '9px' } }>
						<table align="left" border="0" cellPadding="0" cellSpacing="0" width="100%" className="bmCaptionBottomContent" style={ { minWidth: '100%' } }>
							<tbody>
								<tr>
									<td className="bmCaptionBottomImageContent" align={ `${ align || 'center' }` } valign="top" style={ { padding: '0 9px 9px 9px' } }>
										{ href ? (
											<a
												className={ linkClass }
												href={ href }
												target={ linkTarget }
												rel={ newRel }
											>
												{ image }
											</a>
										) : (
											image
										) }
									</td>
								</tr>
								{ ! RichText.isEmpty( caption ) ? (
									<tr>
										<td className="bmTextContent" valign="top" width="564" style={ {
											padding: '0px 9px',
											color: textColor,
											fontFamily,
											fontSize: fontSize ? fontSize + 'px' : undefined,
											lineHeight,
											letterSpacing,
											textAlign,
										} } >
											<RichText.Content tagName="div" value={ caption } />
										</td>
									</tr>
								) : null }
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	);
}
