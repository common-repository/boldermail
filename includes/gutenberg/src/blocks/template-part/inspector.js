// Internal dependencies.
import { getTitleByPart } from './utils';
import {
	BorderControl,
	ColorPaletteControl,
	FontFamilyControl,
	FontSizePicker,
	LineHeightControl,
	UnitControl,
} from '../../components';
import setStyleAttribute from './style';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { Panel, PanelBody } = wp.components;

// Set style post meta.
const setStyleAttributes = ( newAttributes, props ) => {
	const { attributes, setAttributes } = props;
	setStyleAttribute( { ...attributes, ...newAttributes }, setAttributes );
};

// Template controls.
const TemplateInspector = ( props ) => {
	const {
		attributes: { part },
	} = props;

	return (
		<InspectorControls>
			<Panel header={ getTitleByPart( part ) }>
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
				<PanelBody title={ __( 'Border Bottom', 'boldermail' ) } initialOpen={ true }>
					<BorderControl
						attribute={ {
							borderStyle: 'borderBottomStyle',
							borderWidth: 'borderBottomWidth',
							borderColor: 'borderBottomColor',
						} }
						props={ props }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Padding', 'boldermail' ) } initialOpen={ true }>
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
				</PanelBody>
				<PanelBody title={ __( 'Text Styles', 'boldermail' ) } initialOpen={ true }>
					<FontFamilyControl
						attribute={ 'textFontFamily' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<FontSizePicker
						attribute={ 'textFontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<UnitControl
						label={ __( 'Text Spacing (in pixels)', 'boldermail' ) }
						attribute={ 'textLetterSpacing' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'textLineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<ColorPaletteControl
						label={ __( 'Text Color', 'boldermail' ) }
						attribute={ 'textColor' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<ColorPaletteControl
						label={ __( 'Link Color', 'boldermail' ) }
						attribute={ 'linkColor' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Mobile Styles', 'boldermail' ) } initialOpen={ true }>
					<FontSizePicker
						attribute={ 'mobileTextFontSize' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
					<LineHeightControl
						attribute={ 'mobileTextLineHeight' }
						props={ props }
						onChange={ setStyleAttributes }
					/>
				</PanelBody>
			</Panel>
		</InspectorControls>
	);
};

export default TemplateInspector;
