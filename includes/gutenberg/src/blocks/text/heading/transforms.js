// Internal dependencies.
import { name } from './block.json';

// WordPress dependencies.
const { createBlock, getBlockAttributes } = wp.blocks;

// Transforms object.
const transforms = {
	from: [
		{
			type: 'block',
			blocks: [ 'core/heading' ],
			transform: ( { level, content } ) => {
				return createBlock( name, {
					level,
					content,
				} );
			},
		},
		{
			type: 'block',
			blocks: [ 'boldermail/paragraph', 'core/paragraph' ],
			transform: ( { content, fontFamily, color } ) => {
				return createBlock( name, {
					content,
					fontFamily,
					color,
				} );
			},
		},
		{
			type: 'raw',
			selector: 'h1,h2,h3,h4,h5,h6',
			schema: ( { phrasingContentSchema, isPaste } ) => {
				const schema = {
					children: phrasingContentSchema,
					attributes: isPaste ? [] : [ 'style' ],
				};
				return {
					h1: schema,
					h2: schema,
					h3: schema,
					h4: schema,
					h5: schema,
					h6: schema,
				};
			},
			transform( node ) {
				const attributes = getBlockAttributes( name, node.outerHTML );
				const { textAlign } = node.style || {};

				attributes.level = Number( node.nodeName.substr( 1 ) );

				if (
					textAlign === 'left' ||
					textAlign === 'center' ||
					textAlign === 'right' ||
					textAlign === 'justify'
				) {
					attributes.align = textAlign;
				}

				return createBlock( name, attributes );
			},
		},
		...[ 2, 3, 4, 5, 6 ].map( ( level ) => ( {
			type: 'prefix',
			prefix: Array( level + 1 ).join( '#' ),
			transform( content ) {
				return createBlock( name, {
					level,
					content,
				} );
			},
		} ) ),
	],
	to: [
		{
			type: 'block',
			blocks: [ 'boldermail/paragraph' ],
			transform: ( { content, fontFamily, color } ) => {
				return createBlock( 'boldermail/paragraph', {
					content,
					fontFamily,
					color,
				} );
			},
		},
	],
};

export default transforms;
