// Internal dependencies.
import INITIAL_ATTRIBUTES from './attributes';

// WordPress dependencies.
const { BlockControls, BlockAlignmentToolbar } = wp.blockEditor;

// Toolbar controls.
const TemplateControls = ( props ) => {
	const { attributes: { blockAlignment }, setAttributes } = props;

	return (
		<BlockControls>
			<BlockAlignmentToolbar
				controls={ [ 'left', 'center', 'right', 'wide' ] }
				value={ blockAlignment }
				onChange={ newBlockAlignment => {
					setAttributes( { blockAlignment: newBlockAlignment || INITIAL_ATTRIBUTES.blockAlignment.default } );
				} }
				isCollapsed={ false }
			/>
		</BlockControls>
	);
};

export default TemplateControls;
