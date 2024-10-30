// Internal dependencies.
import { getIconBySite, getInstructionsBySite, getTitleBySite } from './utils';

// WordPress dependencies.
const { __, _x, sprintf } = wp.i18n;
const { BlockControls, BlockIcon } = wp.blockEditor;
const { Button, Disabled, Placeholder, ToolbarButton, ToolbarGroup } = wp.components;
const { useState, Fragment } = wp.element;
const { prependHTTP } = wp.url;
const ServerSideRender = wp.serverSideRender;

// Edit function.
export default function Edit( props ) {
	const {
		attributes,
		attributes: { service, url },
		setAttributes,
	} = props;

	// translators: %s: type of embed e.g: "Facebook", "Twitter", etc. "Embed" is used when no specific type exists.
	const label = sprintf( __( '%s URL', 'boldermail' ), getTitleBySite( service ) || __( 'Embed', 'boldermail' ) );

	const [ isEditingURL, setIsEditingURL ] = useState( ! url );

	return (
		<Fragment>
			{ url && ! isEditingURL ? (
				<Fragment>
					<BlockControls>
						<ToolbarGroup>
							<ToolbarButton
								className="components-toolbar__control"
								label={ __( 'Edit URL', 'boldermail' ) }
								icon={
									<svg
										width="24"
										height="24"
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 24 24"
										role="img"
										aria-hidden="true"
										focusable="false"
									>
										<path d="M20.1 5.1L16.9 2 6.2 12.7l-1.3 4.4 4.5-1.3L20.1 5.1zM4 20.8h8v-1.5H4v1.5z" />
									</svg>
								}
								onClick={ () => setIsEditingURL( true ) }
							/>
						</ToolbarGroup>
					</BlockControls>
					<Disabled>
						<ServerSideRender block="boldermail/embed" attributes={ attributes } />
					</Disabled>
				</Fragment>
			) : (
				<Placeholder
					icon={ <BlockIcon icon={ getIconBySite( service ) } showColors /> }
					label={ label }
					className="wp-block-boldermail-embed"
					instructions={ getInstructionsBySite( service ) }
				>
					<form
						onSubmit={ ( event ) => {
							if ( event ) {
								event.preventDefault();
							}

							setIsEditingURL( false );
							setAttributes( { url } );
						} }
					>
						<input
							type="url"
							value={ url || '' }
							className="components-placeholder__input"
							aria-label={ label }
							placeholder={ __( 'Enter URL to embed here…', 'boldermail' ) }
							onChange={ ( event ) => {
								setAttributes( { url: prependHTTP( event.target.value ) } );
							} }
						/>
						<Button isPrimary type="submit">
							{ _x( 'Embed', 'button label', 'boldermail' ) }
						</Button>
					</form>
				</Placeholder>
			) }
		</Fragment>
	);
}
