// Internal dependencies.
import { BorderControl, ColorPaletteControl, UnitControl } from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;

// Inspector controls.
const SeparatorInspector = ( props ) => {
	return (
		<InspectorControls>
			<PanelBody title={ __( 'Separator Style', 'boldermail' ) } initialOpen={ true }>
				<UnitControl
					label={ __( 'Padding Top', 'boldermail' ) }
					attribute={ 'paddingTop' }
					props={ props }
				/>
				<UnitControl
					label={ __( 'Padding Bottom', 'boldermail' ) }
					attribute={ 'paddingBottom' }
					props={ props }
				/>
				<BorderControl
					attribute={ {
						borderStyle: 'borderTopStyle',
						borderWidth: 'borderTopWidth',
						borderColor: 'borderTopColor',
					} }
					props={ props }
				/>
				<ColorPaletteControl
					label={ __( 'Background Color', 'boldermail' ) }
					attribute={ 'backgroundColor' }
					props={ props }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default SeparatorInspector;
