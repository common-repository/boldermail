// Internal dependencies.
import { name, category, supports, parent } from './block.json';
import icon from './icon';
import attributes from './attributes';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Boxed Text', 'boldermail' ),
	description: __( 'This is a boxed text block. You can use it to add text or media to your template.', 'boldermail' ),
	category,
	icon,
	keywords: [
		__( 'boxed', 'boldermail' ),
		__( 'text', 'boldermail' ),
		__( 'section', 'boldermail' )
	],
	example: {
		innerBlocks: [
			{
				name: 'boldermail/paragraph',
				attributes: {
					content: __(
						'In a village of La Mancha, the name of which I have no desire to call to mind, there lived not long since one of those gentlemen that keep a lance in the lance-rack, an old buckler, a lean hack, and a greyhound for coursing.',
						'boldermail'
					),
					customFontSize: 28,
					color: '#fff',
				},
			},
		],
	},
	supports,
	parent,
	attributes,
	edit,
	save,
} );
