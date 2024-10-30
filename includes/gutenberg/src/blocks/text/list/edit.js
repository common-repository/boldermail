// Internal dependencies.
import { name } from './block.json';
import ALLOWED_FORMATS from '../allowed-formats';
import TextInspector from '../inspector';
import TextWrapper from '../wrapper';
import OrderedListSettings from './ordered-list-settings';

// WordPress dependencies.
const { __, _x } = wp.i18n;
const { createBlock } = wp.blocks;
const {
	RichText,
	BlockControls,
	RichTextShortcut,
} = wp.blockEditor;
const { ToolbarGroup } = wp.components;
const { Fragment } = wp.element;
const {
	__unstableCanIndentListItems: canIndentListItems,
	__unstableCanOutdentListItems: canOutdentListItems,
	__unstableIndentListItems: indentListItems,
	__unstableOutdentListItems: outdentListItems,
	__unstableChangeListType: changeListType,
	__unstableIsListRootSelected: isListRootSelected,
	__unstableIsActiveListType: isActiveListType,
} = wp.richText;

// Edit function.
export default function edit( props ) {
	const {
		attributes: {
			ordered,
			values,
			type,
			reversed,
			start,
			textAlign,
			fontFamily,
			fontSize,
			letterSpacing,
			lineHeight,
			color,
		},
		attributes,
		setAttributes,
		mergeBlocks,
		onReplace,
		isSelected,
	} = props;
	const tagName = ordered ? 'ol' : 'ul';

	const controls = ( { value, onChange, onFocus } ) => (
		<Fragment>
			{ isSelected && (
				<Fragment>
					<RichTextShortcut
						type="primary"
						character="["
						onUse={ () => {
							onChange( outdentListItems( value ) );
						} }
					/>
					<RichTextShortcut
						type="primary"
						character="]"
						onUse={ () => {
							onChange(
								indentListItems( value, { type: tagName } )
							);
						} }
					/>
					<RichTextShortcut
						type="primary"
						character="m"
						onUse={ () => {
							onChange(
								indentListItems( value, { type: tagName } )
							);
						} }
					/>
					<RichTextShortcut
						type="primaryShift"
						character="m"
						onUse={ () => {
							onChange( outdentListItems( value ) );
						} }
					/>
				</Fragment>
			) }
			<BlockControls>
				<ToolbarGroup
					controls={ [
						{
							icon: 'editor-ul',
							title: __( 'Convert to unordered list', 'boldermail' ),
							isActive: ordered === false,
							onClick() {
								onChange(
									setAttributes( { ordered: false } )
								);
								onFocus();

								if ( isListRootSelected( value ) ) {
									setAttributes( { ordered: false } );
								}
							},
						},
						{
							icon: 'editor-ol',
							title: __( 'Convert to ordered list', 'boldermail' ),
							isActive: ordered === true,
							onClick() {
								onChange(
									setAttributes( { ordered: true } )
								);
								onFocus();

								if ( isListRootSelected( value ) ) {
									setAttributes( { ordered: true } );
								}
							},
						},
						// {
						// 	icon: 'editor-outdent',
						// 	title: __( 'Outdent list item', 'boldermail' ),
						// 	shortcut: _x( 'Backspace', 'keyboard key', 'boldermail' ),
						// 	isDisabled: ! canOutdentListItems( value ),
						// 	onClick() {
						// 		onChange( outdentListItems( value ) );
						// 		onFocus();
						// 	},
						// },
						// {
						// 	icon: 'editor-indent',
						// 	title: __( 'Indent list item', 'boldermail' ),
						// 	shortcut: _x( 'Space', 'keyboard key', 'boldermail' ),
						// 	isDisabled: ! canIndentListItems( value ),
						// 	onClick() {
						// 		onChange(
						// 			indentListItems( value, { type: tagName } )
						// 		);
						// 		onFocus();
						// 	},
						// },
					] }
				/>
			</BlockControls>
		</Fragment>
	);

	return (
		<Fragment>
			<TextInspector { ...props } />
			<TextWrapper { ...props }>
				<RichText
					identifier="values"
					multiline="li"
					tagName={ tagName }
					placeholder={ __( 'Write list...', 'boldermail' ) }
					allowedFormats={ ALLOWED_FORMATS }
					value={ values }
					onChange={ ( nextValues ) =>
						setAttributes( { values: nextValues } )
					}
					onMerge={ mergeBlocks }
					onSplit={ ( value ) =>
						createBlock( name, { ...attributes, values: value } )
					}
					__unstableOnSplitMiddle={ () =>
						createBlock( 'boldermail/paragraph' )
					}
					onReplace={ onReplace }
					onRemove={ () => onReplace( [] ) }
					start={ start }
					reversed={ reversed }
					type={ type }
					style={ {
						textAlign,
						fontFamily,
						fontSize: fontSize ? fontSize + 'px' : undefined,
						letterSpacing,
						lineHeight,
						color,
					} }
				>
					{ controls }
				</RichText>
			</TextWrapper>
			{ ordered && (
				<OrderedListSettings
					setAttributes={ setAttributes }
					ordered={ ordered }
					reversed={ reversed }
					start={ start }
				/>
			) }
		</Fragment>
	);
}
