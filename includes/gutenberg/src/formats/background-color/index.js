// @see wordpress-develop/node_modules/@wordpress/format-library/src/text-color/index.js

// Internal dependencies.
import './editor.scss';

// WordPress dependencies.
const { __ } = wp.i18n;
const { useSelect } = wp.data;
const { useCallback, useMemo, useState, Fragment } = wp.element;
const { RichTextToolbarButton } = wp.blockEditor;
const { Dashicon } = wp.components;
const { removeFormat } = wp.richText;

// Internal dependencies.
import { default as InlineColorUI, getActiveColor } from './inline';

const name = 'boldermail/background-color';
const title = __( "Background Color", 'boldermail' );

const EMPTY_ARRAY = [];

function BackgroundColorEdit( { value, onChange, isActive, activeAttributes } ) {
	const { colors, disableCustomColors } = useSelect( ( select ) => {
		const blockEditorSelect = select( 'core/block-editor' );
		let settings;
		if ( blockEditorSelect && blockEditorSelect.getSettings ) {
			settings = blockEditorSelect.getSettings();
		} else {
			settings = {};
		}
		return {
			colors: settings && settings.colors ? settings.colors : EMPTY_ARRAY,
			disableCustomColors: settings.disableCustomColors,
		};
	} );
	const [ isAddingBackgroundColor, setIsAddingBackgroundColor ] = useState( false );
	const enableIsAddingBackgroundColor = useCallback( () => setIsAddingBackgroundColor( true ), [
		setIsAddingBackgroundColor,
	] );
	const disableIsAddingBackgroundColor = useCallback( () => setIsAddingBackgroundColor( false ), [
		setIsAddingBackgroundColor,
	] );
	const activeBackgroundColor = getActiveColor( name, value, colors );
	const hasColorsToChoose =
		( Array.isArray( colors ) && colors.length > 0 ) ||
		( colors !== null && typeof colors === 'object' && Object.keys( colors ).length > 0 ) ||
		disableCustomColors !== true;
	if ( ! hasColorsToChoose && ! isActive ) {
		return null;
	}

	return (
		<Fragment>
			{
			/**
			 * The `key` and `name attributes are used in
			 * `wordpress-develop/node-modules/@wordpress/block-editor/src/components/rich-text/format-toolbar/index.js`
			 * to display the format outside the dropdown menu if the format is
			 * active on the text selection (i.e. the text selected has a defined
			 * text color or background color. The issue is that the `format-toolbar`
			 * will only display 4 hardcoded formats: bold, italic, link, and text color.
			 * There is no way yet to expand the toolbar to also include other formats
			 * outside the dropdown menu. So in order to show the `background-color`
			 * format outside the dropdown menu, we set the `key` and `name` attributes
			 * to `text-color`.
			 *
			 * @see   https://github.com/WordPress/gutenberg/issues/22663
			 */
			}
			<RichTextToolbarButton
				key={ isActive ? 'text-color' : 'text-color-not-active' }  // hack so buttons displays in toolbar -- should be background-color
				className="format-library-background-color-button"
				name={ isActive ? 'text-color' : undefined }  // hack so buttons displays in toolbar -- should be background-color
				icon={
					<Fragment>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="20" viewBox="2 2 20 20">
							<path d="M0 0h24v24H0V0z" fill="none"/>
							<path xmlns="http://www.w3.org/2000/svg" d="M9.93 13.5h4.14L12 7.98zM20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-4.05 16.5l-1.14-3H9.17l-1.12 3H5.96l5.11-13h1.86l5.11 13h-2.09z" { ...( isActive ? { fill: `${activeBackgroundColor}`} : {} ) } />
						</svg>
					</Fragment>
				}
				title={ title }
				// If has no colors to choose but a color is active remove the color onClick
				onClick={
					hasColorsToChoose
						? enableIsAddingBackgroundColor
						: () => onChange( removeFormat( value, name ) )
				}
			/>
			{ isAddingBackgroundColor && (
				<InlineColorUI
					name={ name }
					addingColor={ isAddingBackgroundColor }
					onClose={ disableIsAddingBackgroundColor }
					isActive={ isActive }
					activeAttributes={ activeAttributes }
					value={ value }
					onChange={ onChange }
				/>
			) }
		</Fragment>
	);
}

export const backgroundColor = {
	name,
	title,
	tagName: 'span',
	className: 'has-inline-background-color',
	attributes: {
		style: 'style',
		class: 'class',
	},
	edit: BackgroundColorEdit,
};
