// WordPress dependencies.
const { FontSizePicker } = wp.components;
const { select } = wp.data;

// Wrapper function.
const BoldermailFontSizePicker = ( {
	attribute,
	default: DEFAULT_VALUE = '',
	props,
	onChange = () => {},
} ) => {
	const { attributes, setAttributes } = props;

	const onChangeDefault = ( newFontSize ) => {
		const newAttributes = { [ attribute ]: isNaN( parseInt( newFontSize ) ) ? DEFAULT_VALUE : parseInt( newFontSize ) };
		setAttributes( newAttributes );
		onChange( newAttributes, props );
	};

	return (
		<FontSizePicker
			fontSizes={ select( 'core/block-editor' ).getSettings().bmFontSizes }
			value={ Number.isInteger( attributes[ attribute ] ) ? attributes[ attribute ] : '' }
			onChange={ onChangeDefault }
		/>
	);
};

export default BoldermailFontSizePicker;
