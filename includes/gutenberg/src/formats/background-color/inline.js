// @see wordpress-develop/node_modules/@wordpress/format-library/src/text-color/inline.js

// WordPress dependencies.
const { useCallback, useMemo } = wp.element;
const { useSelect } = wp.data;
const { withSpokenMessages } = wp.components;
const { getRectangleFromRange } = wp.dom;
const { applyFormat, removeFormat, getActiveFormat } = wp.richText;
const {
	ColorPalette,
	URLPopover,
	getColorClassName,
	getColorObjectByColorValue,
	getColorObjectByAttributeValues,
} = wp.blockEditor;

export function getActiveColor( formatName, formatValue, colors ) {
	const activeBackgroundColorFormat = getActiveFormat( formatValue, formatName );
	if ( ! activeBackgroundColorFormat ) {
		return;
	}
	const styleBackgroundColor = activeBackgroundColorFormat.attributes.style;
	if ( styleBackgroundColor ) {
		return styleBackgroundColor.replace( new RegExp( `^background-color:\\s*` ), '' );
	}
	const currentClass = activeBackgroundColorFormat.attributes.class;
	if ( currentClass ) {
		const colorSlug = currentClass.replace( /.*has-(.*?)-background-color.*/, '$1' );
		return getColorObjectByAttributeValues( colors, colorSlug ).color;
	}
}

const ColorPopoverAtLink = ( { addingColor, ...props } ) => {
	// There is no way to open a text formatter popover when another one is mounted.
	// The first popover will always be dismounted when a click outside happens, so we can store the
	// anchor Rect during the lifetime of the component.
	const anchorRect = useMemo( () => {
		const selection = window.getSelection();
		const range = selection.rangeCount > 0 ? selection.getRangeAt( 0 ) : null;
		if ( ! range ) {
			return;
		}

		if ( addingColor ) {
			return getRectangleFromRange( range );
		}

		let element = range.startContainer;

		// If the caret is right before the element, select the next element.
		element = element.nextElementSibling || element;

		while ( element.nodeType !== window.Node.ELEMENT_NODE ) {
			element = element.parentNode;
		}

		const closest = element.closest( 'span' );
		if ( closest ) {
			return closest.getBoundingClientRect();
		}
	}, [] );

	if ( ! anchorRect ) {
		return null;
	}

	return <URLPopover anchorRect={ anchorRect } { ...props } />;
};

const ColorPicker = ( { name, value, onChange } ) => {
	const colors = useSelect( ( select ) => {
		const { getSettings } = select( 'core/block-editor' );
		const settings = getSettings();
		return settings && settings.colors ? settings.colors : [];
	} );
	const onColorChange = useCallback(
		( color ) => {
			if ( color ) {
				const colorObject = getColorObjectByColorValue( colors, color );
				onChange(
					applyFormat( value, {
						type: name,
						attributes: colorObject
							? {
									class: getColorClassName(
										'background-color',
										colorObject.slug
									),
							  }
							: {
									style: `background-color:${ color }`,
							  },
					} )
				);
			} else {
				onChange( removeFormat( value, name ) );
			}
		},
		[ colors, onChange ]
	);
	const activeColor = useMemo( () => getActiveColor( name, value, colors ), [
		name,
		value,
		colors,
	] );

	return <ColorPalette value={ activeColor } onChange={ onColorChange } />;
};

const InlineColorUI = ( {
	name,
	value,
	onChange,
	onClose,
	isActive,
	addingColor,
} ) => {
	return (
		<ColorPopoverAtLink
			value={ value }
			isActive={ isActive }
			addingColor={ addingColor }
			onClose={ onClose }
			className="components-inline-background-color-popover"
		>
			<ColorPicker name={ name } value={ value } onChange={ onChange } />
		</ColorPopoverAtLink>
	);
};

export default withSpokenMessages( InlineColorUI );
