// Internal dependencies.
import { SelectControl } from '../../components';

// Wrapper function.
const BoldermailUnitControl = ( {
	label,
	options = 'bmUnitValues',
	attribute,
	props,
	onChange = () => {},
} ) => {
	return (
		<SelectControl
			label={ label }
			attribute={ attribute }
			options={ options }
			props={ props }
			onChange={ onChange }
		/>
	);
};

export default BoldermailUnitControl;
