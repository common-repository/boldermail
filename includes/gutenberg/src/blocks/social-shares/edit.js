// Internal dependencies.
import SocialSharesInspector from './inspector';
import SocialSharesControls from './controls';
import SocialSharesWrapper from './wrapper';

// WordPress dependencies.
const { __ } = wp.i18n;
const { InnerBlocks } = wp.blockEditor;
const { useSelect, useDispatch } = wp.data;
const { Fragment } = wp.element;

// Save function.
export default function edit( props ) {
	const { attributes: {
		shareContent,
		customURL,
		urlDesc,
		iconStyle,
		iconColor,
		fontFamily,
		fontSize,
		textColor,
		buttonBorderWidth,
		buttonBorderStyle,
		buttonBorderColor,
		buttonBorderRadius,
		buttonBackgroundColor,
	}, className, clientId } = props;

	const { innerBlocks } = useSelect(
		( select ) => {
			const { getBlock } = select( 'core/block-editor' );
			const block = getBlock( clientId );
			return {
				innerBlocks: ( block && block.innerBlocks ) ? block.innerBlocks : null,
			};
		},
		[ clientId ]
	);

	const { updateBlockAttributes } = useDispatch( 'core/block-editor' );
	useEffect(() => {
		innerBlocks.forEach( ( childrenBlock ) => {
			const { clientId: childClientId } = childrenBlock;
			updateBlockAttributes( childClientId, {
				shareContent,
				customURL,
				urlDesc,
				iconStyle,
				iconColor,
				fontFamily,
				fontSize,
				textColor,
				buttonBorderWidth,
				buttonBorderStyle,
				buttonBorderColor,
				buttonBorderRadius,
				buttonBackgroundColor,
			} );
		} );
	}, [
		innerBlocks,
		shareContent,
		customURL,
		urlDesc,
		iconStyle,
		iconColor,
		fontFamily,
		fontSize,
		textColor,
		buttonBorderWidth,
		buttonBorderStyle,
		buttonBorderColor,
		buttonBorderRadius,
		buttonBackgroundColor,
		updateBlockAttributes,
	] );

	return (
		<Fragment>
			<SocialSharesInspector { ...props } />
			<SocialSharesControls { ...props } />
			<div className={ className }>
				<SocialSharesWrapper { ...props }>
					<InnerBlocks
						allowedBlocks={ [ 'boldermail/social-share' ] }
						template={ [
							[ 'boldermail/social-share', {
								service: 'facebook',
								label: __( 'Share', 'boldermail' ),
								url: 'http://www.facebook.com/sharer/sharer.php?u=[boldermail_permalink]',
							} ],
						] }
						templateLock={ false }
						__experimentalMoverDirection={ 'horizontal' }
						renderAppender={ () => (
							<InnerBlocks.ButtonBlockAppender />
						) }
					/>
				</SocialSharesWrapper>
			</div>
		</Fragment>
	);
}
