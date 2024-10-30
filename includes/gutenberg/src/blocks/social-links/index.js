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
	title: __( 'Social Follow', 'boldermail' ),
	description: __( 'Use this block to share links to your social profiles.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'social', 'boldermail' ),
		__( 'follow', 'boldermail' ),
		__( 'links', 'boldermail' ),
	],
	example: {
		innerBlocks: [
			{
				name: 'boldermail/social-link',
				attributes: {
					service: 'facebook',
					iconSize: 96,
				},
			},
			{
				name: 'boldermail/social-link',
				attributes: {
					service: 'twitter',
					iconSize: 96,
				},
			},
			{
				name: 'boldermail/social-link',
				attributes: {
					service: 'instagram',
					iconSize: 96,
				},
			},
			{
				name: 'boldermail/social-link',
				attributes: {
					service: 'pinterest',
					iconSize: 96,
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
