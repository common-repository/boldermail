// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl } = wp.components;

// Inspector controls.
const SocialShareInspector = ( props ) => {
	const { attributes: {
		label,
	}, setAttributes } = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Settings', 'boldermail' ) } initialOpen={ true } >
				<TextControl
					label={ __( 'Link Text', 'boldermail' ) }
					onChange={ newLabel => setAttributes( { label: newLabel } ) }
					value={ label }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default SocialShareInspector;
