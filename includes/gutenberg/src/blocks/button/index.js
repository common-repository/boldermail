// Internal dependencies.
import './editor.scss';
import { name, category, parent } from './block.json';
import icon from './icon';
import attributes from './attributes';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block
export default registerBlockType( name, {
	title: __( 'Button', 'boldermail' ),
	description: __( 'Prompt your subscribers to take action with a button-style link.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'button', 'boldermail' ),
		__( 'call-to-action', 'boldermail' ),
		__( 'action', 'boldermail' ),
	],
	example: {
		attributes: {
			text: __( 'Learn More', 'boldermail' ),
		},
	},
	supports: {
		customClassName: false,
		html: false,
		reusable: true,
	},
	attributes,
	parent,
	edit,
	save,
} );
