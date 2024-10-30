// Internal dependencies.
import { SelectControl } from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;

// Wrapper function.
const BoldermailLineHeightControl = ( {
	attribute,
	props,
	onChange = () => {},
} ) => {
	return (
		<SelectControl
			label={ __( 'Line Height', 'boldermail' ) }
			attribute={ attribute }
			options={ 'bmLineHeights' }
			props={ props }
			onChange={ onChange }
		/>
	);
};

export default BoldermailLineHeightControl;
