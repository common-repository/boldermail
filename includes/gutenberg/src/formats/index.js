// Internal dependencies.
import { backgroundColor } from './background-color';

// WordPress dependencies.
const { domReady } = wp;
const { registerFormatType } = wp.richText;

// Register formats.
function registerBoldermailFormatTypes() {
	[
		backgroundColor,
	].forEach( ( { name, ...settings } ) => {
		if ( name ) {
			registerFormatType( name, settings );
		}
	} );
}

domReady( registerBoldermailFormatTypes );
