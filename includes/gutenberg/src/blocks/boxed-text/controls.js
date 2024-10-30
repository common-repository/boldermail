// Internal dependencies.
import { attributesFromMedia } from './utils.js';

// WordPress dependencies.
const { __ } = wp.i18n;
const { BlockAlignmentToolbar, BlockControls, MediaReplaceFlow } = wp.blockEditor;
const { ToolbarButton, ToolbarGroup } = wp.components;

// Toolbar controls.
const BoxedTextControls = ( props ) => {
	const {
		attributes: { isWideWidth, backgroundId, backgroundUrl },
		setAttributes,
	} = props;

	return (
		<BlockControls>
			<BlockAlignmentToolbar
				controls={ [ 'wide' ] }
				value={ isWideWidth ? 'wide' : '' }
				onChange={ ( newBlockAlignment ) => {
					setAttributes( { isWideWidth: newBlockAlignment === 'wide' } );
				} }
				isCollapsed={ false }
			/>
			{ !! backgroundUrl && (
				<MediaReplaceFlow
					mediaId={ backgroundId }
					mediaURL={ backgroundUrl }
					allowedTypes={ [ 'image' ] }
					accept="image/*"
					onSelect={ attributesFromMedia( setAttributes ) }
				/>
			) }
			{ !! backgroundUrl && (
				<ToolbarGroup>
					<ToolbarButton
						name="clear"
						icon={ 'editor-removeformatting' }
						title={ __( 'Clear Background', 'boldermail' ) }
						onClick={ () =>
							setAttributes( {
								backgroundUrl: undefined,
								backgroundId: undefined,
							} )
						}
					/>
				</ToolbarGroup>
			) }
		</BlockControls>
	);
};

export default BoxedTextControls;
