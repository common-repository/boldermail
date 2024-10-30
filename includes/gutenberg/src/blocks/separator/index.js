// Internal dependencies.
import { name, category, example, supports, parent } from './block.json';
import icon from './icon';
import edit from './edit';
import save from './save';
import attributes from './attributes';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Separator', 'boldermail' ),
	description: __( 'Create a break between ideas or sections with a horizontal separator. The separator can either be a line or empty whitespace.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'separator', 'boldermail' ),
		__( 'line', 'boldermail' ),
		__( 'spacer', 'boldermail' )
	],
	example,
	attributes,
	supports,
	parent,
	edit,
	save,
} );
