// Internal dependencies.
import { SelectControl } from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;

// Wrapper function.
const BoldermailBackgroundRepeatControl = ( {
	attribute,
	props,
	onChange = () => {}
} ) => {
	return (
		<SelectControl
			label={ __( 'Background Repeat', 'boldermail' ) }
			attribute={ attribute }
			options={ 'bmBackgroundRepeat' }
			props={ props }
			onChange={ onChange }
		/>
	);
};

export default BoldermailBackgroundRepeatControl;
