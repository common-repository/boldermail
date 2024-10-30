// WordPress dependencies.
const { RangeControl } = wp.components;

// Wrapper function.
const BoldermailRangeControl = ( {
	label,
	min = 0,
	max = 100,
	attribute,
	default: DEFAULT_VALUE = 0,
	props,
} ) => {
	const { attributes, setAttributes } = props;

	return (
		<RangeControl
			label={ label }
			value={ Number.isInteger( attributes[ attribute ] ) ? attributes[ attribute ] : 0 }
			{ ...( min ) ? { min } : null }
			{ ...( max ) ? { max } : null }
			onChange={ newNumber => {
				setAttributes( { [ attribute ]: isNaN( parseInt( newNumber ) ) ? undefined : Math.abs( parseInt( newNumber ) ) } );
			} }
			onBlur={ ( { target: { value: newNumber } } ) => {
				newNumber = isNaN( parseInt( newNumber ) ) ? DEFAULT_VALUE : Math.abs( parseInt( newNumber ) );
				newNumber = ( ! isNaN( max ) && newNumber > max ) ? max : newNumber;
				newNumber = ( ! isNaN( min ) && newNumber < min ) ? min : newNumber;
				setAttributes( { [ attribute ]: newNumber } );
			} }
			min={ 0 }
			max={ 100 }
		/>
	);
};

export default BoldermailRangeControl;
