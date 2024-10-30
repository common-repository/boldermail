// Internal dependencies.
import SocialLinksInspector from './inspector';
import SocialLinksControls from './controls';
import SocialLinksWrapper from './wrapper';

// External dependencies.
import classnames from 'classnames';

// WordPress dependencies.
const { InnerBlocks } = wp.blockEditor;
const { useSelect, useDispatch } = wp.data;
const { Fragment, useEffect } = wp.element;

// Save function
export default function edit( props ) {
	const { attributes: {
		display,
		iconStyle,
		iconSize,
		iconColor,
		fontFamily,
		fontSize,
		textColor,
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
				display,
				iconStyle,
				iconSize,
				iconColor,
				fontFamily,
				fontSize,
				textColor,
			} );
		} );
	}, [
		innerBlocks,
		display,
		iconStyle,
		iconSize,
		iconColor,
		fontFamily,
		fontSize,
		textColor,
		updateBlockAttributes,
	] );

	return (
		<Fragment>
			<SocialLinksInspector { ...props } />
			<SocialLinksControls { ...props } />
			<div className={ classnames( className, { 'is-text-only': display === 'text' }, { 'is-icon-size-large': ( display === 'icon' || display === 'both' ) && iconSize === 96 } ) }>
				<SocialLinksWrapper { ...props }>
					<InnerBlocks
						allowedBlocks={ [ 'boldermail/social-link' ] }
						template={ [
							[ 'boldermail/social-link', {
								service: 'facebook',
								url: 'https://www.facebook.com',
								label: 'Facebook',
							} ],
						] }
						templateLock={ false }
						__experimentalMoverDirection={ 'horizontal' }
						renderAppender={ () => (
							<InnerBlocks.ButtonBlockAppender />
						) }
					/>
				</SocialLinksWrapper>
			</div>
		</Fragment>
	);
}
