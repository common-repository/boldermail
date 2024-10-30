// Internal dependencies.
import ColumnsWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;

// Save function.
export default function save( props ) {
	return (
		<ColumnsWrapper { ...{ ...props, save: true } }>
			<InnerBlocks.Content />
		</ColumnsWrapper>
	);
}
