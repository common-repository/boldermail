// Internal dependencies.
import TextWrapper from '../wrapper';

// WordPress dependencies.
const { RichText } = wp.blockEditor;

// Save function.
export default function save( props ) {
	const { attributes: {
		content,
		level,
		textAlign,
		fontFamily,
		fontSize,
		letterSpacing,
		lineHeight,
		color,
	} } = props;

	const tagName = 'h' + level;

	return (
		<TextWrapper { ...{ ...props, save: true } }>
			<RichText.Content
				tagName={ tagName }
				style={ {
					textAlign,
					fontFamily,
					fontSize: fontSize ? fontSize + 'px' : undefined,
					letterSpacing,
					lineHeight,
					color,
				} }
				value={ content }
			/>
		</TextWrapper>
	);
}
