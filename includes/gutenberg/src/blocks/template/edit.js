// Internal dependencies.
import { getAttributesByPart } from '../template-part/utils';
import TemplateControls from './controls';
import TemplateInspector from './inspector';
import TemplateStyleInspector from './style-inspector';
import TemplateWrapper from './wrapper';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;
const { useSelect, useDispatch } = wp.data;
const { Fragment, useEffect } = wp.element;

// Save function.
export default function edit( props ) {
	const { attributes: {
		style,
		blockAlignment,
	}, className, clientId } = props;

	const { innerBlocks } = useSelect(
		( select ) => {
			const { getBlock } = select( 'core/block-editor' );
			const block = getBlock( clientId );
			return {
				innerBlocks: block && block.innerBlocks ? block.innerBlocks : [],
			};
		},
		[ clientId ]
	);

	const { updateBlockAttributes } = useDispatch( 'core/block-editor' );
	useEffect( () => {
		innerBlocks.forEach( ( childrenBlock ) => {
			const { clientId: childClientId } = childrenBlock;
			updateBlockAttributes( childClientId, {
				isWideWidth: blockAlignment === 'wide',
			} );
		} );
	}, [ innerBlocks, blockAlignment, updateBlockAttributes ] );

	return (
		<Fragment>
			<style>{ style }</style>
			<TemplateInspector { ...props } />
			<TemplateStyleInspector { ...props } />
			<TemplateControls { ...props } />
			<div className={ className } id="bmBody">
				<TemplateWrapper { ...props }>
					<InnerBlocks
						allowedBlocks={ [ 'boldermail/template-part' ] }
						template={ [
							[ 'boldermail/template-part', getAttributesByPart( 'preheader' ) ],
							[ 'boldermail/template-part', getAttributesByPart( 'header' ) ],
							[ 'boldermail/template-part', getAttributesByPart( 'body' ) ],
							[ 'boldermail/template-part', getAttributesByPart( 'footer' ) ],
						] }
						templateLock={ 'insert' }
						renderAppender={ () => (
							<InnerBlocks.ButtonBlockAppender />
						) }
					/>
				</TemplateWrapper>
			</div>
		</Fragment>
	);
}
