// WordPress dependencies.
const { ColorPaletteControl } = wp.blockEditor;
const { select } = wp.data;

/**
 * Wrapper for ColorPaletteControl component.
 *
 * @since   2.0.0
 * @param   {string}    label       Label.
 * @param   {string}    attribute   Attribute key.
 * @param   {string}    default     Default value if user clear the color palette.
 *                                  A value of `undefined` or `null` forces the block validator
 *                                  to use the default value provided in the `attributes` object
 *                                  that we used when we registered the block. This causes the block
 *                                  to fail validation because when the user clears the palette,
 *                                  a color property is not added to the HTML markup, nor stored
 *                                  as a attribute for the block. Since the block has no color
 *                                  attribute stored, the validator grabs the default value, and
 *                                  sees a mismatch. We could remove the default value from the
 *                                  attributes object, but then we have no way to provide an initial value
 *                                  for our blocks (at least until Block Patterns is merged into core).
 *                                  An empty string is stored by the serializer as an attribute
 *                                  for the block, so the validator does not have to use the default
 *                                  value and the block passes validation. ReactJS does not add
 *                                  the style property if the property is an empty string.
 * @param   {Object}    props       Block properties.
 * @return  {Component}             The color palette.
 */
const BoldermailColorPaletteControl = ( {
	label,
	attribute,
	default: DEFAULT_VALUE = '',
	props,
	onChange = () => {},
} ) => {
	const { attributes, setAttributes } = props;

	const onChangeDefault = ( newColor ) => {
		const newAttributes = { [ attribute ]: newColor || DEFAULT_VALUE };
		setAttributes( newAttributes );
		onChange( newAttributes, props );
	};

	return (
		<ColorPaletteControl
			label={ label }
			colors={ select( 'core/block-editor' ).getSettings().bmColors }
			disableCustomColors={ false }
			value={ attributes[ attribute ] }
			onChange={ onChangeDefault }
		/>
	);
};

export default BoldermailColorPaletteControl;
