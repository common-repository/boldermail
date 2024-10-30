// Internal dependencies.
import CountdownWrapper from './wrapper';

// WordPress dependencies.
const { __, _x } = wp.i18n;
const { date } = wp.date;
const { BlockControls, BlockIcon } = wp.blockEditor;
const { Button, DateTimePicker, Placeholder, ToolbarButton, ToolbarGroup } = wp.components;
const { useState, Fragment } = wp.element;

// Edit function.
export default function CountdownEdit( props ) {
	const {
		attributes: { timestamp },
		setAttributes,
	} = props;

	const [ isEditingTimestamp, setIsEditingTimestamp ] = useState( ! timestamp );

	const currentDate = timestamp ? date( 'Y-m-d H:i', new Date( timestamp * 1000 ) ) : date( 'Y-m-d H:i' ); // JS Date is in milliseconds.

	return (
		<Fragment>
			{ timestamp && ! isEditingTimestamp ? (
				<Fragment>
					<BlockControls>
						<ToolbarGroup>
							<ToolbarButton
								className="components-toolbar__control"
								label={ __( 'Edit Timestamp', 'boldermail' ) }
								icon={
									<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false">
										<path d="M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z" />
									</svg>
								}
								onClick={ () => setIsEditingTimestamp( true ) }
							/>
						</ToolbarGroup>
					</BlockControls>
					<CountdownWrapper { ...props } />
				</Fragment>
			) : (
				<Placeholder
					icon={ <BlockIcon icon={ 'clock' } showColors /> }
					label={ __( 'Expiration Date', 'boldermail' ) }
					className="wp-block-boldermail-embed"
					instructions={ __( 'Set when you want this timer to end.', 'boldermail' ) }
				>
					<form
						onSubmit={ ( event ) => {
							if ( event ) {
								event.preventDefault();
							}

							setIsEditingTimestamp( false );
						} }
					>
						<DateTimePicker
							currentDate={ currentDate }
							onChange={ ( newTimestamp ) => {
								setAttributes( { timestamp: date( 'U', newTimestamp ) } );
							} }
							is12Hour={ true }
						/>
						<Button isPrimary type="submit">
							{ _x( 'Submit', 'button label', 'boldermail' ) }
						</Button>
					</form>
				</Placeholder>
			) }
		</Fragment>
	);
}
