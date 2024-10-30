// Internal dependencies.
import ColumnWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;

// Save function
export default function save( props ) {
	return (
		<ColumnWrapper { ...{ ...props, save: true } }>
			<InnerBlocks.Content />
		</ColumnWrapper>
	);
}
