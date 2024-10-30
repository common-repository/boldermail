// Internal dependencies.
import INITIAL_ATTRIBUTES from './attributes';

// WordPress dependencies.
const { __ } = wp.i18n;
const { BlockControls, BlockAlignmentToolbar } = wp.blockEditor;
const { ToolbarButton, ToolbarGroup } = wp.components;

// Toolbar controls.
const SocialLinksControls = ( props ) => {
	const { attributes: {
		isFullWidth,
		blockAlignment,
		borderStyle,
		backgroundColor,
	}, setAttributes } = props;

	return (
		<BlockControls>
			<BlockAlignmentToolbar
				controls={ [ 'left', 'center', 'right' ] }
				value={ blockAlignment }
				onChange={ newBlockAlignment => setAttributes( { blockAlignment: newBlockAlignment || INITIAL_ATTRIBUTES.blockAlignment.default } ) }
			/>
			{ ( borderStyle !== 'none' || backgroundColor ) ? (
				<ToolbarGroup>
					<ToolbarButton
						name="wide"
						icon={
							<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" role="img" aria-hidden="true" focusable="false">
								<path d="M5 5h10V3H5v2zm12 8V7H3v6h14zM5 17h10v-2H5v2z"></path>
							</svg>
						}
						className={ isFullWidth ? 'is-pressed' : '' }
						title={ __( 'Full Width', 'boldermail' ) }
						onClick={ () => setAttributes( { isFullWidth: ! isFullWidth } ) }
					/>
				</ToolbarGroup>
			) : null }
		</BlockControls>
	);
};

export default SocialLinksControls;
