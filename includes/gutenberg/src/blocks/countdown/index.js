// Internal dependencies.
import './editor.scss';
import { name, category, parent, attributes, supports } from './block.json';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Countdown', 'boldermail' ),
	description: __( 'Display a countdown timer in your newsletter. The timer uses your time zone to calculate the expiry date.', 'boldermail' ),
	category,
	icon: 'clock',
	parent,
	attributes,
	supports,
	edit,
	save,
} );
