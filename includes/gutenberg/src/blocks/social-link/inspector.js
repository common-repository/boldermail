// Internal dependencies.
import { getTitleBySite } from './social-list';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl } = wp.components;
const { prependHTTP } = wp.url;

// Inspector controls.
const SocialLinkInspector = ( props ) => {
	const { attributes: {
		url,
		service,
		label,
	}, setAttributes } = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Settings', 'boldermail' ) } initialOpen={ true } >
				<TextControl
					label={ getTitleBySite( service ) + ' ' + ( service === 'forwardtofriend' ? __( 'Address', 'boldermail' ) : __( 'URL', 'boldermail' ) ) }
					onChange={ newUrl => setAttributes( { url: service === 'forwardtofriend' ? newUrl : prependHTTP( newUrl ) } ) }
					value={ url }
					{ ...( service === 'forwardtofriend' ? { type: 'email' } : { type: 'url' } ) }
				/>
				<TextControl
					label={ __( 'Link Text', 'boldermail' ) }
					onChange={ newLabel => setAttributes( { label: newLabel } ) }
					value={ label }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default SocialLinkInspector;
