// Internal dependencies.
import { name } from './block.json';

// WordPress dependencies.
const { createBlock, getBlockAttributes } = wp.blocks;
const {
	__UNSTABLE_LINE_SEPARATOR,
	create,
	join,
	replace,
	split,
	toHTMLString,
} = wp.richText;

function getListContentSchema( { phrasingContentSchema } ) {
	const listContentSchema = {
		...phrasingContentSchema,
		ul: {},
		ol: { attributes: [ 'type', 'start', 'reversed' ] },
	};

	// Recursion is needed.
	// Possible: ul > li > ul.
	// Impossible: ul > ul.
	[ 'ul', 'ol' ].forEach( ( tag ) => {
		listContentSchema[ tag ].children = {
			li: {
				children: listContentSchema,
			},
		};
	} );

	return listContentSchema;
}

const transforms = {
	from: [
		{
			type: 'block',
			blocks: [ 'core/list' ],
			transform: ( { ordered, values, type, start, reversed } ) => {
				return createBlock( name, {
					ordered,
					values,
					type,
					start,
					reversed,
				} );
			},
		},
		{
			type: 'block',
			isMultiBlock: true,
			blocks: [ 'boldermai/paragraph', 'core/paragraph' ],
			transform: ( blockAttributes ) => {
				return createBlock( name, {
					values: toHTMLString( {
						value: join(
							blockAttributes.map( ( { content } ) => {
								const value = create( { html: content } );

								if ( blockAttributes.length > 1 ) {
									return value;
								}

								// When converting only one block, transform
								// every line to a list item.
								return replace(
									value,
									/\n/g,
									__UNSTABLE_LINE_SEPARATOR
								);
							} ),
							__UNSTABLE_LINE_SEPARATOR
						),
						multilineTag: 'li',
					} ),
				} );
			},
		},
		{
			type: 'raw',
			selector: 'ol,ul',
			schema: ( args ) => ( {
				ol: getListContentSchema( args ).ol,
				ul: getListContentSchema( args ).ul,
			} ),
			transform( node ) {
				const attributes = {
					ordered: node.nodeName === 'OL',
				};

				if ( attributes.ordered ) {
					const type = node.getAttribute( 'type' );

					if ( type ) {
						attributes.type = type;
					}

					if ( node.getAttribute( 'reversed' ) !== null ) {
						attributes.reversed = true;
					}

					const start = parseInt( node.getAttribute( 'start' ), 10 );

					if (
						! isNaN( start ) &&
						// start=1 only makes sense if the list is reversed.
						( start !== 1 || attributes.reversed )
					) {
						attributes.start = start;
					}
				}

				return createBlock( name, {
					...getBlockAttributes( name, node.outerHTML ),
					...attributes,
				} );
			},
		},
		...[ '*', '-' ].map( ( prefix ) => ( {
			type: 'prefix',
			prefix,
			transform( content ) {
				return createBlock( name, {
					values: `<li>${ content }</li>`,
				} );
			},
		} ) ),
		...[ '1.', '1)' ].map( ( prefix ) => ( {
			type: 'prefix',
			prefix,
			transform( content ) {
				return createBlock( name, {
					ordered: true,
					values: `<li>${ content }</li>`,
				} );
			},
		} ) ),
	],
	to: [
		{
			type: 'block',
			blocks: [ 'boldermail/paragraph' ],
			transform: ( { values } ) =>
				split(
					create( {
						html: values,
						multilineTag: 'li',
						multilineWrapperTags: [ 'ul', 'ol' ],
					} ),
					__UNSTABLE_LINE_SEPARATOR
				).map( ( piece ) =>
					createBlock( 'boldermail/paragraph', {
						content: toHTMLString( { value: piece } ),
					} )
				),
		},
	],
};

export default transforms;
