// Internal dependencies.
import { SelectControl } from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;

// Wrapper function.
const BoldermailFontFamilyControl = ( {
	attribute,
	props,
	onChange = () => {},
} ) => {
	return (
		<SelectControl
			label={ __( 'Font Family', 'boldermail' ) }
			attribute={ attribute }
			options={ 'bmFontFamilies' }
			props={ props }
			onChange={ onChange }
		/>
	);
};

export default BoldermailFontFamilyControl;
