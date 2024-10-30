// Internal dependencies.
import {
	BorderControl,
	ColorPaletteControl,
	FontFamilyControl,
	FontSizePicker,
} from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl } = wp.components;

// Inspector controls
const SocialLinksInspector = ( props ) => {
	const { attributes: {
		display,
		iconStyle,
		iconSize,
		iconColor,
	}, setAttributes } = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Display Settings', 'boldermail' ) } initialOpen={ true } >
				<SelectControl
					label={ __( 'Display', 'boldermail' ) }
					value={ display }
					options={ [
						{ value: 'icon', label: __( 'Icon only', 'boldermail' ) },
						{ value: 'text', label: __( 'Text only', 'boldermail' ) },
						{ value: 'both', label: __( 'Both icon and text', 'boldermail' ) },
					] }
					onChange={ newdisplay => setAttributes( { display: newdisplay } ) }
				/>
			</PanelBody>
			{ display === 'icon' || display === 'both' ? (
				<PanelBody title={ __( 'Icon Settings', 'boldermail' ) } initialOpen={ true } >
					<SelectControl
						label={ __( 'Icon Style', 'boldermail' ) }
						value={ iconStyle }
						options={ [
							{ value: '', label: __( 'Solid', 'boldermail' ) },
							{ value: 'outline', label: __( 'Outlined', 'boldermail' ) },
						] }
						onChange={ newIconStyle => setAttributes( { iconStyle: newIconStyle } ) }
					/>
					<SelectControl
						label={ __( 'Icon Size', 'boldermail' ) }
						value={ iconSize }
						options={ [
							{ value: 48, label: __( 'Small', 'boldermail' ) },
							{ value: 96, label: __( 'Large', 'boldermail' ) },
						] }
						onChange={ newIconSize => setAttributes( { iconSize: parseInt( newIconSize ) } ) }
					/>
					<SelectControl
						label={ __( 'Icon Color', 'boldermail' ) }
						value={ iconColor }
						options={ [
							{ value: 'color', label: __( 'Color', 'boldermail' ) },
							{ value: 'dark', label: __( 'Dark', 'boldermail' ) },
							{ value: 'light', label: __( 'Light', 'boldermail' ) },
							{ value: 'gray', label: __( 'Gray', 'boldermail' ) },
						] }
						onChange={ newIconColor => setAttributes( { iconColor: newIconColor } ) }
					/>
				</PanelBody>
			) : null }
			{ display === 'text' || display === 'both' ? (
				<PanelBody title={ __( 'Text Settings', 'boldermail' ) } initialOpen={ true } >
					<FontFamilyControl
						attribute={ 'fontFamily' }
						props={ props }
					/>
					<FontSizePicker
						attribute={ 'fontSize' }
						props={ props }
					/>
					<ColorPaletteControl
						label={ __( 'Text Color', 'boldermail' ) }
						attribute={ 'textColor' }
						props={ props }
					/>
				</PanelBody>
			) : null }
			<PanelBody title={ __( 'Border', 'boldermail' ) } initialOpen={ true } >
				<BorderControl
					attribute={ {
						borderStyle: 'borderStyle',
						borderWidth: 'borderWidth',
						borderColor: 'borderColor',
					} }
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
		</InspectorControls>
	);
};

export default SocialLinksInspector;
