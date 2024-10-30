// Internal dependencies.
import { name, category, icon, example } from './block.json';
import attributes from './attributes';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block
export default registerBlockType( name, {
	title: __( 'Generic Template', 'boldermail' ),
	description: __( 'Starting block for Boldermail templates.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'template', 'boldermail' ),
		__( 'layout', 'boldermail' ),
		__( 'builder', 'boldermail' ),
	],
	example,
	attributes,
	supports: {
		multiple: false,
		customClassName: false,
		html: false,
		reusable: false,
	},
	edit,
	save,
} );
