// Internal dependencies.
import './editor.scss';
import { name, category, icon, parent } from './block.json';
import attributes from '../social-shares/icon-attributes';
import variations from './variations';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block
export default registerBlockType( name, {
	title: __( 'Social Share Item', 'boldermail' ),
	description: __( '', 'boldermail' ),
	category,
	icon,
	attributes: {
		service: {
			type: 'string',
		},
		label: {
			type: 'string',
		},
		url: {
			type: 'string',
		},
		// the children blocks need to replicate the parent block's properties
		// because you can't access the parent block's properties in the save function
		// @see https://github.com/WordPress/gutenberg/issues/11776
		...attributes,
	},
	supports: {
		customClassName: false,
		reusable: false,
		html: false,
	},
	keywords: [
		__( 'social', 'boldermail' ),
		__( 'share', 'boldermail' ),
		__( 'link', 'boldermail' ),
	],
	parent,
	variations,
	edit,
	save,
} );
