// Internal dependencies.
import './editor.scss';
import { name, category, icon, parent } from './block.json';
import attributes from './attributes';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block
export default registerBlockType( name, {
	title: __( 'Social Share', 'boldermail' ),
	description: __( 'Use this block to allow your contacts to share your content on their social profiles.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'social', 'boldermail' ),
		__( 'share', 'boldermail' ),
		__( 'links', 'boldermail' ),
	],
	example: {
		innerBlocks: [
			{
				name: 'boldermail/social-share',
				attributes: {
					service: 'facebook',
				},
			},
			{
				name: 'boldermail/social-share',
				attributes: {
					service: 'twitter',
				},
			},
			{
				name: 'boldermail/social-share',
				attributes: {
					service: 'pinterest',
				},
			},
		],
	},
	parent,
	attributes,
	supports: {
		customClassName: false,
		html: false,
		reusable: true,
	},
	edit,
	save,
} );
