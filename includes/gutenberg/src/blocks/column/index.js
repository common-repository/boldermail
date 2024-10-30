// Internal dependencies.
import { name, category, parent } from './block.json';
import icon from './icon';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Column', 'boldermail' ),
	description: __( 'A single column within a columns block.', 'boldermail' ),
	category,
	icon,
	parent,
	attributes: {
		width: {
			type: 'number',
		},
	},
	supports: {
		customClassName: false,
		inserter: false,
		reusable: false,
		html: false,
	},
	edit,
	save,
} );
