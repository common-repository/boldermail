// Internal dependencies.
import './editor.scss';
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
	title: __( 'List', 'boldermail' ),
	description: __( 'Create a bulleted or numbered list.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'bullet list', 'boldermail' ),
		__( 'ordered list', 'boldermail' ),
		__( 'numbered list', 'boldermail' ),
	],
	example: {
		attributes: {
			values: __( '<li>Alice.</li><li>The White Rabbit.</li><li>The Cheshire Cat.</li><li>The Mad Hatter.</li><li>The Queen of Hearts.</li>', 'boldermail' ),
		},
	},
	parent,
	attributes: {
		ordered: {
			type: 'boolean',
			default: false,
		},
		values: {
			type: 'string',
			source: 'html',
			selector: 'ol,ul',
			multiline: 'li',
			__unstableMultilineWrapperTags: [ 'ol', 'ul' ],
			default: '',
		},
		type: {
			type: 'string',
		},
		start: {
			type: 'number',
		},
		reversed: {
			type: 'boolean',
		},
		...TEXT_ATTRIBUTES,
	},
	supports: {
		customClassName: false,
		html: false,
		reusable: true,
		__unstablePasteTextInline: true,
	},
	transforms,
	merge( attributes, attributesToMerge ) {
		const { values } = attributesToMerge;

		if ( ! values || values === '<li></li>' ) {
			return attributes;
		}

		return {
			...attributes,
			values: attributes.values + values,
		};
	},
	edit,
	save,
} );
