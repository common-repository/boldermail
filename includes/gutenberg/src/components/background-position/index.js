// Internal dependencies.
import { SelectControl } from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;

// Wrapper function.
const BoldermailBackgroundPositionControl = ( {
	attribute,
	props,
	onChange = () => {}
} ) => {
	return (
		<SelectControl
			label={ __( 'Background Position', 'boldermail' ) }
			attribute={ attribute }
			options={ 'bmBackgroundPositions' }
			props={ props }
			onChange={ onChange }
		/>
	);
};

export default BoldermailBackgroundPositionControl;
