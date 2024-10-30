// Internal dependencies.
import TextWrapper from '../wrapper';

// WordPress dependencies.
const { RichText } = wp.blockEditor;

// Save function.
export default function save( props ) {
	const { attributes: {
		ordered,
		values,
		type,
		reversed,
		start,
		textAlign,
		fontFamily,
		fontSize,
		letterSpacing,
		lineHeight,
		color,
	} } = props;
	const tagName = ordered ? 'ol' : 'ul';

	return (
		<TextWrapper { ...{ ...props, save: true } }>
			<RichText.Content
				tagName={ tagName }
				value={ values }
				type={ type }
				reversed={ reversed }
				start={ start }
				multiline="li"
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
