// Internal dependencies.
import './editor.scss';
import { name, category, parent } from './block.json';
import icon from './icon';
import attributes from './attributes';
import transforms from './transforms';
import edit from './edit';
import save from './save';

// WordPress dependencies.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Register block.
export default registerBlockType( name, {
	title: __( 'Image', 'boldermail' ),
	description: __( 'Insert an image to make a visual statement.', 'boldermail' ),
	category,
	icon,
	keywords: [
		'img', // "img" is not translated as it is intended to reflect the HTML <img> tag.
		__( 'image', 'boldermail' ),
		__( 'photo', 'boldermail' ),
	],
	example: {
		attributes: {
			sizeSlug: 'boldermail',
			url: 'https://s.w.org/images/core/5.3/MtBlanc1.jpg',
			// translators: Caption accompanying an image of the Mont Blanc, which serves as an example for the Image block.
			caption: __( 'Mont Blanc appears—still, snowy, and serene.', 'boldermail' ),
		},
	},
	__experimentalLabel( newAttributes, { context } ) {
		if ( context === 'accessibility' ) {
			const { caption, alt, url } = newAttributes;

			if ( ! url ) {
				return __( 'Empty', 'boldermail' );
			}

			if ( ! alt ) {
				return caption || '';
			}

			// This is intended to be read by a screen reader.
			// A period simply means a pause, no need to translate it.
			return alt + ( caption ? '. ' + caption : '' );
		}
	},
	attributes,
	supports: {
		customClassName: false,
		html: false,
		reusable: true,
	},
	transforms,
	getEditWrapperProps( newAttributes ) {
		const { align, width } = newAttributes;
		if (
			'left' === align ||
			'center' === align ||
			'right' === align ||
			'wide' === align ||
			'full' === align
		) {
			return { 'data-align': align, 'data-resized': !! width };
		}
	},
	parent,
	edit,
	save,
} );
