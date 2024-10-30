// Internal dependencies.
import {
	BorderControl,
	ColorPaletteControl,
	BackgroundPositionControl,
	BackgroundRepeatControl,
	BackgroundSizeControl,
	UnitControl
} from "../../components";
import { attributesFromMedia } from './utils.js';

// WordPress dependencies.
const { __ } = wp.i18n;
const { compose, withInstanceId } = wp.compose;
const { BlockIcon, InspectorControls, MediaPlaceholder } = wp.blockEditor;
const { PanelBody, withNotices } = wp.components;

// Inspector controls.
const BoxedTextInspector = ( props ) => {
	const {
		attributes: { backgroundUrl },
		setAttributes,
		noticeUI,
		noticeOperations,
	} = props;

	const { removeAllNotices, createErrorNotice } = noticeOperations;

	return (
		<InspectorControls>
			<PanelBody title={ __( 'Border Settings', 'boldermail' ) } initialOpen={ true }>
				<BorderControl
					attribute={ {
						borderStyle: 'borderStyle',
						borderWidth: 'borderWidth',
						borderColor: 'borderColor',
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
			<PanelBody title={ __( 'Background', 'boldermail' ) } initialOpen={ true }>
				<div className="components-base-control">
					{ !! backgroundUrl ? (
						<img alt="" src={ backgroundUrl } />
					) : (
						<MediaPlaceholder
							icon={ <BlockIcon icon={ 'format-image' } /> }
							labels={ {
								title: __( 'Background Image', 'boldermail' ),
								instructions: __( 'Upload an image file, or pick one from your media library.', 'boldermail' ),
							} }
							onSelect={ attributesFromMedia( setAttributes ) }
							accept="image/*"
							allowedTypes={ [ 'image' ] }
							notices={ noticeUI }
							onError={ ( message ) => {
								removeAllNotices();
								createErrorNotice( message );
							} }
						/>
					) }
				</div>
				<BackgroundSizeControl
					attribute={ 'backgroundSize' }
					props={ props }
				/>
				<BackgroundPositionControl
					attribute={ 'backgroundPosition' }
					props={ props }
				/>
				<BackgroundRepeatControl
					attribute={ 'backgroundRepeat' }
					props={ props }
				/>
				<ColorPaletteControl
					label={ !! backgroundUrl ? __( 'Fallback Background Color', 'boldermail' ) : __( 'Background Color', 'boldermail' ) }
					attribute={ 'backgroundColor' }
					props={ props }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

export default compose( [
	withNotices,
	withInstanceId,
] )( BoxedTextInspector );
