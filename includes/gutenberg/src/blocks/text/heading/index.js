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
	title: __( 'Heading', 'boldermail' ),
	description: __( 'Introduce new sections and organize your email content to help your subscribers understand the structure of your template.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'heading', 'boldermail' ),
		__( 'text', 'boldermail' ),
		__( 'boldermail', 'boldermail' ),
	],
	example: {
		attributes: {
			content: __( 'Code is Poetry', 'boldermail' ),
			level: 2,
		},
	},
	parent,
	attributes: {
		content: {
			type: 'string',
			source: 'html',
			selector: 'h1,h2,h3,h4,h5,h6',
			default: '',
		},
		level: {
			type: 'number',
			default: 2,
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
		return {
			content:
				( attributes.content || '' ) +
				( attributesToMerge.content || '' ),
		};
	},
	edit,
	save,
} );
