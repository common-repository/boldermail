// Internal dependencies.
import './editor.scss';
import { name, category, parent } from './block.json';
import icon from './icon';
import variations from './variations';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block
export default registerBlockType( name, {
	title: __( 'Columns', 'boldermail' ),
	description: __( 'Add a block that displays content in multiple columns, then add whatever content blocks you\'d like.', 'boldermail' ),
	category,
	icon,
	parent,
	supports: {
		customClassName: false,
		html: false,
		reusable: true,
	},
	variations,
	example: {
		innerBlocks: [
			{
				name: 'boldermail/column',
				attributes: {
					width: 200,
				},
				innerBlocks: [
					{
						name: 'boldermail/heading',
						attributes: {
							content: __( 'How to Learn Calligraphy', 'boldermail' ),
							level: 2,
						},
					},
					{
						name: 'boldermail/paragraph',
						attributes: {
							/* translators: example text. */
							content: __( 'Today, I’ll walk you through how to learn calligraphy in two months.', 'boldermail' ),
						},
					},
					{
						name: 'boldermail/paragraph',
						attributes: {
							/* translators: example text. */
							content: __( 'Start this weekend, and you’ll be proficient enough to start sending out gorgeous holiday envelopes in early December!', 'boldermail' ),
						},
					},
				],
			},
			{
				name: 'boldermail/column',
				attributes: {
					width: 200,
				},
				innerBlocks: [
					{
						name: 'boldermail/paragraph',
						attributes: {
							/* translators: example text. */
							content: __( 'Calligraphy is a relaxing and beautiful art form that should be accessible to anyone who wants to learn it!', 'boldermail' ),
						},
					},
					{
						name: 'boldermail/paragraph',
						attributes: {
							/* translators: example text. */
							content: __( 'And, you know what? I truly think that you can build a solid calligraphy foundation in two months.', 'boldermail' ),
						},
					},
					{
						name: 'boldermail/paragraph',
						attributes: {
							/* translators: example text. */
							content: __( 'It just takes a bit of self-discipline and the will to stifle any negativity that you feel about your work.', 'boldermail' ),
						},
					},
				],
			},
		],
	},
	edit,
	save,
} );
