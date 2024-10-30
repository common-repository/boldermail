// Internal dependencies.
import './editor.scss';
import { name, category, icon, parent } from './block.json';
import attributes from './attributes';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Template Part', 'boldermail' ),
	description: __( 'Preheader, header, body, or footer component.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'template', 'boldermail' ),
		__( 'layout', 'boldermail' ),
		__( 'builder', 'boldermail' ),
	],
	supports: {
		multiple: true,
		customClassName: false,
		html: false,
		reusable: false,
	},
	parent,
	attributes,
	edit,
	save,
} );
