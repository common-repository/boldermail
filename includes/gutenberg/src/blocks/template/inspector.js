// Internal dependencies.
import { ColorPaletteControl, BorderControl } from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;

// Inspector controls.
const TemplateInspector = ( props ) => {
	return (
		<InspectorControls>
			<PanelBody title={ __( 'Background Style', 'boldermail' ) } initialOpen={ true }>
				<ColorPaletteControl
					label={ __( 'Background Color', 'boldermail' ) }
					attribute={ 'backgroundColor' }
					props={ props }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Border Top', 'boldermail' ) } initialOpen={ true }>
				<BorderControl
					attribute={ {
						borderStyle: 'borderTopStyle',
						borderWidth: 'borderTopWidth',
						borderColor: 'borderTopColor',
					} }
					props={ props }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default TemplateInspector;
