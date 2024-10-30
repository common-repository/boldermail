// Internal dependencies.
import { ColorPaletteControl, UnitControl, SelectControl } from '../../components';

// WordPress dependencies.
const { __ } = wp.i18n;
const { Fragment } = wp.element;

// Wrapper function.
const BoldermailBorderControl = ( {
	attribute,
	default: DEFAULT_VALUE = { borderStyle: undefined, borderWidth: undefined, borderColor: '' },
	props,
} ) => {
	const { borderStyle, borderWidth, borderColor } = attribute;
	const { attributes } = props;

	return (
		<Fragment>
			<SelectControl
				label={ __( 'Border Style', 'boldermail' ) }
				attribute={ borderStyle }
				default={ DEFAULT_VALUE.borderStyle }
				options={ 'bmBorderStyles' }
				props={ props }
			/>
			{ attributes[ borderStyle ] !== 'none' && (
				<UnitControl
					label={ __( 'Border Width', 'boldermail' ) }
					attribute={ borderWidth }
					default={ DEFAULT_VALUE.borderWidth }
					props={ props }
				/>
			) }
			{ attributes[ borderStyle ] !== 'none' && (
				<ColorPaletteControl
					label={ __( 'Border Color', 'boldermail' ) }
					attribute={ borderColor }
					default={ DEFAULT_VALUE.borderColor }
					props={ props }
				/>
			) }
		</Fragment>
	);
};

export default BoldermailBorderControl;
