// Internal dependencies.
import { getAttributesByPart } from './utils';

// External dependencies.
import { default as minifyCssString } from 'minify-css-string';

// Style attributes.
const setStyleAttribute = ( attributes, setAttributes ) => {
	const { part } = attributes;
	const id = 'template' + part.charAt( 0 ).toUpperCase() + part.slice( 1 ); // i.e. templatePreheader
	const INITIAL_ATTRIBUTES = getAttributesByPart( part );
	const styleAttribute = `${ part }Style`;

	/* eslint-disable no-multi-str */
	let STYLE = `\
		#${ id } .bmTextContent,\
		#${ id } .bmTextContent p {\
			color: [textColor];\
			font-family: [textFontFamily];\
			font-size: [textFontSize]px;\
			line-height: [textLineHeight];\
			letter-spacing: [textLetterSpacing];\
		}\
		#${ id } .bmTextContent a,\
		#${ id } .bmTextContent p a {\
			color: [linkColor];\
		}\
		@media only screen and (max-width: 480px) {\
			#${ id } .bmTextContent,\
			#${ id } .bmTextContent p {\
				font-size: [mobileTextFontSize]px !important;\
				line-height: [mobileTextLineHeight] !important;\
			}\
		}\
	`;

	STYLE = STYLE.replace( `[textColor]`, attributes.textColor || INITIAL_ATTRIBUTES.textColor );
	STYLE = STYLE.replace( `[textFontFamily]`, attributes.textFontFamily || INITIAL_ATTRIBUTES.textFontFamily );
	STYLE = STYLE.replace( `[textFontSize]`, attributes.textFontSize || INITIAL_ATTRIBUTES.textFontSize.toString() );
	STYLE = STYLE.replace( `[textLineHeight]`, attributes.textLineHeight || INITIAL_ATTRIBUTES.textLineHeight );
	STYLE = STYLE.replace( `[textLetterSpacing]`, attributes.textLetterSpacing || INITIAL_ATTRIBUTES.textLetterSpacing );
	STYLE = STYLE.replace( `[linkColor]`, attributes.linkColor || INITIAL_ATTRIBUTES.linkColor );
	STYLE = STYLE.replace( `[mobileTextFontSize]`, attributes.mobileTextFontSize || INITIAL_ATTRIBUTES.mobileTextFontSize.toString() );
	STYLE = STYLE.replace( `[mobileTextLineHeight]`, attributes.mobileTextLineHeight || INITIAL_ATTRIBUTES.mobileTextLineHeight );

	setAttributes( { [ styleAttribute ]: minifyCssString( STYLE ) } );
};

export default setStyleAttribute;
