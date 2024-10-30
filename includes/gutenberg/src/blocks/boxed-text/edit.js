// Internal dependencies.
import BoxedTextWrapper from './wrapper';
import BoxedTextControls from './controls';
import BoxedTextInspector from './inspector';
import ALLOWED_BLOCKS from '../template-part/allowed-blocks';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;
const { Fragment } = wp.element;

// Edit function.
export default function Edit( props ) {
	return (
		<Fragment>
			<BoxedTextControls { ...props } />
			<BoxedTextInspector { ...props } />
			<BoxedTextWrapper { ...props }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS.filter( ( e ) => e !== 'boldermail/columns' && e !== 'boldermail/boxed-text' ) }
					template={ [
						[ 'boldermail/paragraph' ],
					] }
					templateLock={ false }
					renderAppender={ () => <InnerBlocks.ButtonBlockAppender /> }
				/>
			</BoxedTextWrapper>
		</Fragment>
	);
}
