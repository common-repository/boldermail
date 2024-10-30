// Internal dependencies.
import INITIAL_ATTRIBUTES from './attributes';

// WordPress dependencies.
const { __ } = wp.i18n;
const {
	BlockControls,
	BlockAlignmentToolbar,
	URLInput,
	URLPopover,
} = wp.blockEditor;
const {
	Button,
	KeyboardShortcuts,
	ToolbarButton,
	ToolbarGroup,
} = wp.components;
const { useState, Fragment } = wp.element;
const { rawShortcut, displayShortcut } = wp.keycodes;
const { prependHTTP } = wp.url;

// Toolbal controls.
const ButtonToolbar = ( props ) => {
	const { attributes: {
		url,
		blockAlignment,
	}, setAttributes, isSelected } = props;
	const [ showURLPopover, setShowURLPopover ] = useState( false );

	return (
		<Fragment>
			<BlockControls>
				<BlockAlignmentToolbar
					controls={ [ 'left', 'center', 'right', 'wide' ] }
					value={ blockAlignment }
					onChange={ newBlockAlignment => {
						setAttributes( { blockAlignment: newBlockAlignment || INITIAL_ATTRIBUTES.blockAlignment.default } );
					} }
					isCollapsed={ false }
				/>
				<ToolbarGroup>
					<ToolbarButton
						name="link"
						icon={
							<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" role="img" aria-hidden="true" focusable="false">
								<path d="M17.74 2.76c1.68 1.69 1.68 4.41 0 6.1l-1.53 1.52c-1.12 1.12-2.7 1.47-4.14 1.09l2.62-2.61.76-.77.76-.76c.84-.84.84-2.2 0-3.04-.84-.85-2.2-.85-3.04 0l-.77.76-3.38 3.38c-.37-1.44-.02-3.02 1.1-4.14l1.52-1.53c1.69-1.68 4.42-1.68 6.1 0zM8.59 13.43l5.34-5.34c.42-.42.42-1.1 0-1.52-.44-.43-1.13-.39-1.53 0l-5.33 5.34c-.42.42-.42 1.1 0 1.52.44.43 1.13.39 1.52 0zm-.76 2.29l4.14-4.15c.38 1.44.03 3.02-1.09 4.14l-1.52 1.53c-1.69 1.68-4.41 1.68-6.1 0-1.68-1.68-1.68-4.42 0-6.1l1.53-1.52c1.12-1.12 2.7-1.47 4.14-1.1l-4.14 4.15c-.85.84-.85 2.2 0 3.05.84.84 2.2.84 3.04 0z"></path>
							</svg>
						}
						className={ url ? 'is-pressed' : '' }
						title={ __( 'Add Call-to-Action', 'boldermail' ) }
						shortcut={ displayShortcut.primary( 'k' ) }
						onClick={ () => setShowURLPopover( true ) }
					/>
				</ToolbarGroup>
			</BlockControls>

			{ isSelected && (
				<KeyboardShortcuts
					bindGlobal
					shortcuts={ {
						[ rawShortcut.primary( 'k' ) ]: () => setShowURLPopover( true ),
					} }
				/>
			) }

			{ isSelected && showURLPopover && (
				<URLPopover
					position="top center"
					onClose={ () => setShowURLPopover( false ) }
				>
					<form
						className="block-editor-url-popover__link-editor"
						onSubmit={ ( event ) => {
							event.preventDefault();
							setShowURLPopover( false );
						} }
					>
						<div className="block-editor-url-input">
							<URLInput
								value={ url }
								onChange={ ( newUrl ) => setAttributes( { url: prependHTTP( newUrl ) } ) }
								placeholder={ __( 'Enter address', 'boldermail' ) }
								disableSuggestions={ true }
							/>
						</div>
						<Button
							icon={
								<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" role="img" aria-hidden="true" focusable="false">
									<path d="M16 4h2v9H7v3l-5-4 5-4v3h9V4z"></path>
								</svg>
							}
							label={ __( 'Apply', 'boldermail' ) }
							type="submit"
						/>
					</form>
				</URLPopover>
			) }
		</Fragment>
	);
};

export default ButtonToolbar;
