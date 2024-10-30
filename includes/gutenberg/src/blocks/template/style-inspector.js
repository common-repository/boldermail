// Internal dependencies.
import setStyleAttribute from './style';
import {
	ColorPaletteControl,
	FontFamilyControl,
	FontSizePicker,
	LineHeightControl,
	UnitControl,
} from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { Panel, PanelBody } = wp.components;

// Set style post meta.
const setStyleAttributes = ( newAttributes, props ) => {
	const { attributes, setAttributes } = props;
	setStyleAttribute( { ...attributes, ...newAttributes }, setAttributes );
};

// Heading inspector.
const TemplateStyleInspector = ( props ) => {
	return (
		<InspectorControls>
			<Panel header={ __( 'Heading Styles', 'boldermail' ) }>
				<PanelBody title={ __( 'Heading 1', 'boldermail' ) } initialOpen={ false }>
					<FontFamilyControl
						attribute={ 'h1FontFamily' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<FontSizePicker
						attribute={ 'h1FontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<UnitControl
						label={ __( 'Letter Spacing (in pixels)', 'boldermail' ) }
						attribute={ 'h1LetterSpacing' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'h1LineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<ColorPaletteControl
						label={ __( 'Text Color', 'boldermail' ) }
						attribute={ 'h1Color' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Heading 2', 'boldermail' ) } initialOpen={ false }>
					<FontFamilyControl
						attribute={ 'h2FontFamily' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<FontSizePicker
						attribute={ 'h2FontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<UnitControl
						label={ __( 'Letter Spacing (in pixels)', 'boldermail' ) }
						attribute={ 'h2LetterSpacing' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'h2LineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<ColorPaletteControl
						label={ __( 'Text Color', 'boldermail' ) }
						attribute={ 'h2Color' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Heading 3', 'boldermail' ) } initialOpen={ false }>
					<FontFamilyControl
						attribute={ 'h3FontFamily' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<FontSizePicker
						attribute={ 'h3FontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<UnitControl
						label={ __( 'Letter Spacing (in pixels)', 'boldermail' ) }
						attribute={ 'h3LetterSpacing' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'h3LineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<ColorPaletteControl
						label={ __( 'Text Color', 'boldermail' ) }
						attribute={ 'h3Color' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Heading 4', 'boldermail' ) } initialOpen={ false }>
					<FontFamilyControl
						attribute={ 'h4FontFamily' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<FontSizePicker
						attribute={ 'h4FontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<UnitControl
						label={ __( 'Letter Spacing (in pixels)', 'boldermail' ) }
						attribute={ 'h4LetterSpacing' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'h4LineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<ColorPaletteControl
						label={ __( 'Text Color', 'boldermail' ) }
						attribute={ 'h4Color' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
			</Panel>
			<Panel header={ __( 'Mobile Styles', 'boldermail' ) }>
				<PanelBody title={ __( 'Heading 1', 'boldermail' ) } initialOpen={ false }>
					<FontSizePicker
						attribute={ 'h1MobileFontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'h1MobileLineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Heading 2', 'boldermail' ) } initialOpen={ false }>
					<FontSizePicker
						attribute={ 'h2MobileFontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'h2MobileLineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Heading 3', 'boldermail' ) } initialOpen={ false }>
					<FontSizePicker
						attribute={ 'h3MobileFontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'h3MobileLineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Heading 4', 'boldermail' ) } initialOpen={ false }>
					<FontSizePicker
						attribute={ 'h4MobileFontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'h4MobileLineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
			</Panel>
		</InspectorControls>
	);
};

export default TemplateStyleInspector;
