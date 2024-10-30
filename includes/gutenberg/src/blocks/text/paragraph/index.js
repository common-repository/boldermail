// Internal dependencies.
import { name } from './block.json';
import { category, parent } from '../block.json';
import TEXT_ATTRIBUTES from '../text-attributes';
import icon from './icon';
import transforms from './transforms';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Paragraph', 'boldermail' ),
	description: __( 'Start with the building block of all narrative.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'paragraph', 'boldermail' ),
		__( 'text', 'boldermail' ),
	],
	example: {
		attributes: {
			content: __( 'In a village of La Mancha, the name of which I have no desire to call to mind, there lived not long since one of those gentlemen that keep a lance in the lance-rack, an old buckler, a lean hack, and a greyhound for coursing.', 'boldermail' ),
			customFontSize: 28,
		},
	},
	parent,
	attributes: {
		content: {
			type: 'string',
			source: 'html',
			selector: 'p',
			default: '',
		},
		...TEXT_ATTRIBUTES,
	},
	supports: {
		customClassName: false,
		html: false,
		reusable: true,
		__unstablePasteTextInline: false,
	},
	transforms,
	merge( attributes, attributesToMerge ) {
		return {
			content:
				( attributes.content || '' ) +
				( attributesToMerge.content || '' ),
		};
	},
	edit,
	save,
} );
