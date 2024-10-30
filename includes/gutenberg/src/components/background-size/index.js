// Internal dependencies.
import { SelectControl } from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;

// Wrapper function.
const BoldermailBackgroundSizeControl = ( {
	attribute,
	props,
	onChange = () => {}
} ) => {
	return (
		<SelectControl
			label={ __( 'Background Size', 'boldermail' ) }
			attribute={ attribute }
			options={ 'bmBackgroundSizes' }
			props={ props }
			onChange={ onChange }
		/>
	);
};

export default BoldermailBackgroundSizeControl;
