// Internal dependencies.
import BoxedTextWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;

// Save function.
export default function Save( props ) {
	return (
		<BoxedTextWrapper { ...{ ...props, save: true } }>
			<InnerBlocks.Content />
		</BoxedTextWrapper>
	);
}
