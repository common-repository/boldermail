// Internal dependencies.
import {
	BorderControl,
	ColorPaletteControl,
	FontSizePicker,
	FontFamilyControl,
	RangeControl,
} from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl, TextControl } = wp.components;
const { Fragment } = wp.element;
const { prependHTTP } = wp.url;

// Inspector controls
const SocialSharesInspector = ( props ) => {
	const { attributes: {
		shareContent,
		customURL,
		urlDesc,
		iconStyle,
		iconColor,
		buttonBorderStyle,
		buttonBackgroundColor,
	}, setAttributes } = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Share Settings', 'boldermail' ) } initialOpen={ true } >
				<SelectControl
					label={ __( 'Content to Share', 'boldermail' ) }
					value={ shareContent }
					options={ [
						{ value: 'campaign', label: __( 'Campaign page URL', 'boldermail' ) },
						{ value: 'custom', label: __( 'Custom URL', 'boldermail' ) },
					] }
					onChange={ newShareContent => setAttributes( { shareContent: newShareContent } ) }
				/>
				{ shareContent === 'custom' ? (
					<Fragment>
						<TextControl
							label={ __( 'Custom URL to Share', 'boldermail' ) }
							value={ customURL }
							onChange={ newCustomURL => setAttributes( { customURL: prependHTTP( newCustomURL ) } ) }
							type="url"
						/>
						<TextControl
							label={ __( 'Short Description', 'boldermail' ) }
							value={ urlDesc }
							onChange={ newUrlDesc => setAttributes( { urlDesc: newUrlDesc } ) }
						/>
					</Fragment>
				) : null }
			</PanelBody>
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
			<PanelBody title={ __( 'Container Styles', 'boldermail' ) } initialOpen={ true } >
				<BorderControl
					attribute={ {
						borderStyle: 'borderStyle',
						borderWidth: 'borderWidth',
						borderColor: 'borderColor',
					} }
					props={ props }
				/>
				<ColorPaletteControl
					label={ __( 'Background Color', 'boldermail' ) }
					attribute={ 'backgroundColor' }
					props={ props }
				/>
			</PanelBody>
			<PanelBody title={ __( 'Button Styles', 'boldermail' ) } initialOpen={ true } >
				<BorderControl
					attribute={ {
						borderStyle: 'buttonBorderStyle',
						borderWidth: 'buttonBorderWidth',
						borderColor: 'buttonBorderColor',
					} }
					props={ props }
				/>
				<ColorPaletteControl
					label={ __( 'Background Color', 'boldermail' ) }
					attribute={ 'buttonBackgroundColor' }
					props={ props }
				/>
				{ buttonBorderStyle !== 'none' || buttonBackgroundColor ? (
					<RangeControl
						label={ __( 'Border Radius (%)', 'boldermail' ) }
						attribute={ 'buttonBorderRadius' }
						props={ props }
					/>
				) : null }
			</PanelBody>
		</InspectorControls>
	);
};

export default SocialSharesInspector;
