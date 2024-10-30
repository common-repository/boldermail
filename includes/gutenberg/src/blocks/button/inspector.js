// Internal dependencies.
import {
	BorderControl,
	ColorPaletteControl,
	FontFamilyControl,
	FontSizePicker,
	UnitControl,
} from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody } = wp.components;

// Inspector controls.
const ButtonInspector = ( props ) => {
	return (
		<InspectorControls>
			<PanelBody title={ __( 'Text Settings', 'boldermail' ) } initialOpen={ true } >
				<FontFamilyControl
					attribute={ 'fontFamily' }
					props={ props }
				/>
				<FontSizePicker
					attribute={ 'fontSize' }
					props={ props }
				/>
				<UnitControl
					label={ __( 'Letter Spacing (in pixels)', 'boldermail' ) }
					attribute={ 'letterSpacing' }
					props={ props }
				/>
				<ColorPaletteControl
					label={ __( 'Text Color', 'boldermail' ) }
					attribute={ 'textColor' }
					props={ props }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Border Settings', 'boldermail' ) } initialOpen={ true } >
				<BorderControl
					attribute={ {
						borderStyle: 'borderStyle',
						borderWidth: 'borderWidth',
						borderColor: 'borderColor',
					} }
					props={ props }
				/>
				<UnitControl
					label={ __( 'Border Radius', 'boldermail' ) }
					attribute={ 'borderRadius' }
					props={ props }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Background', 'boldermail' ) } initialOpen={ true } >
				<ColorPaletteControl
					label={ __( 'Background Color', 'boldermail' ) }
					attribute={ 'backgroundColor' }
					props={ props }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Padding', 'boldermail' ) } initialOpen={ true } >
				<UnitControl
					label={ __( 'Padding (in pixels)', 'boldermail' ) }
					attribute={ 'padding' }
					props={ props }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default ButtonInspector;
