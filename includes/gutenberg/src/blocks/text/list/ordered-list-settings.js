// WordPress dependencies.
const { __ } = wp.i18n;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, TextControl } = wp.components;

// Settings.
const OrderedListSettings = ( { setAttributes, start } ) => (
	<InspectorControls>
		<PanelBody title={ __( 'Ordered list settings', 'boldermail' ) }>
			<TextControl
				label={ __( 'Start value', 'boldermail' ) }
				type="number"
				onChange={ ( value ) => {
					const int = parseInt( value, 10 );

					setAttributes( {
						// It should be possible to unset the value,
						// e.g. with an empty string.
						start: isNaN( int ) ? undefined : int,
					} );
				} }
				value={ Number.isInteger( start ) ? start.toString( 10 ) : '' }
				step="1"
			/>
		</PanelBody>
	</InspectorControls>
);

export default OrderedListSettings;
