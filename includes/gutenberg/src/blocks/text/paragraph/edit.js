// Internal dependencies.
import { name } from './block.json';
import ALLOWED_FORMATS from '../allowed-formats';
import TextInspector from '../inspector';
import TextWrapper from '../wrapper';

// WordPress dependencies.
const { __ } = wp.i18n;
const { createBlock } = wp.blocks;
const { RichText } = wp.blockEditor;
const { useRef, Fragment } = wp.element;

// Edit function.
export default function edit( props ) {
	const { attributes: {
		content,
		textAlign,
		fontFamily,
		fontSize,
		letterSpacing,
		lineHeight,
		color
	}, attributes, setAttributes, mergeBlocks, onReplace, clientId } = props;

	const ref = useRef();

	return (
		<Fragment>
			<TextInspector { ...props } />
			<TextWrapper { ...props }>
				<RichText
					ref={ ref }
					identifier="content"
					tagName="p"
					placeholder={ __( 'Add text...', 'boldermail' ) }
					allowedFormats={ ALLOWED_FORMATS }
					value={ content }
					onChange={ ( value ) => setAttributes( { content: value } ) }
					onSplit={ ( value, isOriginal ) => {
						let newAttributes;

						if ( isOriginal || value ) {
							newAttributes = {
								...attributes,
								content: value,
							};
						}

						const block = createBlock( name, newAttributes );

						if ( isOriginal ) {
							block.clientId = clientId;
						}

						return block;
					} }
					onMerge={ mergeBlocks }
					onReplace={ onReplace }
					onRemove={ onReplace ? () => onReplace( [] ) : undefined }
					aria-label={ content ? __( 'Paragraph block', 'boldermail' ) : __( 'Empty block -- start writing!', 'boldermail' ) }
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
