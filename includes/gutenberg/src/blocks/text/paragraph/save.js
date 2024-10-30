// Internal dependencies.
import TextWrapper from '../wrapper';

// WordPress dependencies.
const { RichText } = wp.blockEditor;

// Save function.
export default function save( props ) {
	const { attributes: {
		content,
		textAlign,
		fontFamily,
		fontSize,
		letterSpacing,
		lineHeight,
		color,
	} } = props;

	return (
		<TextWrapper { ...{ ...props, save: true } }>
			<RichText.Content
				tagName="p"
				value={ content }
				style={ {
					textAlign,
					fontFamily,
					fontSize: fontSize ? fontSize + 'px' : undefined,
					letterSpacing,
					lineHeight,
					color,
				} }
			/>
		</TextWrapper>
	);
}
