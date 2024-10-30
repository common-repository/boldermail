// Internal dependencies.
import ButtonWrapper from './wrapper';

// WordPress dependencies.
const { RichText } = wp.blockEditor;

// Save function.
export default function save( props ) {
	const {
		attributes: {
			url,
			text,
			letterSpacing,
			textColor,
		},
	} = props;

	return (
		<ButtonWrapper { ...props }>
			<RichText.Content
				tagName="a"
				href={ url }
				style={ {
					letterSpacing,
					lineHeight: '100%',
					textAlign: 'center',
					textDecoration: 'none',
					color: textColor,
				} }
				value={ text }
				className="bmButton"
				target="_blank"
				rel="noopener noreferrer"
			/>
		</ButtonWrapper>
	);
}
