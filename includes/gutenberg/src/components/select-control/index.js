// WordPress dependencies.
const { SelectControl } = wp.components;
const { select } = wp.data;

// Wrapper function.
const BoldermailSelectControl = ( {
	label,
	attribute,
	options,
	onChange = () => {},
	props,
} ) => {
	const { attributes, setAttributes } = props;

	const onChangeDefault = ( newSelect ) => {
		const newAttributes = { [ attribute ]: newSelect };
		setAttributes( newAttributes );
		onChange( newAttributes, props );
	};

	return (
		<SelectControl
			label={ label }
			options={ select( 'core/block-editor' ).getSettings()[ options ] }
			value={ attributes[ attribute ] }
			onChange={ onChangeDefault }
		/>
	);
};

export default BoldermailSelectControl;
