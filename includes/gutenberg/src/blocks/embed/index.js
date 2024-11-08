// Internal dependencies.
import { name, category, parent, attributes, supports } from './block.json';
import variations from './variations';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Social Block', 'boldermail' ),
	description: __(
		'Add a block that displays content pulled from other sites, like Twitter, Facebook, or Instagram.',
		'boldermail'
	),
	category,
	icon: (
		<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
			<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm.5 16c0 .3-.2.5-.5.5H5c-.3 0-.5-.2-.5-.5V9.8l4.7-5.3H19c.3 0 .5.2.5.5v14zm-6-9.5L16 12l-2.5 2.8 1.1 1L18 12l-3.5-3.5-1 1zm-3 0l-1-1L6 12l3.5 3.8 1.1-1L8 12l2.5-2.5z" />
		</svg>
	),
	parent,
	attributes,
	supports,
	variations,
	edit,
	save,
} );
