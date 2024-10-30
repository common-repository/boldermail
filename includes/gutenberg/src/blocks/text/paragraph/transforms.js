// Internal dependencies.
import { name } from './block.json';

// WordPress dependencies.
const { createBlock, getBlockAttributes } = wp.blocks;

// Transforms object.
const transforms = {
	from: [
		{
			type: 'block',
			blocks: [ 'core/paragraph' ],
			transform: ( { content } ) => {
				return createBlock( name, { content } );
			},
		},
		{
			type: 'raw',
			// Paragraph is a fallback and should be matched last.
			priority: 20,
			selector: 'p',
			schema: ( { phrasingContentSchema, isPaste } ) => ( {
				p: {
					children: phrasingContentSchema,
					attributes: isPaste ? [] : [ 'style' ],
				},
			} ),
			transform( node ) {
				const attributes = getBlockAttributes( name, node.outerHTML );
				const { textAlign } = node.style || {};

				if (
					textAlign === 'left' ||
					textAlign === 'center' ||
					textAlign === 'right' ||
					textAlign === 'justify'
				) {
					attributes.textAlign = textAlign;
				}

				return createBlock( name, attributes );
			},
		},
	],
};

export default transforms;
