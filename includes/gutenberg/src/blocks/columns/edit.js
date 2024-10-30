// Internal dependencies.
import ColumnsWrapper from './wrapper';

// WordPress dependencies.
const { createBlock } = wp.blocks;
const { __experimentalBlockVariationPicker, InnerBlocks } = wp.blockEditor;
const { useDispatch, useSelect } = wp.data;
const { useRef } = wp.element;

// Create blocks from variation template.
const createBlocksFromInnerBlocksTemplate = ( innerBlocksTemplate ) => {
	return innerBlocksTemplate.map( ( [ name, attributes, innerBlocks = [] ] ) =>
		createBlock( name, attributes, createBlocksFromInnerBlocksTemplate( innerBlocks ) )
	);
};

// Edit function.
export default function edit( props ) {
	const { clientId, name, className } = props;
	const ref = useRef();

	const { blockType, defaultVariation, hasInnerBlocks, variations } = useSelect(
		( select ) => {
			const { getBlockVariations, getBlockType, getDefaultBlockVariation } = select( 'core/blocks' );

			return {
				blockType: getBlockType( name ),
				defaultVariation: getDefaultBlockVariation( name, 'block' ),
				hasInnerBlocks: select( 'core/block-editor' ).getBlocks( clientId ).length > 0,
				variations: getBlockVariations( name, 'block' ),
			};
		},
		[ clientId, name ]
	);

	const { replaceInnerBlocks } = useDispatch( 'core/block-editor' );

	if ( hasInnerBlocks ) {
		return (
			<div className={ className } ref={ ref }>
				<ColumnsWrapper { ...props }>
					<InnerBlocks
						templateLock="all"
						allowedBlocks={ [ 'boldermail/column' ] }
						__experimentalMoverDirection={ 'horizontal' }
						renderAppender={ () => <InnerBlocks.ButtonBlockAppender /> }
					/>
				</ColumnsWrapper>
			</div>
		);
	}

	return (
		<__experimentalBlockVariationPicker
			icon={ blockType && blockType.icon ? blockType.icon.src : undefined }
			label={ blockType ? blockType.title : undefined }
			variations={ variations }
			onSelect={ ( nextVariation = defaultVariation ) => {
				if ( nextVariation.attributes ) {
					props.setAttributes( nextVariation.attributes );
				}
				if ( nextVariation.innerBlocks ) {
					replaceInnerBlocks(
						props.clientId,
						createBlocksFromInnerBlocksTemplate( nextVariation.innerBlocks )
					);
				}
			} }
		/>
	);
}
