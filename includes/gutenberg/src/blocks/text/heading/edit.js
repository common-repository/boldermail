// Internal dependencies.
import { name } from './block.json';
import ALLOWED_FORMATS from '../allowed-formats';
import TextInspector from '../inspector';
import TextWrapper from '../wrapper';
import HeadingToolbar from './heading-toolbar';

// WordPress dependencies.
const { __ } = wp.i18n;
const { createBlock } = wp.blocks;
const { InspectorControls, RichText } = wp.blockEditor;
const { PanelBody } = wp.components;
const { useRef, Fragment } = wp.element;

// Edit function.
export default function edit( props ) {
	const { attributes: {
		content,
		level,
		textAlign,
		fontFamily,
		fontSize,
		letterSpacing,
		lineHeight,
		color,
	}, attributes, setAttributes, mergeBlocks, onReplace, clientId } = props;

	const ref = useRef();
	const tagName = 'h' + level;

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Heading settings', 'boldermail' ) }>
					<p>{ __( 'Level', 'boldermail' ) }</p>
					<HeadingToolbar
						isCollapsed={ false }
						minLevel={ 1 }
						maxLevel={ 7 }
						selectedLevel={ level }
						onChange={ ( newLevel ) =>
							setAttributes( { level: newLevel } )
						}
					/>
				</PanelBody>
			</InspectorControls>
			<TextInspector { ...props } />
			<TextWrapper { ...props }>
				<RichText
					ref={ ref }
					identifier="content"
					tagName={ tagName }
					placeholder={ __( 'Write heading...', 'boldermail' ) }
					allowedFormats={ ALLOWED_FORMATS }
					value={ content }
					onChange={ ( value ) => setAttributes( { content: value } ) }
					onSplit={ ( value, isOriginal ) => {
						let block;

						if ( isOriginal || value ) {
							block = createBlock( 'boldermail/heading', {
								...attributes,
								content: value,
							} );
						} else {
							block = createBlock( 'boldermail/paragraph' );
						}

						if ( isOriginal ) {
							block.clientId = clientId;
						}

						return block;
					} }
					onMerge={ mergeBlocks }
					onReplace={ onReplace }
					onRemove={ () => onReplace( [] ) }
					style={ {
						textAlign,
						fontFamily,
						fontSize: fontSize ? fontSize + 'px' : undefined,
						letterSpacing,
						lineHeight,
						color,
					} }
				/>
			</TextWrapper>
		</Fragment>
	);
}
