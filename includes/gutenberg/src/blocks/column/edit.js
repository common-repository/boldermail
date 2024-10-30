// Internal dependencies.
import ALLOWED_BLOCKS from '../template-part/allowed-blocks';
import ColumnWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;

// Edit function.
export default function edit( props ) {
	return (
		<ColumnWrapper { ...props }>
			<InnerBlocks
				allowedBlocks={ ALLOWED_BLOCKS.filter( e => e !== 'boldermail/columns' ) }
				templateLock={ false }
				renderAppender={ () => (
					<InnerBlocks.ButtonBlockAppender />
				) }
			/>
		</ColumnWrapper>
	);
}
