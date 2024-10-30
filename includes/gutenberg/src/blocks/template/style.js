// Internal dependencies.
import INITIAL_ATTRIBUTES from './attributes';

// External dependencies.
import { default as minifyCssString } from 'minify-css-string';

// Style attributes.
const setStyleAttribute = ( attributes, setAttributes ) => {
	/* eslint-disable no-multi-str */

	let STYLE =
		'\
		#bodyTable h1 {\
			color: [h1Color];\
			font-family: [h1FontFamily];\
			font-size: [h1FontSize]px;\
			line-height: [h1LineHeight];\
			letter-spacing: [h1LetterSpacing];\
		}\
		#bodyTable h2 {\
			color: [h2Color];\
			font-family: [h2FontFamily];\
			font-size: [h2FontSize]px;\
			line-height: [h2LineHeight];\
			letter-spacing: [h2LetterSpacing];\
		}\
		#bodyTable h3 {\
			color: [h3Color];\
			font-family: [h3FontFamily];\
			font-size: [h3FontSize]px;\
			line-height: [h3LineHeight];\
			letter-spacing: [h3LetterSpacing];\
		}\
		#bodyTable h4 {\
			color: [h4Color];\
			font-family: [h4FontFamily];\
			font-size: [h4FontSize]px;\
			line-height: [h4LineHeight];\
			letter-spacing: [h4LetterSpacing];\
		}\
		@media only screen and (max-width: 480px) {\
			#bodyTable h1 {\
				font-size: [h1MobileFontSize]px !important;\
				line-height: [h1MobileLineHeight] !important;\
			}\
			#bodyTable h2 {\
				font-size: [h2MobileFontSize]px !important;\
				line-height: [h2MobileLineHeight] !important;\
			}\
			#bodyTable h3 {\
				font-size: [h3MobileFontSize]px !important;\
				line-height: [h3MobileLineHeight] !important;\
			}\
			#bodyTable h4 {\
				font-size: [h4MobileFontSize]px !important;\
				line-height: [h4MobileLineHeight] !important;\
			}\
		}\
	';

	STYLE = STYLE.replace( '[h1Color]', attributes.h1Color || INITIAL_ATTRIBUTES.h1Color.default );
	STYLE = STYLE.replace( '[h1FontFamily]', attributes.h1FontFamily || INITIAL_ATTRIBUTES.h1FontFamily.default );
	STYLE = STYLE.replace( '[h1FontSize]', attributes.h1FontSize.toString() || INITIAL_ATTRIBUTES.h1FontSize.default.toString() );
	STYLE = STYLE.replace( '[h1LineHeight]', attributes.h1LineHeight || INITIAL_ATTRIBUTES.h1LineHeight.default );
	STYLE = STYLE.replace( '[h1LetterSpacing]', attributes.h1LetterSpacing || INITIAL_ATTRIBUTES.h1LetterSpacing.default );

	STYLE = STYLE.replace( '[h2Color]', attributes.h2Color || INITIAL_ATTRIBUTES.h2Color.default );
	STYLE = STYLE.replace( '[h2FontFamily]', attributes.h2FontFamily || INITIAL_ATTRIBUTES.h2FontFamily.default );
	STYLE = STYLE.replace( '[h2FontSize]', attributes.h2FontSize.toString() || INITIAL_ATTRIBUTES.h2FontSize.default.toString() );
	STYLE = STYLE.replace( '[h2LineHeight]', attributes.h2LineHeight || INITIAL_ATTRIBUTES.h2LineHeight.default );
	STYLE = STYLE.replace( '[h2LetterSpacing]', attributes.h2LetterSpacing || INITIAL_ATTRIBUTES.h2LetterSpacing.default );

	STYLE = STYLE.replace( '[h3Color]', attributes.h3Color || INITIAL_ATTRIBUTES.h3Color.default );
	STYLE = STYLE.replace( '[h3FontFamily]', attributes.h3FontFamily || INITIAL_ATTRIBUTES.h3FontFamily.default );
	STYLE = STYLE.replace( '[h3FontSize]', attributes.h3FontSize.toString() || INITIAL_ATTRIBUTES.h3FontSize.default.toString() );
	STYLE = STYLE.replace( '[h3LineHeight]', attributes.h3LineHeight || INITIAL_ATTRIBUTES.h3LineHeight.default );
	STYLE = STYLE.replace( '[h3LetterSpacing]', attributes.h3LetterSpacing || INITIAL_ATTRIBUTES.h3LetterSpacing.default );

	STYLE = STYLE.replace( '[h4Color]', attributes.h4Color || INITIAL_ATTRIBUTES.h4Color.default );
	STYLE = STYLE.replace( '[h4FontFamily]', attributes.h4FontFamily || INITIAL_ATTRIBUTES.h4FontFamily.default );
	STYLE = STYLE.replace( '[h4FontSize]', attributes.h4FontSize.toString() || INITIAL_ATTRIBUTES.h4FontSize.default.toString() );
	STYLE = STYLE.replace( '[h4LineHeight]', attributes.h4LineHeight || INITIAL_ATTRIBUTES.h4LineHeight.default );
	STYLE = STYLE.replace( '[h4LetterSpacing]', attributes.h4LetterSpacing || INITIAL_ATTRIBUTES.h4LetterSpacing.default );

	STYLE = STYLE.replace( '[h1MobileFontSize]', attributes.h1MobileFontSize.toString() || INITIAL_ATTRIBUTES.h1MobileFontSize.default.toString() );
	STYLE = STYLE.replace( '[h1MobileLineHeight]', attributes.h1MobileLineHeight || INITIAL_ATTRIBUTES.h1MobileLineHeight.default );
	STYLE = STYLE.replace( '[h2MobileFontSize]', attributes.h2MobileFontSize.toString() || INITIAL_ATTRIBUTES.h2MobileFontSize.default.toString() );
	STYLE = STYLE.replace( '[h2MobileLineHeight]', attributes.h2MobileLineHeight || INITIAL_ATTRIBUTES.h2MobileLineHeight.default );
	STYLE = STYLE.replace( '[h3MobileFontSize]', attributes.h3MobileFontSize.toString() || INITIAL_ATTRIBUTES.h3MobileFontSize.default.toString() );
	STYLE = STYLE.replace( '[h3MobileLineHeight]', attributes.h3MobileLineHeight || INITIAL_ATTRIBUTES.h3MobileLineHeight.default );
	STYLE = STYLE.replace( '[h4MobileFontSize]', attributes.h4MobileFontSize.toString() || INITIAL_ATTRIBUTES.h4MobileFontSize.default.toString() );
	STYLE = STYLE.replace( '[h4MobileLineHeight]', attributes.h4MobileLineHeight || INITIAL_ATTRIBUTES.h4MobileLineHeight.default );

	setAttributes( { style: minifyCssString( STYLE ) } );
};

export default setStyleAttribute;
