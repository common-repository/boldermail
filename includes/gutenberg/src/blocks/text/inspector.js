// Internal dependencies.
import {
	ColorPaletteControl,
	FontFamilyControl,
	FontSizePicker,
	LineHeightControl,
	UnitControl,
} from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;
const { AlignmentToolbar, InspectorControls } = wp.blockEditor;
const { BaseControl, PanelBody } = wp.components;

// Inspector controls.
const TextInspector = ( props ) => {
	const { attributes: { textAlign }, setAttributes } = props;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Text Settings', 'boldermail' ) } initialOpen={ true } >
				<BaseControl label={ __( 'Text Alignment', 'boldermail' ) } >
					<AlignmentToolbar
						value={ textAlign }
						onChange={ newTextAlign => setAttributes( { textAlign: newTextAlign } ) }
						isCollapsed={ false }
						alignmentControls={ [
							{
								icon: 'editor-alignleft',
								title: __( 'Align text left', 'boldermail' ),
								align: 'left',
							},
							{
								icon: 'editor-aligncenter',
								title: __( 'Align text center', 'boldermail' ),
								align: 'center',
							},
							{
								icon: 'editor-alignright',
								title: __( 'Align text right', 'boldermail' ),
								align: 'right',
							},
							{
								icon: 'editor-justify',
								title: __( 'Justify text', 'boldermail' ),
								align: 'justify',
							},
						] }
					/>
				</BaseControl>
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
				<LineHeightControl
					attribute={ 'lineHeight' }
					props={ props }
				/>
				<ColorPaletteControl
					label={ __( 'Text Color', 'boldermail' ) }
					attribute={ 'color' }
					props={ props }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default TextInspector;
